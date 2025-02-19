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
    public $message1;
    public $message2;

    public function doubleRefrech() {
        $this->freeReservedAfter10Min();
        $this->freeReservedAfter10Min();
         }
    
    public function freeReservedAfter10Min() {

        $currentTimestamp = now()->timestamp - 2 * 60 ; // Current time in seconds 2 * 60 sec mean after 60 second will disapear 
        $currentTimestamp = date('Y-m-d H:i:s', $currentTimestamp);
        $this->Myresevation=null;
        
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
        $this->message1='La page a été actualisée avec succès.';
        $this->message2='';     

    }
    public function mount($spectacleid)
    {
        $this->spectacleid = $spectacleid;

        // Load all seats on component mount
        
        $this->freeReservedAfter10Min();
        $this->message1='Bienvenue ! Vous pouvez maintenant ';
        $this->message2='choisir vos sièges.';
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

            $this->message1='Vous devez choisir un siège à côté des ';
            $this->message2='sièges déjà pris ou au début des sous-lignes.';

            return;
        }
            
       
        if ($seat && $seat->available) {
            // Reserve the seat by setting 'available' to false
            $this->freeReservedAfter10Min();
            $seat->available = false; //reserve the seat 
            $seat->state=$user_id; //save user id 
            $seat->save();
            $this->message2=$seat->seat_number;
            //create a reservation with the time to begin counting to 10min 
            $seat = Reservation::create([
                'id_user' => $user_id,
                'id_seat' => $seatId,
                'reservation_date' => now(),
                'status' => "pending"
            ]);
           

            //verify if there is any seats exceed the 10min time to turn it available state 
           

            // Refresh the seats data
            $this->seats = Seat::where('id_spectacle', $this->spectacleid)->get();
            $this->message1='Vous avez pris le siège : '.$this->message2.' ';
            $this->message2='avec succès.';
            

        }else{
            //mean if state ==0 -> there is no one take this seat or if state== current user_id : the user can free the seat 
            if (!((int) $seat->state > 0) || $seat->state ==$user_id)
            {
                $seat->available = 1;
                $seat->state=0;
                $seat->save();

                Reservation::where('id_seat', $seat->id_seat)->delete();
               
                //verify if there is any seats exceed the 10min time to turn it available state
                
                // Refresh the seats data
                $this->seats = Seat::where('id_spectacle', $this->spectacleid)->get();
                $this->freeReservedAfter10Min();
                
                $this->message1='Vous avez libéré le siège : '.$seat->seat_number.' ';
                $this->message2="avec succès. ";
            }
            
        }
        
        //get the seats that are reserved by this user 
        $this->Myresevation = Reservation::where('id_user', $user_id)->with('seat')->get();
        
        
        
    }

    public function render()
    {
        
    return view('livewire.seat-reservation', ['seats' => $this->seats]);

    }
    
}
