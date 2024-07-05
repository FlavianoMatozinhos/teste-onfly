<?php

namespace App\Http\Controllers\API;

use App\Models\Expenses;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseFormRequest;
use App\Jobs\SendExpenseCreatedEmail;
use Illuminate\Foundation\Mix;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ExpensesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/expenses",
     *     summary="List all expenses",
     *     tags={"Expenses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of expenses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExpenseFormRequest")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No expenses found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="No expense found!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch expenses",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to fetch expenses!")
     *         ),
     *     ),
     * )
     */
    public function index(): mixed
    {
        try {
            $this->authorize('viewAny', Expenses::class);

            $user = auth()->user();
            $expenses = $user->expenses()->get();

            if ($expenses->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Nenhuma despesa encontrada!',
                ], 404);
            }

            return ExpenseResource::collection($expenses);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar despesas: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao buscar despesas!',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/expenses",
     *     summary="Create a new expense",
     *     tags={"Expenses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseFormRequest")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to create expense!")
     *         )
     *     )
     * )
    */
    public function store(ExpenseFormRequest $request): mixed
    {
        try {
            $this->authorize('create', Expenses::class);

            $expense = $this->createExpense($request);

            SendExpenseCreatedEmail::dispatch($expense);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Erro ao criar despesa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao criar despesa!',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/expenses/{expense}",
     *     summary="Display the specified expense",
     *     tags={"Expenses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense details",
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseFormRequest"),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized access to expense!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="Expense not found!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to fetch expense!")
     *         ),
     *     ),
     * )
    */
    public function show(Expenses $expense): mixed
    {
        try {
            $this->authorize('view', $expense);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar despesa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao buscar despesa!',
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/expenses/{expense}",
     *     summary="Update the specified expense",
     *     tags={"Expenses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExpenseFormRequest")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized update of expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized update of expense!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="Expense not found!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update expense!")
     *         ),
     *     ),
     * )
    */
    public function update(ExpenseFormRequest $request, Expenses $expense): mixed
    {
        try {
            $this->authorize('update', $expense);

            $this->updateExpense($request, $expense);

            return new ExpenseResource($expense);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar despesa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao atualizar despesa!',
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/expenses/{expense}",
     *     summary="Delete the specified expense",
     *     tags={"Expenses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Expense deleted successfully.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized deletion of expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized deletion of expense!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="message", type="string", example="Expense not found!")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to delete expense",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to delete expense!")
     *         ),
     *     ),
     * )
    */
    public function destroy(Expenses $expense): JsonResponse
    {
        try {
            $this->authorize('delete', $expense);

            $expense->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Despesa excluída com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir despesa: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Falha ao excluir despesa!',
            ], 500);
        }
    }

    private function createExpense(ExpenseFormRequest $request): ?Expenses
    {
        $user = auth()->user();
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);

        return $user->expenses()->create([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);
    }

    private function updateExpense(ExpenseFormRequest $request, Expenses $expense): void
    {
        $expenseDate = Carbon::createFromFormat('d/m/Y', $request->expense_date);

        $expense->update([
            'descriptions' => $request->descriptions,
            'price' => $request->price,
            'expense_date' => $expenseDate->format('Y-m-d'),
        ]);
    }
}
