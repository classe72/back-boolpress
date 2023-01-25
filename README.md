# Laravel Auth (Relationships)

Tables:

- users
- posts
- categories

Models:

- User
- Post
- Category

## Add categories relation with posts

### Creazione modello Category

`php artisan make:model Category`

### Creazione controller Category nel name space Admin

`php artisan make:controller Admin/CategoryController -r --model=Category`

### Creazione migration

`php artisan make:migration create_categories_table`

```php
 public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug');
            $table->timestamps();
        });
    }
```

### Creazione seeder

`php artisan make:seeder CategorySeeder`

```php
  public function run()
    {
        $categories = ['Programming', 'Laravel', 'Vuejs', 'front-end', 'back-end', 'full-stack'];

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->name = $category;
            $newCategory->slug = Str::slug($newCategory->name);
            $newCategory->save();
        }
    }
```

### Add migration for foreignkey to posts table

`php artisan make:migration add_category_id_foreign_to_posts_table`

Implementation up method

```php
 public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }
```

implement the opposit in the down method

```php

public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
```

🤓 Quello che fate nel metodo up fa fatto al contrario nel metodo down:

- prima droppo la chiave esterna
- poi droppo la colonna

### Migrate the db and seeding

`php artisan migrate`
`php artisan db:seed --class=CategorySeeder`

### Relationships inside each model

Add the hasMany relationship to the Category Model

```php
 /**
     * Get all of the posts for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

```

👉 Una catagoria ha molti posts.
⚡ Ricorda di importare la classe HasMany `use Illuminate\Database\Eloquent\Relations\HasMany;` all'inizio del Model dopo il namespace.

Add the inverse relationship inside the Post model

```php


    /**
     * Get the category that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
```

⚡ Ricorda di importare la classe HasMany `use Illuminate\Database\Eloquent\Relations\BelongsTo;` all'inizio del Model dopo il namespace.

⚡Ricorda di settare category_id nelle fillable properties: `protected $fillable = ['title', 'body', 'slug', 'cover_image', 'category_id'];`

## Update CRUD ops for Posts

### update Create operations

Inside the postController pass all categories to the create and update methods

```php

 public function create()
    {
        $categories = Category::all(); //👈 get all categories
        //dd($categories);
        return view('admin.posts.create', compact('categories'));
        // pass the categories to the view        👆
    }
```

Edit the create view to show a select for categories
admin/posts/create.blade.php

```html

<div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select form-select-lg @error('category_id') 'is-invalid' @enderror" name="category_id" id="category_id">
            <option selected>Select one</option>

            @foreach ($categories as $category )
            <option value="{{$category->id}}" {{ old('category_id') ? 'selected' : '' }}>{{$category->name}}</option>
            @endforeach

        </select>
    </div>
    @error('category_id')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror
```

Validate categories inside the StorePostRquest

```php
public function rules()
    {
        return [
            'title' => 'required|unique:posts,title|max:100',
            'cover_image' => 'nullable|image|max:300',
            'category_id' => 'nullable|exists:categories,id', // 👈 The category id must exist!!
            'body' => 'nullable'
        ];
    }
```

### Update Edit operation

Add all categories to the method and pass them to the view

```php
 public function edit(Post $post)
    {
        $categories = Category::all(); //👈 get all categories
        return view('admin.posts.edit', compact('post', 'categories'));
        // pass the categories to the view                👆
    }

```

Add a select to the edit.blade.php view

```html
 
    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-select form-select-lg @error('category_id') 'is-invalid' @enderror" name="category_id" id="category_id">
            <option value="">Uncategorize</option>

            @forelse ($categories as $category )
            <!-- Check if the post has a category assigned or not                                    👇 -->
            <option value="{{$category->id}}" {{ $category->id == old('category_id',  $post->category ? $post->category->id : '') ? 'selected' : '' }}>
                {{$category->name}}
            </option>
            @empty
            <option value="">Sorry, no categories in the system.</option>
            @endforelse

        </select>
    </div>
    @error('category_id')
    <div class="alert alert-danger" role="alert">
        {{$message}}
    </div>
    @enderror
```

Update validation in the UpdatePostRequest

```php
public function rules()
    {
        return [
            'title' => ['required', 'max:100', Rule::unique('posts')->ignore($this->post->id)],
            'cover_image' => ['nullable', 'image', 'max:300'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'body' => ['nullable']
        ];
    }

```

Show category inside the posts.show view

```html

<div class="category">
    <strong>Category:</strong>
    {{ $post->category ? $post->category->name : 'Uncategorized'}}
</div>

```
