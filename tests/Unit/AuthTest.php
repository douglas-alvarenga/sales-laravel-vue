<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSuccessfullyUserLoginWithUsername()
    {
        User::factory()->create(['username' => 'testelogin']);

        $response = $this->postJson(
            '/api/login',
            ['login' => 'testelogin', 'password' => '1234'],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
    }

    public function testShouldSuccessfullyUserLoginWithEmail()
    {
        User::factory()->create(['username' => 'login@teste.com']);

        $response = $this->postJson(
            '/api/login',
            ['login' => 'login@teste.com', 'password' => '1234'],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
    }

    public function testShouldNotSuccessfullyUserLoginWithWrongEmail()
    {
        $response = $this->postJson(
            '/api/login',
            ['login' => 'falha@teste.com', 'password' => '1234'],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );

        $response->assertUnauthorized();
    }

    public function testShouldNotSuccessfullyUserLoginWithWrongPassword()
    {
        $user = User::factory()->create(['password' => 'testelogin']);
        $response = $this->postJson(
            '/api/login',
            ['login' => $user->email, 'password' => '1234'],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );

        $response->assertUnauthorized();
    }

    public function testShouldSuccessfullyUserLogout()
    {
        $user = User::factory()->create(['password' => 'testelogout']);
        $token = auth()->login($user);
        $response = $this->getJson(
            '/api/logout',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
    }

    public function testShouldNotSuccessfullyUserLogoutWithoutToken()
    {
        $response = $this->getJson(
            '/api/logout',
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
