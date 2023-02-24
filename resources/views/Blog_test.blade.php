<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel 9 load more page scroll</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
  
<body>
  
  <div class="container">
 
   <div class="row">
        <div id="vueParent">
            <div id="posts" next-page-url=" {{ $a_post->nextPageUrl() }}">
			@foreach($a_post as $a_article)
			<div class="jumbotron">
			
				<h1 class="display-4">{{$a_article->titre}}</h1>
				<p class="lead"> {{!!$a_article->contenu!!}}</p>
				<hr class="my-4">
				<p>{{$a_article->date_post}}</p>

			
				
          </div>
		  @endforeach      
                   
               
              
            </div>
		
        </div>
		
    </div>


  </div>

   
  <script type="text/javascript">
        $(window).scroll(function () {
            clearTimeout(fetch);
          
            fetch = setTimeout(function () {
                var page_url = $("#posts").attr('next-page-url');
                console.log("scrolled");
              // This condition is very essential //
                if (page_url != null) {
                    $.get(page_url, function (data) {
                        $("#posts").append(data.view);
                        $("#posts").attr('next-page-url', data.url);
                    });
                }
            }, 2000);
        });
    </script>



</body>


</html>