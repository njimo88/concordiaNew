<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteOldUsersWithoutBills extends Command
{
    protected $signature = 'users:delete-old-no-bills';
    protected $description = 'Delete users created more than a month ago without any bills';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $oneMonthAgo = Carbon::now()->subMonth();
        $users = User::where('created_at', '<=', $oneMonthAgo)
                     ->whereDoesntHave('bills')
                     ->whereDoesntHave('old_bills')
                     ->get();

        foreach ($users as $user) {
            $user->delete();
        }
        
        $this->info('Old users without bills deleted successfully.');
    }
}
