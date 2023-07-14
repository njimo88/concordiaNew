<div>
    <h1>{{ $currentQuestion->intitule }}</h1>
    <form wire:submit.prevent="submitAnswer">
        @foreach($answers as $answer)
            <div>
                <input type="radio" id="answer{{ $answer->id }}" wire:model="selectedAnswer" value="{{ $answer->id }}">
                <label for="answer{{ $answer->id }}">{{ $answer->intitule }}</label>
            </div>
        @endforeach
        <button type="submit">Next</button>
    </form>
</div>
