@extends('layouts.template')

@livewireStyles
@livewireScripts

@section('content')
<main id="main" class="main">
    {{-- @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif --}}

    <!-- Inclure le composant Livewire -->
    <div class="container">
        @livewire('roles-table')
    </div>
</main>
@endsection