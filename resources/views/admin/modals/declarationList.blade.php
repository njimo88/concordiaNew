
@php
  function getFrenchMonth($date) {
    $months = array(
        'January' => 'Janvier',
        'February' => 'Février',
        'March' => 'Mars',
        'April' => 'Avril',
        'May' => 'Mai',
        'June' => 'Juin',
        'July' => 'Juillet',
        'August' => 'Août',
        'September' => 'Septembre',
        'October' => 'Octobre',
        'November' => 'Novembre',
        'December' => 'Décembre'
    );
    $dateObj = DateTime::createFromFormat('F Y', $date);
    $monthNum = $dateObj->format('n');
    $monthName = $months[$dateObj->format('F')];
    return $monthName;
}

@endphp
<div class="table-responsive">
    <table class="table cust-datatable dataTable no-footer">
        <thead>
            <th > <a>Date de la déclaration</a></th>
            <th ><a></a></th>
        </thead>                            
        <tbody>
            @foreach($array_file as $file)
            <tr>
              <td>Déclaration de <span style="font-weight: bold">{{ getFrenchMonth($file['periode']) }} {{ date('Y', strtotime($file['periode'])) }}</span></td>

              <td><a href="{{ url($file['path']) }}" >Visionner pdf</a></td>
            </tr>
          @endforeach
        </tbody>
    </table>
  </div>

  
            
  