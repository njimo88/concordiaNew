@extends('layouts.template')

@section('content')
<main class="main" id="main">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h1 class="text-center mb-4">Liste des salles</h1>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Adresse</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->address }}</td>
                <td>
                    <a href=" {{ route('rooms.edit', $room->id_room) }} " class="btn btn-info btn-sm">Modifier</a>
                    <form action=" {{ route('rooms.destroy', $room->id_room) }} " method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</main>
@endsection