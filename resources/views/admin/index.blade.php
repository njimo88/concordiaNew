@extends('layouts.template')

@section('content')
@php
require_once('../app/fonction.php');
   
@endphp


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main id="main" class="main">

@php $saison_actu = saison_active() ;@endphp

    <section class="section dashboard">

      <div class="row d-flex justify-contetnt-center p-0">
         
      <div class="col-6 col-md-2 p-0 "style="margin:0px -7px" >
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Chiffre d'affaire</h5>
                         <div>
                             <i  style="color: #0bad00; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa-solid fa-chart-line"></i></div>
                           
                             
                               <h6  style="font-size:14px; text-align:left">   @php   $CA = count_CA() ;  $formatted_number = number_format($CA, 2, '.', ','); @endphp {{$formatted_number}}€</h6>
                       
                         </div>
                      </div>
                   </div>
                
                <div class="col-6 col-md-2 p-0" style="margin:0px -7px">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Reste</h5>
                         <div >
                            <i style=" color: #ad0000; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-coins"></i></div>
                            
                        <h6  style="font-size:14px; text-align:left">   @php  $CA_reste = count_reste_CA() ; $formatted_number = number_format($CA_reste, 2, '.', ','); @endphp {{$formatted_number}}€</h6>
                          
                         </div>
                      </div>
                   </div>
                
                <div class="col-6 col-md-2 p-0" style="margin:0px -7px">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Saison active</h5>
                         <div>
                            <div class=""> <i style="color: #2770e6; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-calendar-check "></i></div>
                            
                               <h6  style="font-size:14px; text-align:left">{{$saison_actu}} - {{$saison_actu + 1}}</h6>
                         
                         </div>
                      </div>
                   </div>
                </div>

                <div class="col-6 col-md-2 p-0" style="margin:0px -7px">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Inscrits</h5>
                         <div >
                            <div class=""> <i style=" color: #03a100 ; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-user"></i></div>
                           
                               <h6  style="font-size:14px; text-align:left"> @php  $nbre_inscrits = inscrits();  @endphp  {{$nbre_inscrits}} </h6>
                           
                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-6 col-md-2 p-0" style="margin:0px -7px">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Déterm. Section </h5>
                         <div >
                            <i style=" color: #a900d4; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-question "></i> </div>
                          
                               <h6  style="font-size:14px; text-align:left">145</h6>
                           
                         </div>
                      </div>
                   </div>
                <div class="col-6 col-md-2 p-0" style="margin:0px -7px">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Pages Visitées</h5>
                         <div >
                            <i style=" color: #e60000;font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-eye"></i></div>
                          
                               <h6  style="font-size:14px; text-align:left">{{ session('visitor_count', 0) }}</h6>
                               @foreach(Session::get('page_count_array', []) as $page => $count)
                              <p>{{ $page }}: {{ $count }}</p>
                           @endforeach
                                                      
                </div>
                      </div>
                </div>
                
             

</div>

<div class="row">


<div class="col-12 col-md-8">

@php

      $years = array();
      
      $stat_values_per_year = array();

      for ($i = 2015; $i <= 2025; $i++) {

         $years[] = $i; // add each year to the array

         $stat_values_per_year[] = nbr_inscrits_based_on_date($i) ; // get the number of subscribers by year

      }

     // dd($stat_values_per_year);


@endphp
      

               
                  <h5 style="font-size:15px;">Inscrits par saison</h5>
                  
                  
               <canvas id="myChart" style="width:100%;"></canvas>
</div>

           

            




<div class="col-12 col-md-4">


<canvas id="chart" ></canvas>

</div>













</div>


        
  
 
    </section>
    
<script>

const xValues = <?php echo json_encode($years); ?>; 
const yValues = <?php echo json_encode($stat_values_per_year); ?>; 




new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      label: '',
      data: yValues
    }]
  },
  options: {
   
  }
});
</script>

















<script>
var x_Values = ['categorie-1','gym-feminine','vac-scolaires','petite-enfance','loisirs','4-5-ans-ecole-de-gym','stages-loisir','gym-rytmique','tions Legales',];
var y_Values = [55, 49, 44, 24, 15];
var barColors = [
   
                'rgb(3, 232, 252)',
                'rgb(252, 223, 3)',
                'rgb(252, 3, 3)',
                'rgb(3, 252, 36)',
                'rgb(54, 162, 235)',
                'rgb(255, 0, 204)',
                'rgb(255, 145, 0)',
                'rgb(179, 0, 255)',
                'rgb(0, 255, 208)',
                'rgb(255, 221, 0)',
                'rgb(150, 150, 150)'

];

new Chart("chart", {
  type: "pie",
  data: {
    labels: x_Values,
    datasets: [{
      backgroundColor: barColors,
      data: y_Values
    }]
  },
  options: {
    title: {
      display: true,
      text: "World Wide Wine Production 2018"
    }
  }
});
</script>

 </main>
 <footer id="footer" class="footer">
    <div class="copyright"> &copy; Copyright <strong><span>Gym Concordia</span></strong>. All Rights Reserved</div>
 </footer>
 <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> 
   
</body>



  
@endsection


