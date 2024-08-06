<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        echo 'APP_ENV: '. env('APP_ENV'). PHP_EOL;
        echo 'DB_CONNECTION: '. env('DB_CONNECTION'). PHP_EOL;
        echo 'DB_HOST: '. env('DB_HOST'). PHP_EOL;
        echo 'DB_DATABASE: '. env('DB_DATABASE'). PHP_EOL;

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();
        $csrfCookie = $this->get('/sanctum/csrf-cookie');
        $headers = [
            'X-XSRF-TOKEN' => $csrfCookie->getCookie('XSRF-TOKEN', false)->getValue(),
        ];

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ], $headers);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();
        $csrfCookie = $this->get('/sanctum/csrf-cookie');

        $headers = [
            'X-XSRF-TOKEN' => $csrfCookie->getCookie('XSRF-TOKEN', false)->getValue(),
        ];

        $response = $this->actingAs($user)->post('/logout', [], $headers);

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
