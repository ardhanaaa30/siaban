<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use App\Models\PasswordResetRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $resetRequestsCount = PasswordResetRequest::where('status', 'pending')->count();
        return view('users.index', compact('users', 'resetRequestsCount'));
    }

    public function resetRequests()
    {
        $requests = PasswordResetRequest::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('users.reset-requests', compact('requests'));
    }

    public function approveReset(Request $request, PasswordResetRequest $resetRequest)
    {
        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $resetRequest->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            $resetRequest->update([
                'status' => 'completed',
                'admin_note' => 'Password telah direset oleh admin.',
            ]);

            return back()->with('success', 'Password user berhasil direset.');
        }

        return back()->with('error', 'User tidak ditemukan.');
    }

    public function rejectReset(Request $request, PasswordResetRequest $resetRequest)
    {
        $resetRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note ?? 'Permintaan ditolak oleh admin.',
        ]);

        return back()->with('success', 'Permintaan reset password ditolak.');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:Admin,Staff,Warga'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:Admin,Staff,Warga'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:Admin,Staff,Warga'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diperbarui.');
    }
}
