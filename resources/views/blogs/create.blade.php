<x-app-layout>
    <div id="form-container">
        <h1 id="form-heading">Add Blog</h1>
        <form action="{{ route('blogs.store') }}" method="POST" class="data-form">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="Blog title" value="{{ old('title', '') }}" autofocus>
                @error('title')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" placeholder="Blog content goes here">{{ old('content', '') }}</textarea>
                @error('content')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group" id="form-btn-container">
                <button type="submit" class="prevent-multiple-submit">Save</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>
</x-app-layout>