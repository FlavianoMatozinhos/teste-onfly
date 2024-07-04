<?php

namespace App\Http\Controllers\API;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;

class ExpensesController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Expenses::class);

        $user = auth()->user();
        $expenses = $user->expenses()->get();
        
        if ($expenses->isEmpty()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No expense found!',
            ], 404);
        }

        return ExpenseResource::collection($expenses);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Expenses::class);

        $request->validate([
            'descriptions' => 'required|string|max:250',
            'price' => 'required|numeric',
            'expense_date' => 'required|date_format:d/m/Y',
        ]);

        $user = auth()->user();
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);

        $expense = $user->expenses()->create([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);

        return new ExpenseResource($expense);
    }

    public function show(Expenses $expense)
    {
        $this->authorize('view', $expense);

        return new ExpenseResource($expense);
    }

    public function update(Request $request, Expenses $expense)
    {
        $this->authorize('update', $expense);

        $request->validate([
            'descriptions' => 'required|string|max:250',
            'price' => 'required|numeric',
            'expense_date' => 'required|date_format:d/m/Y',
        ]);

        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);
        $expense->descriptions = $request->descriptions;
        $expense->price = $request->price;
        $expense->expense_date = $expenseDate->format('Y-m-d');
        $expense->save();

        return new ExpenseResource($expense);
    }

    public function destroy(Expenses $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense deleted successfully.',
        ], 200);
    }
}
