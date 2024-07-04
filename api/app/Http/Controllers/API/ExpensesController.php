<?php

namespace App\Http\Controllers\API;

use App\Jobs\SendExpenseCreatedEmail;
use App\Models\Expenses;
use App\Notifications\ExpenseCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ExpensesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $expenses = Expenses::where('user_id', $user->id)->get();
        
        if (is_null($expenses->first())) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No expense found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Expenses are retrieved successfully.',
            'data' => $expenses,
        ];

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $validate = $this->validateExpenseData($request);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ], 403);
        }

        $user = Auth::user();
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);

        $expense = Expenses::create([
            'descriptions' => $request->descriptions,
            'user_id' => $user->id,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);

        SendExpenseCreatedEmail::dispatch($expense);

        $response = [
            'status' => 'success',
            'message' => 'Expense added successfully.',
            'data' => $expense,
        ];

        return response()->json($response, 201);
    }

    private function validateExpenseData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descriptions' => 'required|string|max:250',
            'price' => 'required|numeric',
            'expense_date' => 'required|date_format:d/m/Y',
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->validateExpenseDateIsNotFuture($validator, $request->expense_date);
        });

        return $validator;
    }

    private function validateExpenseDateIsNotFuture($validator, $expenseDate)
    {
        $parsedDate = Carbon::createFromFormat('d/m/Y', $expenseDate);

        if ($parsedDate->isFuture()) {
            $validator->errors()->add('expense_date', 'The expense date cannot be in the future.');
        }
    }

    public function show($id)
    {
        $expense = Expenses::find($id);
  
        if (is_null($expense)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'expense not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'expense retrieved successfully.',
            'data' => $expense,
        ];
        
        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $validate = $this->validateExpenseData($request);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ], 403);
        }

        $expense = Expenses::find($id);

        if (is_null($expense)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Expense not found!',
            ], 404);
        }

        $user = Auth::user();
        if ($expense->user_id !== $user->id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Expense not found. Unable to update.',
            ], 403);
        }

        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);
        $expense->fill([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);
        $expense->save();

        $response = [
            'status' => 'success',
            'message' => 'Expense updated successfully.',
            'data' => $expense,
        ];

        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expense = Expenses::find($id);
    
        if (is_null($expense)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Expense not found.',
            ], 404);
        }
    
        $user = Auth::user();
        if ($expense->user_id !== $user->id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Expense not found. Unable to deleted.',
            ], 403);
        }
    
        Expenses::destroy($id);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Expense deleted successfully.',
        ], 200);
    }
}
