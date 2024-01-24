<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\bills;
use Carbon\Carbon;

class DeleteOldUnpaidBills extends Command
{
    protected $signature = 'bills:delete-old-unpaid';

    protected $description = 'Supprimer les factures en attente de paiement CB depuis plus de 10 minutes';

    public function handle()
    {
        $cutOffTime = Carbon::now()->subMinutes(10);
        bills::where('status', 31)
            ->where('created_at', '<', $cutOffTime)
            ->update(['status' => 1]);

        $this->info('Suppression des factures en attente de paiement CB depuis plus de 10 minutes');
    }
}
