<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSuccessfullyCreateUser()
    {
        $user = User::factory()->make();
        $user = $user->toArray();
        $user['password'] = '1234';

        $response = $this->postJson(
            '/api/user',
            $user,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['id' => $response->json('data.user.id')]);
    }

    public function testShouldSuccessfullyUpdateUser()
    {
        $user = User::factory()->create();
        $user = $user->toArray();
        $user['name'] = 'teste update';
        $user['password'] = '1234';

        $response = $this->putJson(
            '/api/user/' . $user['id'],
            $user,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'id' => $user['id'],
            'name' => $user['name'],
        ]);
    }

    public function testShouldSuccessfullyRestoreUser()
    {
        $user = User::factory()->trashed()->create();
        $this->assertSoftDeleted($user);

        $response = $this->putJson(
            '/api/user/' . $user->id . '/restore',
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertNotSoftDeleted($user);
    }

    public function testShouldSuccessfullyShowAllUsers()
    {
        $count = 10;
        User::truncate();
        User::factory($count)->create();

        $response = $this->getJson(
            '/api/user',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertJsonCount($count + 1, 'data.users');
    }

    public function testShouldSuccessfullyShowUserById()
    {
        $user = User::factory()->create();

        $response = $this->getJson(
            '/api/user/' . $user->id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertJsonFragment($user->toArray());
    }

    public function testShouldSuccessfullyDeleteUser()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(
            '/api/user/' . $user->id,
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertSoftDeleted($user);
    }

    public function testShouldFailureRestoreUser()
    {
        User::truncate();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getTokenLogin(),
        ];

        $this->put("/api/user/" . rand(5, 10) . "/restore", $headers)->assertUnprocessable();
    }

    protected function setup(): void
    {
        parent::setUp();
    }
}
