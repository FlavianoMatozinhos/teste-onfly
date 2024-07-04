<?php

namespace App\Http\Controllers\API;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseFormRequest;
use Illuminate\Support\Facades\Log;

class ExpensesController extends Controller
{
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error fetching expenses: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch expenses!',
            ], 500);
        }
    }

    public function store(ExpenseFormRequest $request)
    {
        try {
            $this->authorize('create', Expenses::class);

            $expense = $this->createExpense($request);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Error creating expense: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create expense!',
            ], 500);
        }
    }

    public function show(Expenses $expense)
    {
        try {
            $this->authorize('view', $expense);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Error fetching expense: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch expense!',
            ], 500);
        }
    }

    public function update(ExpenseFormRequest $request, Expenses $expense)
    {
        try {
            $this->authorize('update', $expense);
            $this->updateExpense($request, $expense);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Error updating expense: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update expense!',
            ], 500);
        }
    }

    public function destroy(Expenses $expense)
    {
        try {
            $this->authorize('delete', $expense);

            $expense->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Expense deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting expense: '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete expense!',
            ], 500);
        }
    }

    private function createExpense(ExpenseFormRequest $request)
    {
        $user = auth()->user();
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);

        return $user->expenses()->create([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);
    }

    private function updateExpense(ExpenseFormRequest $request, Expenses $expense)
    {
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);
        $expense->update([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);
    }
}
