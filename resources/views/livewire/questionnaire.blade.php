<div class="questionBox" id="app" x-data="handler()">
    <transition :duration="{ enter: 500, leave: 300 }" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut" mode="out-in">
        <div class="questionContainer" x-on:livewire-load="init">
            <header>
                <h1 class="title is-6">{{ $currentQuestion->intitule }}</h1>
            </header>
            <form wire:submit.prevent="submitAnswer">
                <div class="optionContainer">
                    @foreach($answers as $answer)
                        <div class="option" @click="selectAnswer({{$answer->id}})" :class="{ 'is-selected': selectedAnswer === {{$answer->id}}}">
                            <label for="answer{{ $answer->id }}">
                                @if($answer->image)
                                    <img src="{{ asset($answer->image) }}" alt="{{ $answer->intitule }}">
                                @endif
                                <input type="radio" id="answer{{ $answer->id }}" wire:model="selectedAnswer" x-bind:value="selectedAnswer" value="{{ $answer->id }}">
                                {{ $answer->intitule }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <footer class="questionFooter">
                    <nav class="pagination p-4">
                        <button type="submit" class="button is-active">Suivant</button>
                    </nav>
                </footer>
            </form>
        </div>
    </transition>
</div>



<style>
@import url("https://fonts.googleapis.com/css?family=Montserrat:400,400i,700");
@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700");

main {
    padding-top: 5%;
    padding-bottom: 13%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.button {
    transition: 0.3s;
}

.title {
    font-family: Montserrat, sans-serif;
}

.questionBox {
    max-width: 50rem;
    background: #FAFAFA;
    display: flex;
    border-radius: 0.5rem;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

.questionBox header {
    background-color: #482683;
    padding: 1.5rem;
    text-align: center;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    color: #ECEFF1;
}

.questionBox header h1 {
    font-weight: bold;
    margin-bottom: 1rem;
}

.questionBox .questionContainer .optionContainer {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 30px;
}

.optionContainer img {
    max-width: 50%;
    max-height: 50%;
}

.questionBox .questionContainer .option {
    flex: 1;
    margin: 0 10px;
    text-align: center;
    border-radius: 290486px;
    padding: 4px;
    transition: 0.3s;
    cursor: pointer;
    color: rgba(0, 0, 0, 0.85);
    border: transparent 1px solid;
}

.questionBox .questionContainer .option.is-selected {
    border-color: rgba(0, 0, 0, 0.25);
    background-color: white;
}

.questionBox .questionContainer .questionFooter {
    background-image: linear-gradient(-225deg, #FFFEFF 0%, #D7FFFE 100%);
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    width: 100%;
}

.pagination {
    display: flex;
    justify-content: space-between;
}

.button {
    padding: 0.5rem 1rem;
    border: 1px solid rgba(0, 0, 0, 0.25);
    border-radius: 5rem;
}

.button:hover {
    cursor: pointer;
    background: #ECEFF1;
}

.button.is-active {
    background: #482683;
    color: white;
    border-color: transparent;
}

@media screen and (min-width: 769px) {
    .questionBox .questionContainer {
        display: flex;
        flex-direction: column;
    }
}

@media screen and (max-width: 768px) {
    .questionBox {
        align-items: center;
        justify-content: center;
    }
}

</style>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<script>
 function handler() {
    return {
        selectedAnswer: null,
        init() {
            this.reset(); // Resets the selectedAnswer state when the component is loaded
        },
        selectAnswer(id) {
            this.selectedAnswer = id;
        },
        reset() {
            this.selectedAnswer = null; // Resets to the default state
        }
    }
}

</script>