<?php

namespace Database\Factories;

use App\Models\Expenses;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpensesFactory extends Factory
{
    protected $model = Expenses::class;

    public function definition()
    {
        return [
            'descriptions' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'expense_date' => $this->faker->date(),
        ];
    }
}
