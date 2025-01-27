<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SeatReservation extends Component
{

    public $seats = []; // Holds all seats from the database
    public $reservedSeats = []; // Holds seat numbers that are reserved
    public $Myresevation=[];
    public $spectacleid;
    public $message; 
    
 
    public function freeReservedAfter10Min() {

        $currentTimestamp = now()->timestamp - 1 * 60 ; // Current time in seconds 1 * 60 sec mean after 60 second will disapear 
        $currentTimestamp = date('Y-m-d H:i:s', $currentTimestamp);
        
        
        $expiredReservations = Reservation::where('status', 'pending')
        ->where('reservation_date', '<', $currentTimestamp) // Check if it's over 10 minutes
        ->get();

        foreach ($expiredReservations as $reservation) {
            //dd($reservation->seat());
            // Also update the seat's availability
            $reservation->seat->update([
                'available' => 1,
                'state' => 0
            ]);
            $reservation->delete();
        }
        $user_id= Auth::id();
        $this->Myresevation = Reservation::where('id_user', $user_id)->with('seat')->get();
        

    }
    public function mount($spectacleid)
    {
        $this->spectacleid = $spectacleid;

        // Load all seats on component mount
        
        $this->freeReservedAfter10Min();
        $this->seats = Seat::where('id_spectacle', $this->spectacleid)->get();
        
        $user_id= Auth::id();
        $this->Myresevation = Reservation::where('id_user', $user_id)->with('seat')->get();
        
    }

    public function reserveSeat($seatId)
    {
        //$updatedRows = Seat::where('available', false)->update(['seat_number' => '00']);
        //dd($updatedRows);

        // DB::table('seats')
        // ->where('available', false) // Filter rows where reservation is true
        // ->update([
        //     'seat_number' => DB::raw("CONCAT(seat_number, 'X')") // Concatenate 'X' to seat_number
        // ]);
        // dd("updatedRows");
        // Find the seat by ID
        $this->freeReservedAfter10Min();
        $seat = Seat::find($seatId);
        $user_id= Auth::id();
        $seat_befor=Seat::find($seatId-1);
        $seat_after=Seat::find($seatId+1);
        if ($seat_befor!=null && str_ends_with($seat_befor->seat_number, 'X')){$seat_befor=null;}
        if ($seat_after!=null && str_ends_with($seat_after->seat_number, 'X')){$seat_after=null;}
        if ($seat_befor!=null && str_starts_with($seat_befor->seat_number, $seat->seat_number[0]) && $seat_befor->available ){ 

            $this->message='you have to choice the seat next the first seats taken or first seat in the line ';
            return;
        }
            
       
        if ($seat && $seat->available) {
            // Reserve the seat by setting 'available' to false
            $seat->available = false; //reserve the seat 
            $seat->state=$user_id; //save user id 
            $seat->save();
            //create a reservation with the time to begin counting to 10min 
            $seat = Reservation::create([
                'id_user' => $user_id,
                'id_seat' => $seatId,
                'reservation_date' => now(),
                'status' => "pending"
            ]);

            //verify if there is any seats exceed the 10min time to turn it available state 
            $this->freeReservedAfter10Min();

            // Refresh the seats data
            $this->seats = Seat::where('id_spectacle', $this->spectacleid)->get();
            $this->message='you have take the seat';

        }else{
            //mean if state ==0 -> there is no one take this seat or if state== current user_id : the user can free the seat 
            if (!((int) $seat->state > 0) || $seat->state ==$user_id)
            {
                $seat->available = 1;
                $seat->state=0;
                $seat->save();

                Reservation::where('id_seat', $seat->id_seat)->delete();
               
                //verify if there is any seats exceed the 10min time to turn it available state
                $this->freeReservedAfter10Min();
                // Refresh the seats data
                $this->seats = Seat::where('id_spectacle', $this->spectacleid)->get();
                $this->message='you have free the seat  ';
            }
            
        }
        $this->freeReservedAfter10Min();
        //get the seats that are reserved by this user 
        $this->Myresevation = Reservation::where('id_user', $user_id)->with('seat')->get();
        
        
    }

    public function render()
    {
        //$this->seats = Seat::all();
    return view('livewire.seat-reservation', ['seats' => $this->seats]);

    }
    
}
