
@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">
  <div class="row">
    <div class="col-md-4"><a href="{{route('enregistrer_appel',$id_cours)}}"><button>Retour</button></a></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"><a class="btn btn-primary" href=""><button id="generate-pdf">Generer PDF </button></a></div>
  
  </div>
    

                        <h2 style="text-align:center;">Fiche de presence </h2>
<div class="container">

<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      @foreach($users as $data)
            <th scope="col">{{$data->name }} {{$data->lastname}}</th>
            @endforeach
    </tr>
  </thead>
  <tbody>
  @foreach($appel as $data1)
    <tr>
    
    <th scope="row">{{$data1->date}}</th>

    @foreach ($present as $value) 
        @foreach($value as $key => $val)

            @if($key==$data1->date)
                @foreach($val as $valeur)

            <th scope="col"> 
                
            @if ($valeur == 1) 
            
            <div style="color:green"> <i class="fa fa-check"></i> </div>

            @else

            <div style="color:red"> <i class="fa-solid fa-xmark"></i> </div>


            @endif

            </th>

                @endforeach

            @endif

        @endforeach

    @endforeach

  
    </tr>
   
    @endforeach
     
    
  </tbody>
</table>


















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


