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
        $this->calculateFinalScores();
        return redirect()->to('/result');
    }

    $this->updateIntermediateScores();

    $this->currentQuestion = Question::firstWhere('id', $answer->lien);
    if (!$this->currentQuestion) {
        $this->calculateFinalScores();
        return redirect()->to('/result');
    }
    $this->answers = Answer::where('question_id', $this->currentQuestion->id)->get();
    $this->imageSize = $this->getImageSize(count($this->answers));
    session()->save();
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

public function updateIntermediateScores() {
    $answer = Answer::find($this->selectedAnswer);
    $scores = json_decode($answer->scores, true);

    
    $sessionScores = session('scores') ?? [];
    $operationCounts = session('operationCounts') ?? [];
    $sessionMultiplicativeScores = session('multiplicativeScores') ?? [];

    foreach ($scores as $categoryId => $score) {
        $value = substr($score, 1);

        if (!isset($sessionScores[$categoryId])) {
            $sessionScores[$categoryId] = $score[0] == '*' ? 0 : $value;
            $sessionMultiplicativeScores[$categoryId] = $score[0] == '*' ? $value : 1;
            $operationCounts[$categoryId] = $score[0] == '+' ? 1 : 0;
        } else {
            if ($score[0] == '+') {
                $sessionScores[$categoryId] += $value;
                $operationCounts[$categoryId]++;
            } else if ($score[0] == '*') {
                $sessionMultiplicativeScores[$categoryId] *= $value;
            }
        }
    }

    
    session(['scores' => $sessionScores, 'multiplicativeScores' => $sessionMultiplicativeScores, 'operationCounts' => $operationCounts]);
}

public function calculateFinalScores() {
    $sessionScores = session('scores') ?? [];
    $operationCounts = session('operationCounts') ?? [];
    $sessionMultiplicativeScores = session('multiplicativeScores') ?? [];
    $maxScore = 15;

    foreach ($sessionScores as $categoryId => $score) {
        if ($operationCounts[$categoryId] == 0) {
            $percentage = 0;
        } else {
            
            $score *= $sessionMultiplicativeScores[$categoryId];
            $percentage = $score / ($maxScore * $operationCounts[$categoryId]) * 100;
        }

        $roundedPercentage = round($percentage, 2);

        $sessionScores[$categoryId] = $roundedPercentage;
    }

    session(['scores' => $sessionScores]);
}






public function render() {
    return view('livewire.questionnaire');
}

}
