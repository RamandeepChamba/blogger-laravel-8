<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBlogRequest;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        $validated = $request->validated();
        $blog = new Blog($validated);
        request()->user()->blogs()->save($blog);

        return redirect()->route('blogs.show', $blog->id);
        */
        $content = $request->content['ops'];

        foreach ($content as $key => $value) {
            if(isset($value['insert']['image'])) {
                // Convert base64 string to image
                // data:image/png;base64,iVBORw0KGg
                $b64 = $value['insert']['image'];
                $data = explode(',', $b64);
                // iVBORw0KGg
                $imgStr = $data[1];
                // .png
                $extension = '.' . explode(';', explode('/', $data[0])[1])[0];
                $filename = md5(time().uniqid()) . $extension;
                // actual image file
                $img=base64_decode($imgStr);
                // Store image
                // - TODO
                // -- Start transaction here
                // Next id in case of creating
                // If updating use same id
                $filepath = 'blogs/' . Blog::getNextId() . '/' . $filename;
                Storage::disk('public')->put($filepath, $img);
                // Replace b64 with image url
                $content[$key]['insert']['image'] = $filepath;
            }
        }
        // Store in database
        // - TODO
        return response()->json($content);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        dd($blog);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        // Check if current user is the author of the blog
        if($blog->user_id != request()->user()->id) {
            abort(403);
        }
        return view('blogs.edit', compact(['blog']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBlogRequest $request, Blog $blog)
    {
        if($blog->user_id != request()->user()->id) {
            abort(403);
        }
        $validated = $request->validated();
        $blog->fill($validated);
        $blog->save();

        return redirect()->route('blogs.show', $blog->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if($blog->user_id != request()->user()->id) {
            abort(403);
        }

        $blog->delete();

        return redirect()->route('blogs.index');
    }
}
