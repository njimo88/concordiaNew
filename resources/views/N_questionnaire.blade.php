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
    
    </style>
        

</main>

@endsection