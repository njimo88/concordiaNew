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
           
            <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}">
            <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">



                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-myvalue="{{$data->id_shop_article}}">
                Launch demo modal
                </button>
                            <a  id="load-content" href="{{route('form_appel',$data->id_shop_article)}}">Load more</a>
                                
                                
                              
                                 <div id="content"></div>
                               
   
            </div>

            @endforeach
            </div>
            </table>

</div>


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



                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-myvalue="{{$data->id_shop_article}}">
                Launch demo modal
                </button>
                            <a  id="load-content" href="{{route('form_appel',$data->id_shop_article)}}">Load more</a>
                                
                                
                              
                                 <div id="content"></div>
                               
   
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
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

</script>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
     
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


</main>
    


@endsection