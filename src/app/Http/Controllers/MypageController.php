<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class MypageController extends Controller
{
    private function getProfile()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return compact('user', 'profile');
    }

    public function index()
    {
        $profileData = $this->getProfile();

        return view('mypage.index', $profileData);
    }

    public function edit()
    {
        $profileData = $this->getProfile();

        return view('mypage.edit', $profileData);
    }

    // プロフィールの登録、更新処理
    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;
        $data = $request->validated();

        if ($request->has('user_image')) {
            if ($profile && $profile->user_image) {
                Storage::disk('public')->delete($profile->user_image);
            }
            $data['user_image'] = $request->file('user_image')->store('profile-images', 'public');
        }

        if (isset($data['user_name'])) {
            $user->update(['name' => $data['user_name']]);
            unset($data['user_name']); // profileテーブルには不要のため
        }

        if ($profile) {
            $profile->update($data);
        } else {
            $data['user_id'] = $user->id;
            Profile::create($data);
        }

        return to_route('index', ['tab' => 'mylist']);
    }
}
