<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use App\Models\PasswordResetRequest;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Check if there is already a pending request for this email
        $existingRequest = PasswordResetRequest::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('status', 'Permintaan reset password Anda sedang diproses oleh Admin. Mohon tunggu.');
        }

        PasswordResetRequest::create([
            'email' => $request->email,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Permintaan reset password telah dikirim ke Admin. Silakan hubungi Admin untuk info lebih lanjut.');
    }
}
