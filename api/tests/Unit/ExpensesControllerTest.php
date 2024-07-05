<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Expenses;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpensesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        Expenses::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')
            ->json('get', '/api/expenses')
        ;

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'descriptions',
                        'price',
                        'expense_date',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
        ;
    }

    public function testStoreExpense()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->json('post', '/api/expenses', [
                "descriptions"=> "adadasdaasda@fsdd",
                "price"=> "12345678",
                "expense_date"=> "03/12/2023"
            ])
        ;

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'descriptions',
                    'price',
                    'expense_date',
                    'user_id',
                    'created_at',
                    'updated_at',
                ]
            ])
        ;
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $expense = Expenses::factory()->create(['user_id' => $user->id]);

        $response = $this->json('DELETE', "/api/expenses/{$expense->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Despesa excluída com sucesso.',
            ])
        ;
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $expense = Expenses::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'descriptions' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'expense_date' => $this->faker->date('d/m/Y'),
        ];

        $response = $this->json('put', "/api/expenses/{$expense->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'descriptions',
                    'price',
                    'expense_date',
                    'user_id',
                    'created_at',
                    'updated_at',
                ]
            ])
        ;
    }
}
