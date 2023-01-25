@extends('layouts.admin')

@section('content')

<div class="page-top d-flex justify-content-between">
    <h1>Posts</h1>
    <a class="btn btn-primary m-3" href="{{route('admin.posts.create')}}" role="button">
        Add Post <i class="fas fa-plus fa-sm fa-fw"></i>
    </a>
</div>
<!-- TODO: move in a partial -->
@include('partials.session-message')
@include('partials.errors')


<div class="table-responsive">
    <table class="table table-striped
    table-hover
    table-borderless
    table-primary
    align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Cover Image</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($posts as $post)
            <tr class="table-primary">
                <td scope="row">{{$post->id}}</td>
                <td>

                    @if($post->cover_image)
                    <img width="140" class="img-fluid" src="{{asset('storage/' . $post->cover_image)}}" alt="">
                    @else
                    <div class="placeholder p-5 bg-secondary d-flex align-items-center justify-content-center" style="width:140px">Placeholder</div>
                    @endif

                </td>
                <td>{{$post->title}}</td>
                <td>{{$post->slug}}</td>
                <td>

                    <a class="btn btn-primary btn-sm" href="{{route('admin.posts.show', $post->slug)}}" role="button"><i class="fas fa-eye fa-sm fa-fw"></i></a>
                    <a class="btn btn-secondary btn-sm" href="{{route('admin.posts.edit', $post->slug)}}"><i class="fas fa-pencil fa-sm fa-fw"></i></a>





                    <!-- Modal trigger button -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#post-{{$post->id}}">
                        <i class="fas fa-trash fa-sm fa-fw"></i>
                    </button>

                    <!-- Modal Body -->
                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                    <div class="modal fade" id="post-{{$post->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modal-{{$post->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-{{$post->id}}">Delete {{$post->title}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this post?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                    <form action="{{route('admin.posts.destroy', $post->slug)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>







                </td>
            </tr>
            @empty
            <tr>
                <td>No posts in the db yet</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <!-- TODO: fix style -->
    {{$posts->links('vendor.pagination.bootstrap-5')}}


</div>



@endsection
