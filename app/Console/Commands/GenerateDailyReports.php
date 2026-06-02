<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\ReportController;

class GenerateDailyReports extends Command
{
    protected $signature   = 'reports:generate-daily';
    protected $description = 'Generate daily focus reports for all users';

    public function handle()
    {
        $this->info('🧠 Generating daily reports...');

        $users      = User::all();
        $controller = new ReportController();
        $count      = 0;

        foreach ($users as $user) {
            $report = $controller->generateDailyReport($user->id);
            if ($report) {
                $this->info("✅ Report generated for: {$user->name}");
                $count++;
            }
        }

        $this->info("🎉 Done! Generated {$count} reports.");
        return Command::SUCCESS;
    }
}

