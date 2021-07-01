<x-app-layout>
    <div id="blog-list-container">
        @if (count($blogs))
        <!-- Blog list -->
        <div id="blog-list">
        @foreach ($blogs as $blog)
            <div class="blog-list-item">
                <!-- Blog title -->
                <h2 class="blog-list-item-heading">{{$blog->title}}</h2>
                <hr>
                <!-- Blog info -->
                <div class="blog-list-item-meta">
                    <!-- Author -->
                    <p><strong>Author: </strong>
                        <a href="{{route('profile.show', $blog->user->id)}}">{{$blog->user->name}}</a>
                    </p>
                    <!-- Created At -->
                    <p><strong>Created At: </strong>{{$blog->created_at}}</p>
                    <!-- Updated At -->
                    <p><strong>Updated At: </strong>{{$blog->updated_at}}</p>
                </div>
                <!-- Further Links -->
                <a href="{{route('blogs.show', $blog->id)}}">
                    <button class="blog-list-item-button blog-list-item-button-view">View</button>
                </a>
                @if(auth()->user()->id === $blog->user->id)
                <a href="{{route('blogs.edit', $blog->id)}}">
                    <button class="blog-list-item-button blog-list-item-edit">Edit</button>
                </a>
                @endif
            </div>
        @endforeach
        </div>
        @else
        <h1>No blogs here</h1>
        @endif
    </div>
</x-app-layout>