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

    $this->updateScores();

    $this->currentQuestion = Question::firstWhere('id', $answer->lien);
    if (!$this->currentQuestion) {
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

public function updateScores() {
    $answer = Answer::find($this->selectedAnswer);
    $scores = json_decode($answer->scores, true);

    // Récupère les scores et les compteurs d'opérations existants ou initialise des tableaux vides
    $sessionScores = session('scores') ?? [];
    $operationCounts = session('operationCounts') ?? [];

    foreach ($scores as $categoryId => $score) {
        $value = substr($score, 1); // Obtient la partie numérique du score

        if (!isset($sessionScores[$categoryId])) {
            // Initialise le score avec la valeur si l'opération est '*', sinon avec 0
            $sessionScores[$categoryId] = $score[0] == '*' ? $value : 0;
            // Initialise le compteur d'opérations pour cette catégorie
            $operationCounts[$categoryId] = 1;
        } else {
            if ($score[0] == '+') {
                $sessionScores[$categoryId] += $value;
                $operationCounts[$categoryId]++;
            } else if ($score[0] == '*') {
                $sessionScores[$categoryId] *= $value;
                $operationCounts[$categoryId]++;
            }
        }
    }

    // Définit le score maximum possible
    $maxScore = 15;

    foreach ($sessionScores as $categoryId => $score) {
        // Calcule le pourcentage en fonction du score maximum et du nombre d'opérations
        $percentage = $score / ($maxScore * 7) * 100;

        // Arrondit le pourcentage à deux décimales
        $roundedPercentage = round($percentage, 2);

        // Met à jour le pourcentage dans le tableau des scores
        $sessionScores[$categoryId] = $roundedPercentage;
    }

    // Réaffecte les scores et les compteurs d'opérations mis à jour à la session
    session(['scores' => $sessionScores, 'operationCounts' => $operationCounts]);
}




public function render() {
    return view('livewire.questionnaire');
}

}
