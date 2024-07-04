<?php

namespace App\Jobs;

use App\Models\Expenses;
use App\Notifications\ExpenseCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExpenseCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $expense;

    public function __construct(Expenses $expense)
    {
        $this->expense = $expense;
    }

    public function handle()
    {
        $this->expense->user->notify(new ExpenseCreatedNotification($this->expense));
    }
}
