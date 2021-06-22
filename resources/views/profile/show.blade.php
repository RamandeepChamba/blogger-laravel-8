@php
    $avatar = $user->profile->avatar ?? "https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Jennifer_Lawrence_in_2016.jpg/220px-Jennifer_Lawrence_in_2016.jpg";
@endphp
<x-app-layout>
    <div id="profile-show-container">
        <div id="profile-card" data-profile-id="{{ $user->profile->id }}">
            <!-- Left -->
            <div id="profile-card-left">
                <!-- Avatar -->
                <div id="profile-card-img">
                    <div id="avatar-box">
                        <img id="avatar" src="{{ $avatar }}" alt="avatar">
                        @if (auth()->user()->id == $user->id)
                        <label for="profile-card-avatar" class="bg-yellow-400" id="avatar-edit-btn">Edit
                        </label>    
                        @endif
                    </div>
                    
                    <input type="file" name="avatar" id="profile-card-avatar">
                    <button id="avatar-save-btn" class="hidden">Save</button>
                    <button id="avatar-cancel-btn" class="hidden">Cancel</button>
                </div>
                <!-- Categories -->
                <div id="profile-card-section-labels">
                    <button class="active-section-label" data-section-id="basic-info">Basic Details</button>
                    <button data-section-id="about">About</button>
                    <button data-section-id="social-links">Social Life</button>
                    @if (auth()->user()->id == $user->id)
                        <button data-section-id="settings">Settings</button>
                    @endif
                </div>
            </div>
            <!-- Right -->
            <div id="profile-card-info">
                @if (auth()->user()->id == $user->id)
                    <button class="bg-yellow-400" id="edit-profile-btn">Edit</button>
                @endif
                <form action="#" id="profile-form">
                    <!-- Basic Info -->
                    <div class="profile-card-section" id="profile-card-section-basic-info">
                        <label for="name">Name</label>
                        <input type="text" id="name" value="{{ $user->name }}" disabled>

                        <div>
                            <label for="email">Email</label>
                            <span id="email">{{ $user->email }}</span>
                        </div>
                    </div>

                    <!-- About -->
                    <div class="profile-card-section hidden" id="profile-card-section-about">
                        <label for="bio">Bio</label>
                        <textarea name="bio" id="bio" placeholder="Describe yourself here..." disabled>{{ $user->profile->bio }}</textarea>
                    </div>
                    <!-- Social Links -->
                    <div class="profile-card-section hidden" id="profile-card-section-social-links">
                        <h5>Facebook</h5>
                        <a href="" id="facebook_link_anchor" target="_blank">
                            <!-- Ajax -->
                        </a>
                        <p id="facebook_link_absent" class="hidden">N/A</p>
                        <input type="text" name="facebook_link" id="facebook_link" class="hidden">

                        <h5>Twitter</h5>
                        <a href="" id="twitter_link_anchor" target="_blank">
                            <!-- Ajax -->
                        </a>
                        <p id="twitter_link_absent" class="hidden">N/A</p>
                        <input type="text" name="twitter_link" id="twitter_link" class="hidden">
                    </div>
                    <!-- Settings -->
                    @if (auth()->user()->id == $user->id)
                        <div class="profile-card-section hidden dontHaveEditBtn" id="profile-card-section-settings">
                            <button class="bg-red-400">Delete Account</button>
                        </div>
                    @endif
                    
                    <button type="reset" class="bg-blue-200 hidden" id="cancel-profile-btn">Cancel</button>
                    <button class="bg-blue-200 hidden" id="save-profile-btn">Save</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
