@extends('layouts.admin')


@section('content')


<h1 class="pb-4">All Tags here</h1>
@include('partials.session-message')

@include('partials.errors')

<div class="container-fluid">
    <div class="row">
        <div class="col">

            <form action="{{route('admin.tags.store')}}" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input id="name" name="name" type="text" class="form-control" placeholder="Tag name" aria-label="Tag name " aria-describedby="button-addon">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">Add</button>
                </div>
            </form>
        </div>
        <div class="col">

            <div class="table-responsive-md">
                <table class="table table-striped table-hover table-borderless table-primary align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Posts Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">

                        @forelse ($tags as $tag)

                        <tr class="table-primary">
                            <td scope="row">{{$tag->id}}</td>
                            <td>
                                <form action="{{route('admin.tags.update', $tag->slug)}}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" id="name" class="form-control" value="{{$tag->name}}">
                                    <small>Press enter to update the tag name</small>
                                </form>
                            </td>
                            <td>{{$tag->slug}}</td>
                            <td><span class="badge bg-info">{{count($tag->posts)}}</span></td>
                            <td>
                                <form action="{{route('admin.tags.destroy', $tag->slug)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>


                        @empty
                        <tr class="table-primary">
                            <td scope="row">No tags</td>

                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>



        </div>

    </div>
</div>


@endsection
