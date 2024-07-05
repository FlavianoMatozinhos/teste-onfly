<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Expenses;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) {
            Expenses::factory()->create([
                'user_id' => $user->id,
                'descriptions' => 'Expense description',
                'price' => rand(10, 1000),
                'expense_date' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        });
    }
}
