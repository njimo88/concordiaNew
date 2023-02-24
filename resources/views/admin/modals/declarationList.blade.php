
<div class="table-responsive">
    <table class="table cust-datatable dataTable no-footer">
        <thead>
            <th > <a>Date de la déclaration</a></th>
            <th ><a></a></th>
        </thead>                            
        <tbody>
            @foreach($array_file as $file)
            <tr>
              <td>Déclaration de <span style="font-weight: bold">{{ $file['periode'] }}</span></td>
              <td><a href="{{ url($file['path']) }}" >Visionner pdf</a></td>
            </tr>
          @endforeach
        </tbody>
    </table>
  </div>

  
            
  