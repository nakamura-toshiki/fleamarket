<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**会員登録機能 */
    /**名前が未入力 */
    public function test_registration_requires_name()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect(route('register'));
    }

    /**メールアドレスが未入力 */
    public function test_registration_requires_email()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect(route('register'));
    }

    /**パスワードが未入力 */
    public function test_registration_requires_password()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect(route('register'));
    }

    /**パスワードが7文字以下 */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect(route('register'));
    }

    /**パスワードが確認用パスワードと一致しない*/
    public function test_password_confirmation_must_match()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
        $response->assertStatus(302);
        $response->assertRedirect(route('register'));
    }

    /** 全ての項目が正しく入力されている場合 */
    public function test_successful_registration()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }



    /**ログイン機能 */
    /**メールアドレスが未入力 */
    public function test_login_requires_email()
    {
        $response = $this->from('/login')->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**パスワードが未入力 */
    public function test_login_requires_password()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**入力情報が間違っている */
    public function test_login_invalid_credentials_error()
    {
        $invalidCredentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'invalid-password',
        ];

        $response = $this->from('/login')->post('/login', $invalidCredentials);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => trans('auth.failed'),
        ]);
    }

    /**全ての項目が正しく入力されている場合 */
    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/email/verify');
    }



    /**ログアウト機能 */
    public function test_logout()
    {
        $user = User::factory()->create([
            'email' => 'example@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/email/verify');
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post('/logout');

        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }
}
