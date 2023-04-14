@extends('layouts.template')

@section('content')
require_once('../app/fonction.php');


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main id="main" class="main">

@php $saison_actu = saison_active() ;@endphp

    <section class="section dashboard">

      <div class="row">
         
      <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Chiffre d'affaire</h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i style="color: #0bad00;" class="fa-solid fa-chart-line"></i></div>
                            <div class="ps-3">
                               <h6>       @php  $CA = count_CA() ; @endphp  {{$CA}}  €</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Reste</h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i style="font-size:15px; color: #ad0000;" class="fa fa-coins"></i></div>
                            <div class="ps-3">
                            <h6>       @php  $CA_reste = count_reste_CA() ; @endphp  {{$CA_reste}}  €</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Saison active</h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i style="font-size:15px; color: #2770e6;" class="fa fa-calendar-check "></i></div>
                            
                               <h6 style="font-size:large;">{{$saison_actu}} - {{$saison_actu + 1}}</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                           
                         </div>
                      </div>
                   </div>
                </div>

                <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Inscrits</h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i style="font-size:15px; color: #03a100" class="fa fa-user"></i></div>
                            <div class="ps-3">
                               <h6 >145</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Déterm. Section </h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i style="font-size:15px; color: #a900d4;" class="fa fa-question"></i> </div>
                            <div class="ps-3">
                               <h6>145</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-6 col-md-4">
                   <div class="card info-card sales-card">

                      <div class="card-body">
                         <h5 class="card-title">Pages Visitées</h5>
                         <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i style="font-size:15px; color: #e60000;" class="fa fa-eye"></i></div>
                            <div class="ps-3">
                               <h6>145</h6>
                               <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1"></span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                
             

</div>

<div class="row">


<div class="col-12 col-md-6">


         <div class="card info-card sales-card">

                              <div class="card-body">
                                 <h5 class="card-title"></span></h5>
                                 <div class="d-flex align-items-center">
                              
                                    <div class="ps-3">
                                    <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
         
                                    </div>
                                 </div>
                              </div>
         </div>







</div>



<div class="col-12 col-md-6">



<div class="card info-card sales-card">

<div class="card-body">
   <h5 class="card-title"></span></h5>
   <div class="d-flex align-items-center">

      <div class="ps-3">
      <canvas id="chart" style="width:100%;max-width:600px"></canvas>

      </div>
   </div>
</div>
</div>














</div>













</div>


        
  
 
    </section>
    
<script>
const xValues = [50,60,70,80,90,100,110,120,130,140,150];
const yValues = [7,8,8,9,9,9,10,11,14,14,15];

new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 6, max:16}}],
    }
  }
});
</script>

<script>
var x_Values = ["Italy", "France", "Spain", "USA", "Argentina"];
var y_Values = [55, 49, 44, 24, 15];
var barColors = [
  "#b91d47",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#1e7145"
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


