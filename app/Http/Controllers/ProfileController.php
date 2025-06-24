<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserPasswordRequest; 

class ProfileController extends Controller
{
    public function changePasswordForm()
    {
        return view('profile.change_password');
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.change_password_form')
                         ->with('success', 'Sua senha foi alterada com sucesso!');
    }
}