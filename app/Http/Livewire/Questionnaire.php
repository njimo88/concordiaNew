<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\Answer;

class Questionnaire extends Component
{
    public $currentQuestion;
public $answers;
public $selectedAnswer;
public $imageSize;

public function mount() {
    $this->currentQuestion = Question::firstWhere('id', 'Q-000');
    $this->answers = Answer::where('question_id', $this->currentQuestion->id)->get();
    $this->imageSize = $this->getImageSize(count($this->answers));
}

public function submitAnswer() {
    $answer = Answer::find($this->selectedAnswer);
    
    if(is_numeric($answer->lien)) {
        session(['category' => $answer->lien]);
        return redirect()->to('/result');
    }

    $this->currentQuestion = Question::firstWhere('id', $answer->lien);
    $this->answers = Answer::where('question_id', $this->currentQuestion->id)->get();
    $this->imageSize = $this->getImageSize(count($this->answers));
}

public function getImageSize($count) {
    switch($count) {
        case 1:
            return 400; 
        case 2:
            return 250; 
        case 3:
            return 250; 
        default:
            return 200; 
    }
}


public function render() {
    return view('livewire.questionnaire');
}

}
