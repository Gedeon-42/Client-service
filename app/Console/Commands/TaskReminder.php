<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskTracker;
use Illuminate\Console\Command;

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

        $tasks = Task::where('reminder_enabled', true)
            ->whereNotNull('due_date')
            ->whereNotNull('reminder_before')
            ->whereNull('reminder_sent_at')
            ->orderBy('due_date')
            ->limit(50)
            ->get();

        foreach ($tasks as $task) {

            $remindAt = null;

            if ($task->reminder_before === '1_hour') {

                $remindAt = $task->due_date->copy()->subHour();
            } 
            
            elseif ($task->reminder_before === '1_day') {
                $remindAt = $task->due_date->copy()->subDay();
            }

            

            if ($now->greaterThanOrEqualTo($remindAt)) {
                $task->user->notify(new TaskTracker($task));

                $task->update([
                    'reminder_sent_at' => now(),
                ]);
            }
        }
    }
}
