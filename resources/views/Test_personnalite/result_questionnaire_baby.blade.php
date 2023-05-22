@extends('layouts.app')

@section('content')

        @php
        require_once(app_path().'/fonction.php');

        @endphp

        <main id="main" class="main">
<style>

</style>


       @foreach($shop_category as $data)

  
       <div class="container">
       <h2>  Resultats : </h2><br>
  
  <!-- Left-aligned media object -->
  <div class="media">
    <div class="media-left">
      <img src="{{ $data->image }}" class="media-object" style="width:160px">
    </div>
    <div class="media-body">

    </div>
  </div>
  <hr>
  
  <!-- Right-aligned media object -->
  <div class="media">
    <div class="media-body">
    
      <p> {!! $data->description !!} </p>
    </div>
   
  </div>
</div>


@endforeach









        </main>

@endsection


	

	
