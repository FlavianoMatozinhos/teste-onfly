<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expenses::all();
        
        if (is_null($expenses->first())) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No expense found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'expenses are retrieved successfully.',
            'data' => $expenses,
        ];

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'descriptions' => 'required|string|max:250',
            'price' => 'required|'
        ]);

        if($validate->fails()){  
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ], 403);    
        }

        $expense = Expenses::create($request->all());

        $response = [
            'status' => 'success',
            'message' => 'expense added successfully.',
            'data' => $expense,
        ];

        return response()->json($response, 200);
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
        $validate = Validator::make($request->all(), [
            'descriptions' => 'required',
            'price' => 'required',
        ]);

        if($validate->fails()){  
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
                'message' => 'expense not found!',
            ], 200);
        }

        $expense->update($request->all());
        
        $response = [
            'status' => 'success',
            'message' => 'expense updated successfully.',
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
                'message' => 'expense not found!',
            ], 200);
        }

        Expenses::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'expense deleted successfully.'
            ], 200);
    }

    public function search($name)
    {
        $expenses = Expenses::where('descriptions', 'like', '%'.$name.'%')
            ->latest()->get();

        if (is_null($expenses->first())) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No expense found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'expenses are retrieved successfully.',
            'data' => $expenses,
        ];

        return response()->json($response, 200);
    }
}
