<?php

namespace App\Http\Controllers;

use Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $photoPath = $user->userDetail->photo ?? null;
        
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
        ]);
        
        $userDetail = $user->userDetail;
        $userDetail->update([
            'photo' => $photoPath,
            'description' => $request->input('description'),
        ]);

        if ($request->hasFile('photo')) {
            // Cek apakah userDetail memiliki foto sebelumnya dan jika ada, hapus file tersebut
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
    
            // Simpan foto baru
            $imageName = time() . '.' . $request->photo->extension();
            $imagePath = Storage::disk('public')->putFileAs('photos', $request->file('photo'), $imageName);
    
            // Simpan nama file di tabel user_details
            $userDetail->photo = $imagePath;
        }
    
        $userDetail->save();

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}