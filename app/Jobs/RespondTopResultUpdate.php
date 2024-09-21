<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RespondTopResultUpdate implements ShouldQueue
{
    use Queueable;

    private $respondId;
    /**
     * Create a new job instance.
     */
    public function __construct($_respondId)
    {
        $this->respondId=$_respondId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
    }
}
