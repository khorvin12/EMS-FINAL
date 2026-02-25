<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function updateDarkMode(Request $request)
    {
        $request->validate([
            'dark_mode' => 'required|boolean'
        ]);
        
        $user = Auth::user();
        $user->dark_mode = $request->dark_mode;
        $user->save();
        
        return back()->with('success', 'Dark mode setting updated!');
    }
}