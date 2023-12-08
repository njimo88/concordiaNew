@extends('layouts.app')

@section('content')
<section class="event-section">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 d-flex flex-column flex-lg-row align-items-center">
                <!-- Les détails de l'événement seront à gauche -->
                <div class="event-details flex-fill">
                    <h1 class="event-title">{{ $posts->title }}</h1>
                    <p class="event-info">
                        <span class="event-date">vendredi, 08/12/2023 | 20:00</span>
                        <span class="event-location">ECKBOLSHEIM | Zénith de Strasbourg Europe</span>
                    </p>
                </div>
                <!-- L'image de l'événement sera à droite -->
                <div class="event-image flex-fill">
                    <a data-fancybox="" href="{{ $posts->image }}" tabindex="0">
                        <img src="{{ $posts->image }}" alt="" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
  .event-section {
    background-color: #2f2f7f;
    padding: 1rem 0;
    color: white;
}

.event-details,
.event-image {
    flex: 1; /* Les deux enfants flex auront la même largeur */
}

.event-title {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.event-info {
    font-size: 1rem;
}

.event-date, .event-location {
    display: block;
    margin-bottom: 0.5rem;
}

.event-image img {
    max-height: 400px;
    width: auto; /* Assurez-vous que l'image n'est pas déformée */
    margin-left: 2rem; /* Espace entre le texte et l'image */
}

/* Pour les appareils plus petits, empilez le texte et l'image verticalement */
@media (max-width: 991px) {
    .event-section .d-flex {
        flex-direction: column;
    }

    .event-image img {
        margin-left: 0;
        margin-top: 2rem; 
        max-width: 100%; 
    }
}


@media (min-width: 992px) {
    .event-section {
        padding: 2rem 0;
    }

    .event-title {
        font-size: 3rem;
    }

    .event-info {
        font-size: 1.25rem;
    }
}
</style>
<section class="choice-section">
    <div class="container">
        <div class="choice-content">
            <div class="icon-container">
                <i class="icon seat-switch-icon icon-seatmap" aria-hidden="true">
                    <span class="sr-only">Choix sur plan</span>
                </i>
            </div>
            <h2 class="choice-title">Choix sur plan</h2>
            <p class="choice-subtitle">de votre place : c'est vous qui décidez.</p>
            <div class="scroll-indicator">
                <span class="dot"></span>
            </div>
        </div>
    </div>
</section>

<style>
   .choice-section {
    background: #ffffff;
    text-align: center;
    padding: 2rem 0;
    border: 1px solid #cccccc;
}

.choice-content {
    display: inline-block;
    max-width: 300px;
}

.icon-container .icon-seatmap {
    font-size: 50px; /* Ajustez la taille de l'icône comme nécessaire */
    color: #FFA500; /* Couleur de l'icône */
    margin-bottom: 1rem;
}

.choice-title {
    color: #333333;
    margin-bottom: 0.5rem;
}

.choice-subtitle {
    color: #777777;
    margin-bottom: 2rem;
}

.scroll-indicator {
    display: flex;
    justify-content: center;
}

.scroll-indicator .dot {
    width: 10px;
    height: 10px;
    background-color: #FFA500;
    border-radius: 50%;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}


</style>
<section id="seat-selection" class="my-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                
<style>
    .seating-chart {
      display: grid;
      grid-template-columns: repeat(28, 30px);
      grid-gap: 5px;
    }
    .seat {
      width: 30px;
      height: 30px;
      background-color: #ddd;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
    }
    .occupied {
      background-color: #f00;
      pointer-events: none;
    }
    .selected {
      background-color: #0f0;
    }
  </style>
    <div class="seating-chart">
      @for ($row = 'A'; $row !== 'AA'; $row++)
        @for ($seatNum = 1; $seatNum <= 28; $seatNum++)
          <div class="seat" data-row="{{ $row }}" data-seat="{{ $seatNum }}"></div>
        @endfor
      @endfor
    </div>
  
  <script>
    document.querySelectorAll('.seat').forEach(seat => {
      seat.addEventListener('click', function() {
        const row = this.dataset.row;
        const seat = this.dataset.seat;
        // AJAX call to Laravel backend to book seat
        axios.post('/book-seat', {
          row: row,
          seat: seat,
          _token: '{{ csrf_token() }}' // CSRF token for Laravel
        }).then(response => {
          // Handle the response from the server
          if (response.data.booked) {
            this.classList.add('selected');
          } else {
            alert('Ce siège a déjà été réservé.');
          }
        }).catch(error => {
          console.error(error);
        });
      });
    });
  </script>
            </div>
        </div>
    </div>
</section>

@endsection