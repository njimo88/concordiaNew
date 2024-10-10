<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Basket;
use Carbon\Carbon;

class DeleteDailyBaskets extends Command
{
    protected $signature = 'baskets:delete-daily';
    protected $description = 'Delete baskets daily at midnight';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Basket::where('created_at', '<', Carbon::today())->delete();
        $this->info('Daily baskets deleted successfully.');
    }
}

