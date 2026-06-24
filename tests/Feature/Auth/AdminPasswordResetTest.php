<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reset_requests_page()
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->get(route('users.reset-requests'));

        $response->assertStatus(200);
        $response->assertSee('Permintaan Reset Password');
    }

    public function test_non_admin_cannot_view_reset_requests_page()
    {
        $user = User::factory()->create([
            'role' => 'Warga',
        ]);

        $response = $this->actingAs($user)->get(route('users.reset-requests'));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_password_reset_request()
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $resetRequest = PasswordResetRequest::create([
            'email' => 'user@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('users.reset-requests'))
            ->post(route('users.reset-requests.approve', $resetRequest), [
                'password' => 'newpassword123',
            ]);

        $response->assertRedirect(route('users.reset-requests'));
        $response->assertSessionHas('success', 'Password user berhasil direset.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        $resetRequest->refresh();
        $this->assertEquals('completed', $resetRequest->status);
    }

    public function test_admin_can_reject_password_reset_request()
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $resetRequest = PasswordResetRequest::create([
            'email' => 'user@example.com',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('users.reset-requests'))
            ->post(route('users.reset-requests.reject', $resetRequest), [
                'admin_note' => 'Invalid request.',
            ]);

        $response->assertRedirect(route('users.reset-requests'));
        $response->assertSessionHas('success', 'Permintaan reset password ditolak.');

        $resetRequest->refresh();
        $this->assertEquals('rejected', $resetRequest->status);
        $this->assertEquals('Invalid request.', $resetRequest->admin_note);
    }
}
