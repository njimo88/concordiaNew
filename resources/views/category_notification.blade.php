<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>rhino</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="../r_css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="../r_css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="../r_css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="../r_images/fevicon.png" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="../r_css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
   </head>
   <!-- body -->
   <body class="main-layout">
     
      
       
   @if(session()->get('success'))
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-success">
            {{ session()->get('success') }}
            </div>
        </div>
    </div>
@endif

@if(session()->get('error'))
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-danger">
            {{ session()->get('error') }}
            </div>
        </div>
    </div>
@endif



































   











     
     
   </body>
</html>
