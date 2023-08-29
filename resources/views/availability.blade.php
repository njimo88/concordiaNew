@if ($data->stock_actuel > $data->alert_stock)
    @if ($data->type_article == 0)
        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Places Disponibles</span>
    @elseif ($data->type_article == 1)
        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Places Disponibles</span>
    @elseif ($data->type_article == 2)
        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Disponibles</span>
    @endif
@elseif ($data->stock_actuel > 0 && $data->stock_actuel <= $data->alert_stock)
    @if ($data->type_article == 0)
        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} disponibilités</span>
    @elseif ($data->type_article == 1)
        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} places</span>
    @elseif ($data->type_article == 2)
        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} disponibilités</span>
    @endif
@elseif ($data->stock_actuel <= 0)
    @if ($data->type_article == 0)
        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
    @elseif ($data->type_article == 1)
        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Séance complète</span>
    @elseif ($data->type_article == 2)
        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
    @endif
@endif
