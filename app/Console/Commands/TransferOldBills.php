<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\bills;
use App\Models\old_bills;

class TransferOldBills extends Command
{
    protected $signature = 'bills:transfer';
    protected $description = 'Transfer bills older than 2 years to old_bills table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get bills older than 2 years
        $oldBills = bills::whereDate('date_bill', '<', Carbon::now()->subYears(2))->get();

        // Loop through each bill and transfer
        foreach ($oldBills as $bill) {
            // Include the 'id' in the data to be transferred
            $data = $bill->toArray();
            $data['id'] = $bill->id;

            // Insert into old_bills
            old_bills::create($data);

            // Delete from the original bills table
            $bill->delete();
        }

        $this->info("Bills transferred successfully!");
    }
}
