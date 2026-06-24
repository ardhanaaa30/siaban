<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Permintaan reset password telah dikirim ke Admin. Silakan hubungi Admin untuk info lebih lanjut.');

        $this->assertDatabaseHas('password_reset_requests', [
            'email' => $user->email,
            'status' => 'pending',
        ]);
    }

    public function test_cannot_request_multiple_pending_resets()
    {
        $user = User::factory()->create();

        PasswordResetRequest::create([
            'email' => $user->email,
            'status' => 'pending',
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Permintaan reset password Anda sedang diproses oleh Admin. Mohon tunggu.');
        
        $this->assertEquals(1, PasswordResetRequest::where('email', $user->email)->count());
    }
}
