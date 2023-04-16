
@extends('layouts.template')

@section('content')

@php

require_once(app_path().'/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">
  <div class="row">
    <div class="col-md-4"><a href="{{route('index_cours')}}"><button>Retour</button></a></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"><a class="btn btn-primary" href=""><button id="generate-pdf">Generer PDF </button></a></div>
  
  </div>
    

                        <h2 style="text-align:center;">Fiche de presence </h2>
<div class="container">
    @php

        $array_user_id = [] ;
        $array_user_presence = [] ;

    @endphp



<table  class="table">
  <thead>
    <tr>
      <th>Date</th>
      @foreach($appel as $data1)

          
               <th>{{$data1->date}}</th>

       @php   $tab_date[] = $data1->date  ;  $num = 1; @endphp 
           

                
      @endforeach


    </tr>
  </thead>
          
        

  <tbody>

  @foreach($users as $data)
      <tr>
   
            <td>{{$data->name}} {{$data->lastname}}</td>

                @foreach($tab_date as $dt)
                <td>
                @foreach($appel as $data1)
                    @if( $data1->date == $dt)

                      @foreach(json_decode($data1->presents) as $key =>  $op)
                              @if($key == $data->user_id)

                                  @if ($op == 1)

                                              <div style="color:green"> <i class="fa fa-check"></i> </div>

                                              @else

                                              <div style="color:red"> <i class="fa-solid fa-xmark"></i> </div>

                                   @endif




                              @endif
                      
                      @endforeach

                    @endif
                @endforeach
                </td> 
              @endforeach
      </tr>      

  @endforeach

     
   

 
  </tbody>
</table>



</div>















</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
function generatePDF(callback) {
  // Create a new jsPDF instance
  const doc = new jsPDF();

  // Get the HTML content to add to the PDF document
  const html = document.getElementById("main").innerHTML;

  // Add the HTML content to the PDF document
  doc.fromHTML(html, 10, 10);
  doc.save("example.pdf");
  doc.output("datauristring");
  // Save the PDF document
  
}




</script>

<script>
  document.getElementById("generate-pdf").addEventListener("click", generatePDF);
</script>



</main>
    


@endsection


