@php
    $aff_heure = 0 ;
    if (count($Data_lesson['start_date'])>1) {  
        foreach($Data_lesson['start_date'] as $dt){
            $date = new DateTime($dt);
            echo "<p style='align-self: flex-start !important; font-weight:bold;'>" ; 
            echo fetcchDayy($dt)." de ".$date->format('G:i'); 
            $dt1 = $Data_lesson['end_date'][$aff_heure];
            $date = new DateTime($dt1);
            echo " - ".$date->format('G:i');
            echo "</p>" ; 
            $aff_heure++;
        }
    } else {
        foreach($Data_lesson['start_date'] as $dt){
            $date = new DateTime($dt);
            echo "<p style='align-self: flex-start !important; font-weight:bold;'>" ; 
            echo fetcchDayy($dt)." de ".$date->format('G:i') ;
            foreach($Data_lesson['end_date'] as $dt){
                $date = new DateTime($dt);
                echo " ".$date->format('G:i');
                echo "</p>" ; 
            };
        };
    }
    $counter = 1;
    foreach($rooms as $room){
        foreach($Data_lesson['room'] as $r){
            if($r == $room->id_room){
                echo"</p>";
                echo " <b style='align-self: flex-start !important;'>lieu ";
                if(count($Data_lesson['room']) > 1) {
                    echo $counter;
                    $counter++;
                }
                echo ": </b>" ;
                echo "<a class='a' style='font-size: small' href='$room->map' target='_blank'>" . $room->name .  "</a>";
            }
        }
    }
@endphp
