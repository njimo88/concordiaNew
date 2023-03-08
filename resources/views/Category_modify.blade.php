
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
</body>
</html>
<body>


@foreach($info as $value1)
<div class="d-flex justify-content-center">


<form class="form-horizontal pt-5" action="{{route('edit',$Id)}}" method="POST">
   
    @csrf

           
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Nom</label>
                <div class="col-sm-12">
                <input class="form-control" id="focusedInput" type="text" name="nom" placeholder="le nom de la catégorie.." value="{{$value1->name}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Image</label>
                <div class="col-sm-12">
                <input class="form-control" id="focusedInput" type="text"   name="image" placeholder="le chemin de l'image.." value="{{$value1->image}}">
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-12">
            
                <div class="form-outline">
                        <textarea class="form-control" id="textAreaExample1" rows="4" name="description" placeholder="la description..">    {!! $value1->description !!} </textarea>
                        </div>
                </div>
            </div>

         

        

            <br>
            <div class="form-group">
            <div class="col-sm-12">
                    <select class="form-select" aria-label="Default select example"  name="action">
                                <option value="new_cat">Créer une nouvelle catégorie</option>

                                @foreach($shop_category as $dt)

                                <option value="{{$dt->id_shop_category}}" {{$value1->id_shop_category == $dt->id_shop_category ? "selected":" " }} >{{ $dt->id_shop_category}} -   {{ $dt->name }}</option>
                              
                                @endforeach

                    </select>
           </div>
            </div>
                                 
            <br>           <label class="form-check-label" for="flexCheckDefault">
                            Active ?
                            <input class="form-check-input" type="checkbox" value="{{$value1->active}}" {{ $value1->active == 1 ? 'checked' : 0 }}  name="active" id="flexCheckDefault">
                           
                        </label>
                       
                       <br>
                       <br>
                       
                       
                        
                        <input class="btn btn-primary"type="submit" value="Valider">
                    
                    </form>


</div>


@endforeach
</body>
