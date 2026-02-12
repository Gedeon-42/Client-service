<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Jobs\SendReminder;
use Illuminate\Console\Command;
use App\Notifications\TaskTracker;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $now = now();
        $tasks = Task::whereNotNull('due_date')
            ->where('is_completed', false)
            ->whereNull('reminder_sent_at')
            ->whereHas('user', function ($query) {
                $query->where('task_notifications_enabled', true);
            })
            ->orderBy('due_date')
            ->limit(50)
            ->get();
        foreach ($tasks as $task) {
            $remindAt = null;
            if ($task->user->reminder_before === '1_hour') {
                $remindAt = $task->due_date->copy()->subHour();
            } elseif ($task->user->reminder_before === '1_day') {
                $remindAt = $task->due_date->copy()->subDay();
            }
            if ($now->greaterThanOrEqualTo($remindAt)) {
                // $task->user->notify(new TaskDueReminder($task));
                SendReminder::dispatch($task);
                $task->update([
                    'reminder_sent_at' => now(),
                ]);
            }
        }
    }
}
