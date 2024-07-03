<?php

// namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Tests\TestCase;
// use App\Models\User;
// use App\Models\Expenses;

// class ExpensesControllerTest extends TestCase
// {
//     use RefreshDatabase;

//     public function test_it_creates_an_expense()
//     {
//         $user = User::factory()->create();
//         $this->actingAs($user);

//         $expenseDate = now()->format('d/m/Y');

//         $response = $this->postJson('/api/expenses', [
//             'descriptions' => 'Test Expense',
//             'price' => 100,
//             'expense_date' => $expenseDate,
//         ]);

//         $response->assertStatus(200)
//                  ->assertJson([
//                      'status' => 'success',
//                      'message' => 'Expense added successfully.',
//                  ]);
//     }

//     public function test_it_updates_an_expense()
//     {
//         $user = User::factory()->create();
//         $this->actingAs($user);

//         $expense = Expenses::factory()->create([
//             'user_id' => $user->id,
//         ]);

//         $expenseDate = now()->format('d/m/Y');

//         $response = $this->postJson("/api/expenses/{$expense->id}", [
//             'descriptions' => 'Updated Test Expense',
//             'price' => 150,
//             'expense_date' => $expenseDate,
//         ]);

//         $response->assertStatus(200)
//                  ->assertJson([
//                      'status' => 'success',
//                      'message' => 'Expense updated successfully.',
//                  ]);
//     }

//     public function test_it_deletes_an_expense()
//     {
//         $user = User::factory()->create();
//         $this->actingAs($user);

//         $expense = Expenses::factory()->create([
//             'user_id' => $user->id,
//         ]);

//         $response = $this->deleteJson("/api/expenses/{$expense->id}");

//         $response->assertStatus(200)
//                  ->assertJson([
//                      'status' => 'success',
//                      'message' => 'Expense deleted successfully.',
//                  ]);
//     }
// }
