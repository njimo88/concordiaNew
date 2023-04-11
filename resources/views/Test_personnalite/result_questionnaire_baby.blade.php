@extends('layouts.app')

@section('content')

        @php
        require_once('../app/fonction.php');

        @endphp

        <main id="main" class="main">
<style>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container img {
  max-width: 100%;
  max-height: 100%;
}

.content {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 0vh;
  border-color: darkblue;
}

.po
</style>


       @foreach($shop_category as $data)


      
       <p class="position"> <h2>  Resultats : </h2> </p>
    

        <div class="container">
            
        <img src="{{ $data->image }}">
       
        </div>
        <div class="content">
       <p> {!! $data->description !!} </p>
        </div>

        <br>

       @endforeach








        </main>

@endsection


	

	
