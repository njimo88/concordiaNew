<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\bills;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;
use App\Models\old_bills;


class OldBills extends Component
{
    public $sortBy = 'id';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortDirection == 'asc') {
            $this->sortDirection = 'desc';
        } else {
            $this->sortDirection = 'asc';
        }

        return $this->sortBy = $field;
    }

    public function render()
    {
         $bill = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->select('bills.*', 'bills.status as bill_status', 'users.name', 'users.lastname', 'bills_payment_method.payment_method', 'bills_payment_method.image', 'bills_status.status', 'bills_status.image_status','bills_status.row_color')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
        
        
        return view('livewire.old-bills')->with('bill', $bill)->with('user', auth()->user());
    }
}
