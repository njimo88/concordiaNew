@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">
@foreach($shop_article as $data)
<div class="container">
<div  class="row">
    <div style="margin-top : 80px;" id="vueParent">
        <div class="card shadow mb-4 border rounded">
            <div style="background-color: #f3eded;height:45px;padding: 3px;">
                <span x style="margin-right: 5px; vertical-align: sub;">
                    <a style="text-decoration:none" href="https://www.gym-concordia.com/index.php/category//1">
                        <img style="height:26px" src="">
                    </a>
                </span>
                <span class="info-news-emit">
                    <img src="https://www.gym-concordia.com/favicon.png" alt="Gym concordia" title="Gym Concordia"><font style="color: #b8c2c2; text-shadow: 1px 1px 1px #ffffff;vertical-align: -webkit-baseline-middle;">Publié par <a id="test" href="#"  >Gym Concordia</a> le
                                              <em><?php echo date("d/m/Y", strtotime($data->date_post)); ?></em>
        </font>
      </span>
    </div>
            <div class="card-header py-3">
               
                   <h5> Politique de confidentialité </h5> 
                  
            </div>
            <div style="text-align: left !important;" class="card-body">
          

                {!! $data->contenu !!} 

            @endforeach

            </div>
           
        </div>
    </div>
</div>
</div>

</main>
@endsection