@extends('layouts.app')

@section('content')
<main class="main" id="main">
    
      
    <livewire:questionnaire />

    <script>
        function handler() {
            return {
                selectedAnswer: null,
            }
        }
    </script>
    
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
    
    </style>
        
        
        

</main>


@endsection