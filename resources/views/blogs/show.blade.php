<x-app-layout>
    <p>{{ session('status') }}</p>
    <div id="blog-container">
        <div id="blog-meta">
            <h1 id="blog-heading">{{ $blog->title }}</h1>
            <!-- Blog info -->
            <p id="blog-info">
                <div>
                    <strong><em>Author</em>: </strong>{{$blog->user->name}}
                </div>
            </p>
            @if (auth()->user()->id === $blog->user->id)
            <a href="{{route('blogs.edit', $blog->id)}}">
                <button id="blog-edit-link" class="disable-after-click">Edit</button>
            </a>
            @endif
        </div>
        <hr>
        <div id="blog-content" 
            data-mode="show"
            data-content="{{ $blog->content }}"
        >
        </div>
        <hr>
        <!-- Comments -->
        <h2>Comments</h2>
    </div>

    @push('scripts')
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush

    @push('styles-prepend')
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush
</x-app-layout>