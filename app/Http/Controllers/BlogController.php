<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\User;
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
        return $this->storeOrUpdate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blogs.show', compact(['blog']));
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
    public function update(Request $request, Blog $blog)
    {
        if($blog->user_id != request()->user()->id) {
            abort(403);
        }
        return $this->storeOrUpdate($request, $blog);
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

    private function storeOrUpdate(Request $request, $blog = null)
    {
        $updating = true;
        if(!$blog) {
            $updating = false;
        }
        $errors = [];
        $content = json_decode($request->content);
        
        // Title is null
        if(!$request->title) {
            $errors['title'] = 'Title is required';
        }
        $isEmpty = true;
        // If content is null (contains more than just \n and spaces)
        foreach ($content->ops as $key => $value) {
            if(isset($value->insert->image)) {
                $isEmpty = false;
                break;
            }

            $str = str_replace(" ", "", $value->insert);
            $str = str_replace("\n", "", $str);

            if(strlen($str) > 0) {
                $isEmpty = false;
                break;
            }
        }

        if($isEmpty) {
            $errors['content'] = 'Content is required';
        }

        if(count($errors) == 0) {
            if(!$updating) {
                // Next id in case of creating
                // - If updating use same id
                $blog = Blog::create([
                    'title' => 'temp',
                    'content' => json_encode('temp'),
                    'user_id' => User::max('id')
                ]);
            }
            // If updating
            // - Delete old images
            $exclude = [];
            foreach ($content->ops as $key => $value) {
                if(isset($value->insert->image)) {
                    // Convert base64 string to image
                    // data:image/png;base64,iVBORw0KGg
                    // If updating (32a41230763f79dc86ad47dee38fee462.jpeg) $data[1] won't exist
                    $b64 = $value->insert->image;
                    $data = explode(',', $b64);
                    // New image
                    if(isset($data[1])) {
                        // iVBORw0KGg
                        $imgStr = $data[1];
                        // .png
                        $extension = '.' . explode(';', explode('/', $data[0])[1])[0];
                        $filename = md5(time().uniqid()) . $extension;
                        // actual image file
                        $img=base64_decode($imgStr);
                        // Store image
                        // -- Start transaction here
                        $filepath = '/uploads/blogs/' . $blog->id . '/' . $filename;
                        Storage::disk('public')->put($filepath, $img);
                        // Store in exclude array
                        $exclude[] = $filepath;
                        // Replace b64 with image url
                        $content->ops[$key]->insert->image = $filepath;
                    }
                    // Old image
                    else if($updating) {
                        // Store in exclude array
                        $exclude[] = $value->insert->image;
                    }
                    else {
                        abort(422);
                    }
                }
            }

            // Delete images that are not needed
            $files = glob(storage_path("app/public/uploads/blogs/").$blog->id."/*");
            $imgsToDeleteTemp = array_filter($files, 
                function($file) use($exclude) {
                    return !in_array(explode("/public", $file)[1], $exclude);
                }
            );
            $imgsToDelete = array_map(
                function ($file) {
                    return explode("/public", $file)[1];
                }, 
                $imgsToDeleteTemp
            );
            // Delete
            Storage::disk("public")->delete($imgsToDelete);
        }
        else {
            return response()->json([
                'errors' => $errors
            ]);
        }
        
        // Store in database
        $blog->title = $request->title;
        $blog->content = json_encode($content);
        request()->user()->blogs()->save($blog);
        $blog->refresh();
        // Flash message
        if ($updating) {
            $request->session()->flash('status', 'Blog updated successfully!');
        }
        else {
            $request->session()->flash('status', 'Blog added successfully!');
        }
        return response()->json([
            'route' => route('blogs.show', $blog->id)
        ]);
    }
}
