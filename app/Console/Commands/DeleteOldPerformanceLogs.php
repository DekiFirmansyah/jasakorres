<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldPerformanceLogs extends Command
{
    protected $signature = 'performance:clean';

    protected $description = 'Delete performance logs older than 2 weeks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $twoWeeksAgo = now()->subWeeks(2);

        DB::table('performance_logs')
            ->where('created_at', '<', $twoWeeksAgo)
            ->delete();

        $this->info('Old performance logs deleted successfully.');
    }
}