@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
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
                                                        array_push($my_articles,$data->title."   ".$data->stock_actuel."/".$data->stock_ini);

                                                        
                                                       
                                             if((int)$data->stock_ini != 0){

                                                        $calcul = (int)$data->stock_actuel * 100 / (int)$data->stock_ini ;
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

                            <div class="card" style="width: 130rem;">
                <div class="card-header">
                Statistiques des cours
                </div>
                <div class="card-body" style="width:75rem;">
                
                      <canvas id="myChart" style="padding-right:150px;width:1000px;max-width:2000px;"></canvas>
                </div>
                </div>

              

                




                @endif

               
             
     </main>


                <script>


                var xValues = <?php echo json_encode($my_articles); ?>; 
                var yValues = <?php echo json_encode($calcul_tab); ?>; 
               //var yValues =[100,100,100,100,100,100,100,100];

                var colors = [] ;
                var barColors = ["red", "blue","orange","brown"];

                for (var i = 0; i < yValues.length; i++) {
                    if (yValues[i] === 100) {
                        colors.push('green');

                    }else if((yValues[i] >= 60 ) && (yValues[i] <100) ){
                        colors.push('lightgreen');
                    }else {
                        var randomColor = barColors[Math.floor(Math.random() * barColors.length)];
                        colors.push(randomColor);
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
                    responsive: true,
                    maintainAspectRatio: true,
                    legend: {
                        display: true,
                        title: {
                                    display: true,
                                    text: 'Pourcentage'
                                }
                                                                
                    },
                    title: {
                    display: true,
                    text: "Statistiques cours"
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
                                }
                                }],
                        
                        }]
                    },
                },
                plugins: {
    datalabels: {
      align: 'end',
      anchor: 'end',
      font: {
        size: 14
      },
      color: 'black',
      
    }
  },
  minBarLength: 1 // display bars for values that are equal to 0
                });


                </script>







@endsection



















