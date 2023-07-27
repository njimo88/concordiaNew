@extends('layouts.template')

@section('content')
@php
require_once('../app/fonction.php');
   
@endphp

require_once(app_path().'/fonction.php');
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main id="main" class="main">

@php
 $saison_actu = saison_active() ;
$saison_active = saison_active() ;
      $annee_creation = 2015 ;
      $years = array();
      $years  = generateArray($annee_creation,$annee_actu,1);
      $stat_values_per_year = array();

      foreach ($years as $i) {

         $stat_values_per_year[] = nbr_inscrits_based_on_date($i) ; // get the number of subscribers by year

      }

    


@endphp

    <section class="section dashboard">

      <div class="row d-flex justify-content-center ">
         @if (auth()->user()->role >= 90)
            
         
      <div class="col-6 col-md-2 ">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Chiffre d'affaire</h5>
                         <div>
                             <i  style="color: #0bad00; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa-solid fa-chart-line"></i></div>
                           
                             
                               <h6  style="font-size:14px; text-align:left">   @php   $CA = count_CA() ;  $formatted_number = number_format($CA, 2, '.', ','); @endphp {{$formatted_number}}€</h6>
                       
                         </div>
                      </div>
                   </div>
                
                <div class="col-6 col-md-2 ">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Reste</h5>
                         <div >
                            <i style=" color: #ad0000; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-coins"></i></div>
                            
                        <h6  style="font-size:14px; text-align:left">   @php  $CA_reste = count_reste_CA() ; $formatted_number = number_format($CA_reste, 2, '.', ','); @endphp {{$formatted_number}}€</h6>
                          
                         </div>
                      </div>
                   </div>
                   @endif 
                <div class="col-6 col-md-2 ">
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

                <div class="col-6 col-md-2 ">
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
                <div class="col-6 col-md-2 ">
                  <div class="card info-card sales-card p-3">
                      <div class="card-body pt-4" style="min-height: 87px;">
                          <h5 style="font-size:15px;">Déterm. Section </h5>
                          <div>
                              <i style=" color: #a900d4; font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-question "></i> 
                          </div>
                          <h6 style="font-size:14px; text-align:left">{{ $determinSecValue }}</h6>
                      </div>
                  </div>
              </div>
              
                <div class="col-6 col-md-2 ">
                   <div class="card info-card sales-card p-3">

                      <div class="card-body pt-4" style="min-height: 87px;">
                         <h5 style="font-size:15px;">Pages Visitées</h5>
                         <div>
                            <i style=" color: #e60000;font-size:160%;  position:absolute; top: 7px; right:7px;" class="fa fa-eye"></i></div>
                          
                            @php

                                    // un tableau avec les chemins de certaines pages specifiques

                                    $top_10_cate = ['Categorie_front','SubCategorie_front/1206','SubCategorie_front/2','SubCategorie_front/100','SubCategorie_front/120','SubCategorie_front/1004','SubCategorie_front/201','SubCategorie_front/1204','Mentions',]; 
                                    $top_10_visiteurs = array() ; 

                           @endphp

                              

                               <h6  style="font-size:14px; text-align:left">{{$nbre_visit}}</h6>


                      
                                                      
                </div>
                      </div>
                </div>

                @php

                $top_10_cate = [] ;

                  @endphp

                @foreach($get_stat_pages as $dt)

                         
                        
                        @php

                        $top_10_cate[]  =    put_label($dt->page) ;
                        $top_10_visiteurs[] = $dt->nbre_visitors ;
                     
                        @endphp

               @endforeach


             
                           

</div>

<div class="row">

   @if(\App\Models\shop_article_1::isUserTeacher(auth()->user()->user_id))
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
       <div class="container">
   
               
   
                               @php 
   
   
                                           $id_teacher = auth()->user()->user_id ;
                                           $my_articles = [] ;
                                           $add = [] ;
                                           $calcul = 0 ;
                                           $calcul_tab = [] ;
   
                               @endphp
   
   
                               @foreach($shop_article_lesson as $data)
   
   
                               @php 
                               $add [] = (array)json_decode($data->teacher) ; 
                            
   
                               if (isset($add)) {
                                       foreach ($add as $teacherArray) {
                                                   
                                               foreach($teacherArray as  $t){
                                                           
                                                   if ($id_teacher === $t){
                                                           array_push($my_articles,$data->title."   ".(int)$data->stock_ini - (int)$data->stock_actuel ."/".$data->stock_ini);
   
                                                           
                                                          
                                                if((int)$data->stock_ini != 0){
   
                                                           $calcul =round( ( (int)$data->stock_ini - (int)$data->stock_actuel ) * 100 / (int)$data->stock_ini ,0);
                                                                   if ($calcul>100){
                                                                       array_push($calcul_tab,100);
                                                                   }
                                                                   else{
                                                                       array_push($calcul_tab,$calcul);
                                                                   }
                                                                   
   
                                                          
   
                                                           }else{
                                                               array_push($calcul_tab,0);
                                                           }
   
   
                                                           
                                                          
                                                         
                                                   }
   
                                               }
   
                                            
   
                                           }
                                           
   
                                       }
   
                                       $add = [] ;
                                       $calcul = 0 ;
   
                               @endphp
   
                               
                               @endforeach
   
                               @php 
                               
                               $my_articles = array_unique($my_articles) ;
                              
                             
                               @endphp 
   
                    
                        
                      <!--   <canvas id="myChart"  style="margin-left:-120px;"  width="955" height="750"></canvas> -->
   
                         <div class="chart-container d-flex justify-content-center " >
   
                                       <canvas id="myChart" ></canvas>
   
                         </div>
   
                   </div>
                   </div>
   
                 
   
                   
       </div>
   
   
   
   
                  
                
   
   
                   <script>
   
   
                   var xValues = <?php echo json_encode($my_articles); ?>; 
   
                   var yValues = <?php echo json_encode($calcul_tab); ?>; 
                  //var yValues =[100,100,100,100,100,100,100,100];
   
                   var colors = [] ;
                  // var barColors = ["red", "blue","orange","brown"];
   
                   for (var i = 0; i < yValues.length; i++) {
   
   
                       switch (yValues[i] > 0) {
               case (yValues[i] < 20):
                   colors.push('#f2362c');
                   break;
               case (yValues[i] >= 20 && yValues[i] < 40):
                   colors.push('#eb9c15');
                   break;
               case (yValues[i] >= 40 && yValues[i] < 60):
                   colors.push('#dfe62c');
                   break;
               case (yValues[i] >= 60 && yValues[i] < 80):
                   colors.push('#c9eb34');
                   break;
               case (yValues[i] >= 80 && yValues[i] < 100):
                   colors.push('#007ebd');
                   break;
               case (yValues[i] == 100):
                   colors.push('#7dd600');
                   break;
           }
                           }
   
                   new Chart("myChart", {
                   type: "bar",
                   data: {
                       labels: xValues,
                       datasets: [{
                           label: 'Pourcentage',
                       backgroundColor: colors,
                       data: yValues
   
                       }]
                       
                   },
                   
                   options: {
                       responsive:true,
                       maintainAspectRatio: true,
                       legend: {
                           display: false,
                           title: {
                                       display:false,
                                       text: 'Pourcentage'
                                   }
                                                                   
                       },
                       title: {
                       display: true,
                       text: "Statistiques des cours"
                       },
                       scales: {
                           yAxes: [{
                           ticks: {
                               min: 0,
                               max: 100,
                               beginAtZero: true
                           },
                           xAxes: [{
                                   ticks: {
                                       autoSkip: false,
                                       maxRotation: 90,
                                       minRotation: 90,
                                       maxTicksLimit: 20
                                   },
                                  
                                   }],
                           
                           }]
                       },
                   },
                  
     minBarLength: 1, // display bars for values that are equal to 0
     plugins: {
               datalabels: {
                   display: false, // désactiver l'affichage des étiquettes de données
                  
               }
           },
           hover: {
               mode: null // désactiver le survol des étiquettes
           }
                   });
   
   
                   </script>
   
   
   
   @else
<div class="col-12 col-md-8">

      

               
                  <h5 style="font-size:15px;">Inscrits par saison</h5>
                  
                  
               <canvas id="myChart" style="width:100%;"></canvas>
</div>

     @endif      

            



@if (auth()->user()->role >= 90)
   

<div class="col-12 col-md-4">


<canvas id="chart" ></canvas>



</div>
@endif








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
    
var y_Values = <?php echo json_encode($top_10_visiteurs); ?> ;

var x_Values = <?php echo json_encode($top_10_cate); ?> ;


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


