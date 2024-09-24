<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index($user_id)
    {
        $user_data = User::where('id', $user_id)->first();

        // dd($user_data);
        return view('users-profile', compact('user_data'));
    }


    public function update(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = uniqid() . '.' . $file->extension();

            // Move the new file to the upload directory
            $file->move(public_path('uploads/user_profile/' . $user_id . '/'), $fileName);

            // Delete the old profile file if it exists
            if ($user->profile) {
                $oldFilePath = public_path('uploads/user_profile/' . $user_id . '/' . $user->profile);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Update user's profile with the new file
            $user->profile = $fileName;
        }

        $user->save();
        return redirect()->route('users_profile', $user_id)->with('success', 'Profile Updated Successfully');
    }

    public function password_reset(Request $request, $user_id)
    {

        // dd($request);

        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($user_id);

        // dd($user);

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
        }


        $newpwd = Hash::make($request->password);
        $user->password = $newpwd;
        $user->update();


        // dd('ok ');

        return redirect()->route('users_profile', $user_id)->with('success', 'Password Updated Successfully');
    }
}
