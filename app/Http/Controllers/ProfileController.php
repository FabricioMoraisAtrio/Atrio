<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'avatar'                   => 'nullable|image|max:2048',
            'current_password'         => 'required_with:password',
            'password'                 => 'nullable|min:6|confirmed',
            'notify_document_pending'  => 'boolean',
            'notify_plan_expiring'     => 'boolean',
        ]);

        // Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Senha
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
            }
            $user->password = Hash::make($request->password);
        }

        // Notificações
        $user->notify_document_pending = $request->boolean('notify_document_pending');
        $user->notify_plan_expiring    = $request->boolean('notify_plan_expiring');

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }
}