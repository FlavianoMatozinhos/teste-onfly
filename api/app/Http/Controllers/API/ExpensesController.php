<?php

namespace App\Http\Controllers\API;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseFormRequest;

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

    public function store(ExpenseFormRequest $request)
    {
        $this->authorize('create', Expenses::class);

        $expense = $this->createExpense($request);

        return new ExpenseResource($expense);
    }

    public function show(Expenses $expense)
    {
        $this->authorize('view', $expense);

        return new ExpenseResource($expense);
    }

    public function update(ExpenseFormRequest $request, Expenses $expense)
    {
        $this->authorize('update', $expense);

        $this->updateExpense($request, $expense);

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
        $expense->descriptions = $request->descriptions;
        $expense->price = $request->price;
        $expense->expense_date = $expenseDate->format('Y-m-d');
        $expense->save();
    }
}
