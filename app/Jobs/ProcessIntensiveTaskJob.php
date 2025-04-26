<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessIntensiveTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle(): void
    {
        $this->task->update(['status' => 'processing']);

        try {
            // Randomly fail (20% chance)
            if (random_int(1, 10) <= 2) {
                throw new \Exception("Random processing error occurred.");
            }

            $input = $this->task->input_data['text'];

            // Simulate computational delay
            sleep(20); // slight delay

            // Simulate intensive processing: custom "series sum"
            $limit = strlen($input) * 100; // e.g. 1000 if input is 10 chars

            $sum = 0;
            $fib1 = 0;
            $fib2 = 1;

            for ($i = 0; $i < $limit; $i++) {
                $sum += $fib1;

                // Generate next Fibonacci number
                $next = $fib1 + $fib2;
                $fib1 = $fib2;
                $fib2 = $next;
            }

            $processed = strtoupper(strrev($input));
            $result = "{$processed}_{$sum}";

            $this->task->update([
                'status' => 'completed',
                'result' => $result,
            ]);
        } catch (\Throwable $e) {
            $this->task->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}