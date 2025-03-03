@extends('layouts.app')

@section('content')

<div class="container">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>

    <h1 class="mb-4">Spectacles</h1>
    <a target="_blank" href="{{ route('spectacles.create') }}" class="btn btn-primary mb-3">Create Spectacle</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container d-flex justify-content-center">
        <div class="row justify-content-center gap-4">
            @foreach ($spectacles as $spectacle)
            <div class="col-lg-3 col-md-5 col-sm-10">
                <a href="{{ route('spectacles.seats', $spectacle->id_spectacle) }}" target="_blank" class="card-link">
                    <div class="card">
                        <img src="{{ asset($spectacle->image) }}" class="card-img-top" alt="{{ $spectacle->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $spectacle->name }}</h5>
                            <p class="card-text">{{ $spectacle->description }}</p>
                            <p class="card-text">{{ $spectacle->date }}</p>
                            <a target="_blank" href="{{ route('spectacles.edit', $spectacle->id_spectacle) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('spectacles.destroy', $spectacle->id_spectacle) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </a>    
            </div>
            @endforeach
        </div>
    </div>


    {{-- scaner  --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    <div id="reader" style="width: 300px;"></div>
    
    <script>
        function onScanSuccess(qrMessage) {
            alert("QR Code Scanned: " + qrMessage);
            
            fetch('/spectacles/validation_Teckit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ qr_code: qrMessage })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    alert("✅ " + body.message); // Access granted
                    // You can redirect or do something else:
                    //window.location.href = "/success-page"; 
                } else {
                    if (status === 400) {
                        alert("❌ " + body.message);
                    }if (status === 300) {
                        alert("❌ " + body.message);
                    }
                    //alert("❌ " + body.message); // Invalid ticket or already used
                }
            })
            .catch(error => console.error('Fetch Error:', error));
        }
    
        function onScanError(errorMessage) {
            console.log(errorMessage);
        }
    
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>




    {{-- <script src="https://unpkg.com/html5-qrcode"></script>

<div id="reader" style="width: 300px;"></div>

<script>
    function onScanSuccess(qrMessage) {
        alert("QR Code Scanned: " + qrMessage);
        window.location.href = '/spectacles/validation_Teckit=' + encodeURIComponent(qrMessage);
    }

    function onScanError(errorMessage) {
        console.log(errorMessage);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 }
    );
    html5QrcodeScanner.render(onScanSuccess, onScanError);
</script> --}}
        
</div>

    
    
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
</style>
    
@endsection

@section('test')
<h1>test content </h1>
@endsection