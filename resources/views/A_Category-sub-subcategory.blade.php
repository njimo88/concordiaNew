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
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
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
   <body>
    
    
   @if(!empty($category->categories))
    <ol class="dd-list list-group">
        @foreach($category->categories as $kk => $sub_category)
            <li class="dd-item list-group-item" data-id="{{ $sub_category['id_shop_category'] }}" >
                <div class="dd-handle" >{{ $sub_category['name'] }}</div>
                <div class="dd-option-handle">
                <a href="{{ route('category-edit', [ 'id_shop_category' => $sub_category['id_shop_category'] ]) }}" class="btn btn-success btn-sm" >Modifier</a> 
                <a href="{{ route('category-remove', [ 'id_shop_category' => $sub_category['id_shop_category'] ]) }}" class="btn btn-danger btn-sm" >Supprimer</a> 
                </div>

                @include('A_child_categorie', [ 'category' => $sub_category])
            </li>
        @endforeach
    </ol>
@endif

   </body>
</html>