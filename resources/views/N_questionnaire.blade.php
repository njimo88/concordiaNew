@extends('layouts.app')

@section('content')
<main class="main" id="main">
    
      
    <livewire:questionnaire />

    <style>
        @media (max-width: 600px) {
          .answerContainer {
            flex-direction: column;
          }
          .answerImage {
            width: 100%;
          }
          .div1 {
          height: 150px !important;
        }
        }
    
    input[type=radio] {
      display: none;
    }
    .answerImage.active {
      border: 4px solid #2f365f;
      box-sizing: border-box;
    }
    .questionBox .questionContainer .option.is-selected {
    border-width: 2px;
    border-color: #482683; /* Making the border color consistent with the header color for emphasis */
    background-color: #EEEFF3; /* A light greyish background for selected option */
    color: #482683; /* Making the text color consistent with the header color for emphasis */
    transform: scale(1.05); /* Slightly scale up the selected option for added emphasis */
}

    </style>
        
        
        

</main>


@endsection