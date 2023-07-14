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

    public function mount() {
        $this->currentQuestion = Question::firstWhere('id', 'Q-000');
        $this->answers = Answer::where('question_id', $this->currentQuestion->id)->get();
    }

    public function submitAnswer() {
        $answer = Answer::find($this->selectedAnswer);
        
        if(is_numeric($answer->lien)) {
            // Ceci est une catégorie
            session(['category' => $answer->lien]);
            return redirect()->to('/result');
        }

        $this->currentQuestion = Question::firstWhere('id', $answer->lien);
        $this->answers = Answer::where('question_id', $this->currentQuestion->id)->get();
    }

    public function render()
    {
        return view('livewire.questionnaire');
    }
}
