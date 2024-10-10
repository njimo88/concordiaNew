<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\bills;
use App\Models\LiaisonShopArticlesBill;
use Carbon\Carbon;

class DeleteOldUnpaidBills extends Command
{
    protected $signature = 'bills:delete-old-unpaid';

    protected $description = 'Supprimer les factures en attente de paiement CB depuis plus de 10 minutes';

    public function handle()
    {
        $cutOffTime = Carbon::now()->subMinutes(10);
        
        // Récupérer les factures à supprimer
        $billsToDelete = bills::where('status', 31)
            ->where('created_at', '<', $cutOffTime)
            ->get();
        
        // Supprimer les liaisons liées
        foreach ($billsToDelete as $bill) {
            LiaisonShopArticlesBill::where('bill_id', $bill->id)->delete();
        }
        
        // Supprimer les factures
        bills::where('status', 31)
            ->where('created_at', '<', $cutOffTime)
            ->delete();

        $this->info('Suppression des factures en attente de paiement CB depuis plus de 10 minutes, ainsi que des liaisons associées.');
    }
}
