<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: calc(80vh - 60px); padding-top: 60px; background-color: #f8fafc; font-family: 'Roboto', sans-serif;" x-data="handler()">
    <div style="padding: 1em; max-width: 900px; background: white; border-radius: 8px; box-shadow: 0px 10px 30px -5px rgba(73, 93, 207, 0.1);">
        <h1 style="font-size: 2em; color: #2f365f; margin-bottom: 1em;">{{ $currentQuestion->intitule }}</h1>
        <form wire:submit.prevent="submitAnswer" style="margin-top: 2em;">
            <div style="display: flex; justify-content: space-around; flex-wrap: wrap;" id="answersContainer">
                @foreach($answers as $answer)
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 1em; justify-content: center; flex: 1 0 21%;" class="answerContainer">
                  <label for="answer{{ $answer->id }}"  style="color: #2f365f; text-align: center;">
                      @if($answer->image)
                      <div class="div1" style="margin: 0 auto; display: flex; justify-content: center; height: {{ $imageSize }}px; width: 100%;">
                              <img class="answerImage" x-bind:class="{'active': selectedAnswer === {{$answer->id}}}" src="{{ asset($answer->image) }}" alt="{{ $answer->intitule }}" style="max-width: 100%; max-height: 100%; object-fit: cover; object-position: center; border-radius: 8px; box-shadow: 0px 10px 30px -5px rgba(73, 93, 207, 0.2); margin-bottom: 1em;">
                          </div>
                      @endif
                      <input type="radio" id="answer{{ $answer->id }}" wire:model="selectedAnswer" value="{{ $answer->id }}" x-on:click="selectedAnswer = {{$answer->id}}" style="margin-bottom: 0.5em;">
                      {{ $answer->intitule }}
                  </label>
              </div>
              
                @endforeach
            </div>
            <button  type="submit" style="padding: 1em 2em; font-size: 1em; color: white; background: #2f365f; border: none; border-radius: 50px; cursor: pointer; box-shadow: 0px 10px 30px -5px rgba(73, 93, 207, 0.3); transition: background 0.3s ease-in-out; margin-top: 2em;">Suivant</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<script>
    function handler() {
        return {
            selectedAnswer: null,
        }
    }
</script>