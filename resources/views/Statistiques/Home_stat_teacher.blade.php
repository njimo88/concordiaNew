@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main id="main" class="main">
@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


    <div class="container">

            @if (auth()->user()->role == 40 || auth()->user()->role == 30 )

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

                      <div class="chart-container" style="position: relative; width:100%">

                                    <canvas id="myChart"  style="margin-left:-120px;" ></canvas>

                      </div>

                </div>
                </div>

              

                
    </div>



                @endif

               
             
     </main>


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







@endsection



















