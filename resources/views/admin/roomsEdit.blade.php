@extends('layouts.template')

@section('content')
<main class="main" id="main">
    <div class="container py-5">
        <h4 class="mb-4 text-center text-primary">Modifier la Salle</h4>

        <form method="POST" action="{{ route('rooms.update', $room->id_room) }}">
            @csrf
            @method('PUT')

            <div class="row justify-content-center">
                <div class="col-md-3 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $room->name }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $room->address }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <label for="url_room" class="form-label">URL de la Salle</label>
                            <input type="text" class="form-control" id="url_room" name="url_room" value="{{ $room->map }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card shadow">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <label for="url_room" class="form-label">Salle visible pour les cours</label>
                            <input type="hidden" name="room_active" value="0">
                            <input type="checkbox" class="form-check-input" id="room_active" value="1" name="room_active" @if($room->room_active) checked @endif>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card shadow">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <label for="url_room" class="form-label">Salle visible pour les animations</label>
                            <input type="hidden" name="room_active_animations" value="0">
                            <input type="checkbox" class="form-check-input" id="room_active_animations" value="1" name="room_active_animations" @if($room->room_active_animations) checked @endif>
                        </div>
                    </div>
                </div>                
            </div>

            

            <div class="row justify-content-center">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Soumettre</button>
                </div>
            </div>
        </form>
    </div>
</main>


@endsection
