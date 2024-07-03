<?php

// namespace Tests\Feature;

// use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Laravel\Sanctum\Sanctum;
// use Tests\TestCase;

// class UserControllerTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     public function test_create_user_with_valid_data()
//     {
//         $this->withoutExceptionHandling();

//         Sanctum::actingAs(User::factory()->create());

//         $userData = [
//             'name' => $this->faker->name,
//             'email' => $this->faker->unique()->safeEmail,
//             'password' => '',
//         ];

//         $response = $this->postJson('api/users', $userData);

//         $response->assertStatus(201)
//                  ->assertJsonStructure(['id', 'name', 'email']);

//         $this->assertDatabaseHas('users', [
//             'email' => $userData['email'],
//         ]);
//     }
// }