@if ($val == 1) 

                            <td style="color:green"> <i class="fa fa-check"></i> </td>

                        @else
                            <td style="color:green"> <i class="fa-solid fa-xmark"></i> </td>

                        @endif



                        

<table class="table">
  <thead>
  
    <tr>   
     <th scope="col">Date</th>
     @foreach($appel as $data)
      <th scope="col">{{ $data->date }}</th>

  
      @endforeach
    </tr>
  
  </thead>
  <tbody>
    @php $i = 0 ; @endphp 

    @foreach($users as $data1)
   
        <tr>
        <th scope="row"> {{$data1->name }} {{$data1->lastname}}</th>
        @foreach($appel as $dt)
        <th scope="row">
            @foreach($present as $value)
          
                @foreach($value as $key => $val)
              
                        @if ($dt->date == $key)
                        



                        @endif
                    @break 
                @endforeach

          
          
            @endforeach
        </th>
        @endforeach
        
        </tr>
    
    @endforeach
  

    
    @php $i = $i+1 ;@endphp
    

  </tbody>
</table>
  

