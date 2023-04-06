@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>    

$(document).ready(function() {

    $('.toggle-button').click(function() {
        var articleId = $(this).data('article-id');
        $.ajax({
            type: "GET",
            url: "get_data_table/ "+ articleId,
            data: {
                article_id: articleId,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                // Handle the response from the server
                console.log(articleId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error
            }
        });
    });



  // Hide all tables by default
  $('table[id^="my-table-"]').hide();
  
  // Show the corresponding table when the button is clicked
  $('button[id^="toggle-button-"]').click(function() {
    var id = $(this).attr('id').replace('toggle-button-', '');
    $('#my-table-' + id).toggle();

    var articleId = $(this).data('article-id');
        $.ajax({
            type: "POST",
            url: "get_data_table/ "+ articleId,
            data: {
                article_id: articleId,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                // Handle the response from the server
                console.log(articleId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error
            }
        });



  });
});
 





</script>


@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


<div class="container">


 
    <div class="col-md-4">  
       
        <label> Saison </label>
        <form action="{{ route('include-page') }}" method="POST" >
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


@if (auth()->user()->role == 40 || auth()->user()->role == 30 )


      @php 
              $id_teacher = auth()->user()->user_id ;
              $my_articles = [] ;
              $add = [] ;
      @endphp



                    @if(session('submitted'))
                        
                        
                    @else




                    @endif
                            
                        
     
      

@elseif (auth()->user()->role == 90 || auth()->user()->role == 100)


                @if(session('submitted'))
                    
                    
                        @else

                    
                        @foreach($shop_article_lesson as $data)
                 
                        <input readonly  class="btn btn-secondary"  value="{{$data->title}}">
                                   
                        
                                        <a  id="load-content" href="{{route('form_appel',$data->id_shop_article)}}">Faire l'appel</a>
                                                        
                                                        
                                                    
                                                        <div id="content"></div>
                                                        
                            
                                    </div>
 
                                    <button id="toggle-button-{{ $data->id_shop_article }}" data-article-id="{{ $data->id_shop_article }}" > {{$data->title}} </button>

                              
                                                    <table id="my-table-{{ $data->id_shop_article }}" style="display: none; background-color:green;"  data-article-id="{{ $data->id_shop_article }}" class="table table-hover" >
                                                    <thead>
                                                        <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                            <tr>   
                                                                <td></td>
                                                            </tr>
                                                       
                                                    </tbody>
                                                    </table>

                                                        
                                                                    @endforeach



                                                                        
                    
                            @endif

         
@endif





</main>
    


@endsection