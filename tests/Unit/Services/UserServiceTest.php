<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function testStoreUserWithValidData()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'document' => '9999999',
        ];

        $user = $this->userService->store($data);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['document'], $user->document);
    }
    public function testStoreUserWithInvalidDataMissingRequiredFields()
    {
        $data = [
            'name' => 'John Doe',
        ];
        $this->expectException(QueryException::class);
        $this->userService->store($data);
    }
    public function testStoreUserWithInvalidDataInvalidFieldTypes()
    {
        $data = [
            'name' => 123,
            'email' => 'invalid-email',
            'password' => 'password',
        ];
        $this->expectException(QueryException::class);
        $this->userService->store($data);
    }

    public function testListAllUsers()
    {
        // Create some users
        User::factory()->count(5)->create();
        
        // Test listing all users
        $users = $this->userService->list([]);
        $this->assertCount(5, $users);
    }
   
    public function testListUsersExceptAuthenticatedOne()
    {
        // Create some users
        User::factory()->count(5)->create();

        // Authenticate a user
        $authenticatedUser = User::factory()->create();
        Auth::login($authenticatedUser);

        // Test listing users except the authenticated one
        $users = $this->userService->list(['not_me' => true]);
  
        $this->assertCount(5, $users);
        $this->assertNotContains($authenticatedUser->id, $users->pluck('id'));
    }

    public function testListUsersWithMissingNotMeKey()
    {
        // Test listing users with missing 'not_me' key in input data
        $users = $this->userService->list([]);
        $this->assertCount(User::all()->count(), $users);
    }
}

