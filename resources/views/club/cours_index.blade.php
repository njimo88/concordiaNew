@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">


<div class="container">


 
    <div class="col-md-4">  
       
        <label> Saison </label>
        <form action="{{ route('include-page') }}" method="POST" onsubmit="hideRow(event)">
            @csrf
          <select class="form-control" name="saison" id="saison">
                   
                   @foreach($saison_list as $data)

                                   <option value="{{$data->saison}}" {{ $data->saison == $saison_active ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                   
                   
                                   @endforeach

          </select>
          <button type="submit" id="hide-row-btn" >Submit</button>
        
        </form>
    </div>

    <div class="col-md-4"></div>
    <div class="col-md-4"></div>

 @if(session('submitted'))
       
 <div id="div1-content">
    <h1 style="text-align:center;"> 
  
       {{$saison}}
   
    
    </h1>
  <table> 

                <div class="d-grid gap-2">
            @foreach($shop_article as $data)
           
            <input readonly onclick="loadView('{{ $data->id_shop_article }}')" class="btn btn-secondary"  value="{{$data->title}}">
            <div id="view-container" style="display: none;">
                {{-- insert content for the view here --}}
            </div>
            @endforeach
            </div>
            </table>

</div>

    @else



    <div id="div1-content">
    <h1 style="text-align:center;"> 
   
        {{$saison_active}}
   
    
    </h1>
  <table> 

                <div class="d-grid gap-2">
            @foreach($shop_article_first as $data)
           
            <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}">
            <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">


            @include('shop_article_cours_ajax')
              -- insert content for the view here --
            </div>

            @endforeach
            </div>
            </table>

</div>


    @endif
    

</div>

<script>

function toggleElement(id) {
   
var element = document.getElementById('my-element-' + id);
    if (element.style.display === 'none') {
        // Make a GET request to the toggle route with the ID as a parameter
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // If the toggle request is successful, show the element
                    element.style.display = 'block';
                } else {
                    // If there is an error, log it to the console
                    console.error('Error: ' + xhr.status);
                }
            }
        };
        xhr.open('GET', '/shop_article_cours_ajax/' + id);
        xhr.send();
        console.log('Sent AJAX request for ID ' + id);
    } else {
        // Hide the element
        element.style.display = 'none';
    }

}





</script>




</main>
    


@endsection