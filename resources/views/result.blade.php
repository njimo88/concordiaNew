<div>
    <h1>Vos scores :</h1>
    @if (session('scores'))
        @php
            $sortedScores = collect(session('scores'))->sortDesc()->take(5);
        @endphp
        
        @foreach ($sortedScores as $categoryId => $percentage)
    <p>Catégorie ID {{ $categoryId }} : {{ $percentage }}%</p>
@endforeach

    @else
        <p>Aucun score n'a été enregistré.</p>
    @endif
</div>
