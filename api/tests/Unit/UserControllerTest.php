<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->json('get', '/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ]
            ])
        ;
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->json('post', '/api/users', [
               'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
            ])
        ;

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
        ;
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $updatedData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ];

        $response = $this->json('post', "/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated User',
                'email' => 'updated@example.com',
            ])
        ;
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
    
        $response = $this->json('DELETE', "/api/users/{$user->id}");
    
        $response->assertStatus(204);
    }
}
