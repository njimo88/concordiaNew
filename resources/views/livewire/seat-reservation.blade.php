<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  
      <div class=" seat-reservation-container ">
          <br>
          
  
  
          <!-- Button to Open the Modal -->
  <button   onclick="timeRefreching()" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    <i class="fas fa-calendar-check"></i> Mes Reservations 
    </button>
  
    <button wire:click="freeReservedAfter10Min()" class="btn btn-secondary" >
      <i class="fas fa-sync-alt"></i> Refresh
    </button>
    <!-- The Modal -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
    
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title text-secondary">Mes Reservations</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
    
          <!-- Modal body -->
          <div class="modal-body">
              
              <h4>Mes Reservations
                  
                  @foreach ($Myresevation as $item)
                  
                  <div id="here" class="seat-timer" 
                      data-reservation-time="{{$item->reservation_date}}" 
                      data-seat-number="{{ $item->id_seat }}">
                      <span class="text-secondary">Siege: {{ $item->seat->seat_number }} </span>
                      <span class="text-danger timer"></span>
                  </div>
                      
                  @endforeach 
  
                  
              </h4>
      
              
          </div>
    
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          </div>
    
        </div>
      </div>
    </div>

  @if (@isset($message))
    
        <div class="alert alert-success alert-dismissible">
             {{$message}}
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
    
    
  @endif
    
  
          <!-- Screen Label -->
          <h2 class="screen-label">Gradin </h2>
          
          @php
          use Illuminate\Support\Str;
          
          @endphp
  
          <!-- Seating Area -->
          <div class="seating-area">
              @foreach (['A','B','C','D','E','F','G','H','I','J','K','L','M'] as $row)
              <div class="seat-row">
                  @foreach ($seats as $seat)
                      
                      @if (Str::startsWith($seat->seat_number, $row))
                          @php
                              $color="reserved";
                              if($seat->state==Auth::id())
                              {
                                  $color="reservedpending";
                              }else {
                                  if (( (int)$seat->state ) > 0){
                                  $color="reservedpendingUsers";
                                  }
                              }
                              
                          @endphp
                          <button 
                              onclick="confirmReservation({{ $seat->id_seat }}, this)"
                              class="seat {{ !$seat->available ? $color : '' }} {{ str_ends_with($seat->seat_number, 'X') ? 'reservedEmpty' : '' }}" 
                              wire:click="reserveSeat({{ $seat->id_seat }})"
                              {{ str_ends_with($seat->seat_number, 'X') ? 'desible' : '' }}
                               >
                              {{ str_ends_with($seat->seat_number, 'X') ? ' ' : $seat->seat_number }}
                          </button>
                      @endif
                      
                  @endforeach
                  
              </div>
              @endforeach
          </div>
  
          <p>Sol Gradin </p>
          <div class="seating-area">
              @foreach (['N','O'] as $row)
              <div class="seat-row">
                  @foreach ($seats as $seat)
  
                      @if (Str::startsWith($seat->seat_number, $row))
                          @php
                          $color="reserved";
                          if($seat->state==Auth::id())
                          {
                              $color="reservedpending";
                          }else {
                              if (( (int)$seat->state ) > 0){
                              $color="reservedpendingUsers";
                              }
                          }
                          @endphp
  
                          <button 
                          onclick="confirmReservation({{ $seat->id_seat }}, this)"
                          class="seat {{ !$seat->available ? $color : '' }} {{ str_ends_with($seat->seat_number, 'X') ? 'reservedEmpty' : '' }}" 
                          wire:click="reserveSeat({{ $seat->id_seat }})"
                          {{ str_ends_with($seat->seat_number, 'X') ? 'desible' : '' }}
                          >
                          {{ str_ends_with($seat->seat_number, 'X') ? ' ' : $seat->seat_number }}
                          </button>
                      @endif
                  @endforeach
              </div>
              @endforeach
          </div>
  
          <p>Parterre </p>
          <div class="seating-area">
              @foreach (['P','Q','R','S','T','U','V','W','X','Y','Z'] as $row)
              <div class="seat-row">
                  @foreach ($seats as $seat)
                      @if (Str::startsWith($seat->seat_number, $row))
                          @php
                          $color="reserved";
                          if($seat->state==Auth::id())
                          {
                              $color="reservedpending";
                          }else {
                              if (( (int)$seat->state ) > 0){
                              $color="reservedpendingUsers";
                              }
                          }
                          @endphp
                         <button 
                          onclick="confirmReservation({{ $seat->id_seat }}, this)"
                          class="seat {{ !$seat->available ? $color : '' }} {{ str_ends_with($seat->seat_number, 'X') ? 'reservedEmpty' : '' }}" 
                          wire:click="reserveSeat({{ $seat->id_seat }})"
                          {{ str_ends_with($seat->seat_number, 'X') ? 'desible' : '' }}
                              >
                          {{ str_ends_with($seat->seat_number, 'X') ? ' ' : $seat->seat_number }}
                          </button>
                      @endif
                  @endforeach
              </div>
              @endforeach
          </div>
          
          <h1 class="text-body">.</h1>
          <h1 class="text-body">.</h1>
          <h1 class="text-body">.</h1>
          <h1 class="text-body">.</h1>
          <h1 class="text-body">.</h1>
          <h1 class="text-body">.</h1>
          
  
      </div>
      
  <br>
  
  
  <script> 



  //ajust a timer 
  document.addEventListener('DOMContentLoaded', timeRefreching() );
  
  function timeRefreching() {
      const seatTimers = document.querySelectorAll('.seat-timer');
      const countdownDuration = 10 * 60 * 1000; // 10 minutes in milliseconds
  
      seatTimers.forEach(timerDiv => {
          const reservationTime = new Date(timerDiv.getAttribute('data-reservation-time'));
          const seatNumber = timerDiv.getAttribute('data-seat-number');
          const timerSpan = timerDiv.querySelector('.timer');
  
          function updateTimer() {
              const now = new Date();
              const elapsedTime = now - reservationTime; // Time elapsed since reservation
              const remainingTime = countdownDuration - elapsedTime;
  
              if (remainingTime <= 0) {
                  timerSpan.textContent = "Time expired!";
                  return;
              }
  
              const minutes = Math.floor(remainingTime / (1000 * 60));
              const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
              timerSpan.textContent = `Rest : ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
          }
  
          // Adjust for timezone difference (if needed)
          const timezoneOffset = new Date().getTimezoneOffset() * 60 * 1000; // Local offset in milliseconds
          reservationTime.setTime(reservationTime.getTime() - timezoneOffset);
  
          // Start the countdown
          updateTimer();
          setInterval(updateTimer, 1000);
      });
  }
          
  
      function confirmReservation(seatId, button) {
          
          // Show confirmation dialog
          const buttonText = button.textContent || button.innerText;
          //if the button(seat) is reserved  
          // or if the other user book the seat , the current user cant reserve the seat for 10min 
          if (button.classList.contains('reserved') || button.classList.contains('reservedpendingUsers')  ) { 
              event.preventDefault(); //block the event 
              event.stopImmediatePropagation(); //block the event 
              
              }else{
              // Check if character is '' mean buttons that's not a seat because i do show the seats as 
              // matrice like 30x40 and desible the seats that are not in the real Stage
              if (button.innerText =='') {
                  event.preventDefault();//block the event 
                  event.stopImmediatePropagation();//block the event 
  
              } else {// if the seat is available it gonna ask the user if he want to reserve it 
                  
                  if(button.classList.contains('reservedpending')){ //if seat is reserved by the current user 
                      const confirmAction = confirm("Voulez-vous vraiment laisser ce siège?");
                      
                      if (!confirmAction) {
                      //block the event if the user click No 
                      event.preventDefault();
                      event.stopImmediatePropagation();
                      
                      }
                  }else{
                      //if seat are available 
                      const confirmAction = confirm("Voulez-vous vraiment réserver ce siège?");
                      
                      if (!confirmAction) {
                      //block the event if the user click No 
                      event.preventDefault();
                      event.stopImmediatePropagation();
                     
                      }
                  }
  
              
              }
  
          }
  
  
      }
  </script>
  
  <style>



  /* card */
  /* remove the underline from links card */
  .card-link {
    text-decoration: none; /* Remove underline from the link */
    color: inherit; /* Inherit text color */
  }
  
  .card-link:hover .card {
    transform: scale(1.02); /* Optional: add hover effect for better interactivity */
    transition: transform 0.3s;
  }
  
  .card {
    margin: 10px; /* Add spacing between cards */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: add a shadow for better appearance */
    transition: transform 0.3s;
  }
  
  .card:hover {
    transform: scale(1.05); /* Hover effect */
  }
  
  .row {
    gap: 20px; /* Add gap between rows and columns */
  }
  /* fin card */
  
  
  /* General container */
  .seat-reservation-container {
      
      text-align: center;
      background-color: #000;
      padding-left: 15px;
      padding-right: 15px;
      color: #fff;
      padding: auto;
      overflow-x: auto; /* Enable horizontal scrolling */
      overflow-y: hidden; /* Enable horizontal scrolling */
      white-space: nowrap; /* Prevent wrapping of rows */
  }
  .seatEmpty{
      background-color: #000000;
      color: #000000;
      width: 25px;
      height: 25px;
      border: none;
      border-radius: 5px;
      padding: 0px;
      text-align: center;
      line-height: 35px;
      cursor: pointer;
      font-size: 12px;
      font-weight: bold;
      transition: background-color 0.3s ease;
      margin-right: 4px;
  }
  /* Screen label */
  .screen-label {
      font-size: 24px;
      margin-bottom: 20px;
      text-transform: uppercase;
      font-weight: bold;
  }
  
  /* Seating area */
  .seating-area {
      display: inline-block; /* Allows horizontal scrolling */
      margin: 0 auto;
  }
  
  /* Seat rows */
  .seat-row {
      display: flex; /* Make rows align horizontally */
      margin-bottom: 8px;
  }
  
  /* Individual seats */
  .seat {
      width: 25px;
      height: 25px;
      background-color: #f13b78;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 0px;
      text-align: center;
      line-height: 35px;
      cursor: pointer;
      font-size: 12px;
      font-weight: bold;
      transition: background-color 0.3s ease;
      margin-right: 4px;
  }
  
  .seat:hover {
      background-color: #d81b60;
  }
  
  /* Reserved seats and payed  */
  .seat.reserved { 
      background-color: #383838;
      color: #6d6d6d;
      cursor: not-allowed; 
  }
  .seat.reservedpendingUsers{ /* Reserved seats from someone and not payed  */
      background-color: #BDBDBD;
      color: #424242;
      cursor: not-allowed; 
  }
  
  .seat.reservedpending {/* Reserved seats from current user and not payed  */
      background-color: #FFA726;
      color: #FFFFFF;
      /* cursor: not-allowed; */
  }
  .seat.reservedEmpty {
      background-color: #000000;
      color: #000000;
      cursor: default;
  }
  
  
  /* Responsive tweaks for horizontal scrolling */
  @media (max-width: 768px) {
      .seat-row {
          justify-content: flex-start; /* Keep seats aligned to the left */
      }
  
      .seat {
          width: 25px;
          height: 25px;
          font-size: 10px;
      }
  
      .seat-row {
          margin-bottom: 6px;
      }
  }
  </style>
</div>
  
