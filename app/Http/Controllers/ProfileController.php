<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile.show', compact(['user']));
    }

    public function update(Request $request)
    {
        $user = request()->user();
        $profile = request()->user()->profile;

        // Basic
        if($request->type == 'basic') {
            $validated = $request->validate([
                'name' => 'required',
            ]);

            $user->name = $validated['name'];
            $user->save();
        }

        // About
        if($request->type == 'about') {
            $validated = $request->validate([
                'bio' => 'nullable|max:300'
            ]);

            $profile->bio = $validated['bio'];
            $profile->save();
        }

        // Social
        if($request->type == 'social') {
            $validated = $request->validate([
                'facebook_link' => 'nullable',
                'twitter_link' => 'nullable',
            ]);

            $profile->facebook_link = $validated['facebook_link'];
            $profile->twitter_link = $validated['twitter_link'];
            $profile->save();
        }

        // Avatar
        if($request->type == 'avatar') {
            
            $validated = $request->validate([
                'avatar' => 'required|mimes:jpg,bmp,png'
            ]);

            // Delete previous store new
            $fileName = 'uploads/avatars/'.$request->user()->id;
            // - Delete
            Storage::disk('public')->delete([$fileName.'.jpg', $fileName.'.bmp', $fileName.'.png']);
            // - Store
            $path = $validated['avatar']->storeAs(
                'uploads/avatars',
                $request->user()->id . '.' . $validated['avatar']->extension(),
                'public'
            );

            $profile->avatar = asset($path);
            $profile->save();
        }

        $user->refresh();
        $profile->refresh();

        return response()->json(compact('user', 'profile'));
    }

    public function getSocialLinks(Profile $profile)
    {
        $data = [
            'facebook_link' => $profile['facebook_link'],
            'twitter_link' => $profile['twitter_link']
        ];

        return response()->json($data);
    }
}
