<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfilePictureController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);

        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Not authenticated']);
        }

        $userId = Auth::id();
        
        // Get old profile picture
        $oldPicture = DB::table('users')->where('id', $userId)->value('profile_picture');
        
        // Delete old profile picture if exists
        if ($oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }
        
        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        
        // Update user record using DB
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'profile_picture' => $path,
                'updated_at' => now()
            ]);
        
        return back()->with('success', 'Profile picture updated!');
    }
}