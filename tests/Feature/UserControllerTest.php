<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulIndexUsersWithMe()
    {
        $user = User::factory()->create();
        User::factory(3)->create();

        $response = $this->actingAs($user)->getJson('/api/users?not_me=0');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertCount(4, $data);
    }

    public function testSuccessfulIndexUsersWithoutMe()
    {
        $user = User::factory()->create();
        User::factory(3)->create();

        $response = $this->actingAs($user)->getJson('/api/users?not_me=1');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertCount(3, $data);
    }

    public function testStoreUserWithValidRequestData()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'John Doe',
            'email' => Str::random(10) . '@example.com',
            'password' => 'password',
            "password_confirmation" => "password",
            'document' => Str::random(10),
        ];

        $response = $this->actingAs($user)->postJson('/api/user', $data);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'User created successfully']);

        $user = User::where('email', $data['email'])->firstOrFail();
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['document'], $user->document);
        $this->assertEquals(0, $user->balance);
    }

    public function testStoreUserWithInValidRequestData()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'John Doe',
            'password' => 'password',
            'document' => Str::random(10),
        ];

        $response = $this->actingAs($user)->postJson('/api/user', $data);
        $response->assertStatus(422);
    }

    public function testStoreUserWithTwoUsers()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'John Doe',
            'email' => Str::random(10) . '@example.com',
            'password' => 'password',
            "password_confirmation" => "password",
            'document' => Str::random(10),
        ];

        $response = $this->actingAs($user)->postJson('/api/user', $data);
        $response->assertStatus(201);

        $response = $this->actingAs($user)->postJson('/api/user', $data);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'The email has already been taken. (and 1 more error)']);
    }

    public function testUserIndexReturnsUsersWithResourceFormat()
    {
        $authUser = User::factory()->create();
        User::factory(3)->create();

        $response = $this->actingAs($authUser)->getJson('/api/users?not_me=0');

        $response->assertStatus(200);
        $response->assertJsonCount(4);

        $response->assertJsonFragment([
            'id' => $authUser->id,
            'name' => $authUser->name,
            'email' => $authUser->email,
            'document' => $authUser->document,
            'created_at' => Carbon::parse($authUser->created_at)->format('Y-m-d H:i:s'),
        ]);
    }

    public function testUserIndexExcludesAuthenticatedUserWhenNotMeIsTrue()
    {
        $authUser = User::factory()->create();
        User::factory(5)->create();

        $response = $this->actingAs($authUser)->getJson('/api/users?not_me=1');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonMissing(['email' => $authUser->email]);
    }

    public function testUserIndexNotAuthenticatedUser()
    {
        $response = $this->getJson('/api/users?not_me=1');

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testUserShowReturnsUserData()
    {
        $authUser = User::factory()->create();

        $response = $this->actingAs($authUser)->getJson("/api/user/{$authUser->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $authUser->id,
            'name' => $authUser->name,
            'email' => $authUser->email,
        ]);
    }

    public function testUserShowReturnsForbiddenWhenAccessingAnotherUser()
    {
        $authUser = User::factory()->create();
        $anotherUser = User::factory()->create();
    
        $response = $this->actingAs($authUser)->getJson("/api/user/{$anotherUser->id}");
    
        $response->assertStatus(403);
        $response->assertSeeText('Forbidden');
    }
    


    public function testGetBalanceReturnsUserBalance()
    {
        $authUser = User::factory()->create();
        $authUser->balance = 100;
        $authUser->save();

        $response = $this->actingAs($authUser)->getJson('/api/user/balance');

        $response->assertStatus(200);
        $response->assertJson(['balance' => 100]);
    }

    public function testGetBalanceNotAuthenticatedUser()
    {
        $response = $this->getJson('/api/user/balance');

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
