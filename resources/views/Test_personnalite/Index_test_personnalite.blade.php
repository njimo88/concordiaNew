@extends('layouts.app')

@section('content')

        @php
        require_once(app_path().'/fonction.php');
        @endphp
        <main id="main" class="main">



        <h4>Test de personnalité et d'orientation</h4>
                    <hr>


                    @if(session('submitted'))




                    <div class="container">

                        

                                @if($tranche_age == 0 )

                        <div class="container">

                        
                                <div class="row">

                                <div id="error-message" class="alert alert-danger" role="alert"> </div>

                                <br>



                                                                <!-- Question age:baby -->
                                                
                                                        <h5>Quel âge as-tu exactement ?</h5>
                                                        <br>
                                                       
                                <form action="{{route('baby_formulaire')}}" method="POST">
                                        @csrf
                                                        Veuillez selectionner votre age.
                                                        </div>
                                                        <br>
                                                        <input name="agebaby" type="hidden" value=NULL>

                                                        <div class="row" id="page_baby_1">
                                                                <div class="col-2 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="agebaby" type="radio" value=1 class="radio-btn">
                                                                                        <br>
                                                                                        <p>1 an</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-2 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="agebaby" type="radio" value=2 class="radio-btn">
                                                                                <br>
                                                                                <p>2 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-2 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="agebaby" type="radio" value=3 class="radio-btn">
                                                                                        <br>
                                                                                        <p>3 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-2 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="agebaby" type="radio" value=4 class="radio-btn">
                                                                                        <br>
                                                                                        <p>4 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-2 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="agebaby" type="radio" value=5 class="radio-btn">
                                                                                        <br>
                                                                                        <p>5 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                
                                                                
                                                        </div>
                                                
                                                        <button type="submit" id="submitBtn" class="next-btn">Suivant</button>

                                </form>

                                </div>
                                       

                                


                        </div>


                                  @elseif($tranche_age == 2 || $tranche_age == 3  )

                                <!-- Questionnaire Entre 25 et 40 ans  // Plus de 40 ans  --> 
                          
                 <div class="tranche_2_3" >

                                <div class="row question-div show" id="div1">

                                <form action="{{route('questionnaire_25_et_40')}}" method="POST">
                                        @csrf

                                        <div id="error-message-calm" class="alert alert-danger" role="alert"> </div>

                                        <br>


                                        <h2>Aimez-vous les sports calmes ?</h2>
                                            <br>
                                            <div id="sport_calm" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="sport_calm" type="radio" value=NULL hidden>
                                            <div class="row">
                                        
                                                <div class="col-6 d-flex justify-content-center">
                                                                <div class="card" style="width: 7rem;">
                                                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/sport_calmes/y.png')}}" alt="Card image cap">
                                                                        <div class="card-body">
                                                                        <input name="sport_calm" type="radio" value=0 class="radio-btn">
                                                                        <br>
                                                                        
                                                                                <p>Oui</p>
                                                                        </div>
                                                                </div>
                                                </div>
                                                <div class="col-6 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/sport_calmes/n.png') }}" alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="sport_calm" type="radio" value=1 class="radio-btn">
                                                                                <br>
                                                                                
                                                                                <p>Non</p>
                                        
                                                                                </div>
                                        
                                                                        </div>
                                                </div>
                                        </div>
                                                <div class="col-2 d-flex justify-content-center">
                                                 
                                                </div>

                                                <div class="col-12 d-flex justify-content-center">
                                                   <button class="next-btn btn-sm mx-auto" type="button" id="next-div1" disabled onclick="nextQuestion()">Suivant</button>
                                                </div>
                                                
                                    </div>

                        
                                    <div class="row question-div" id="div2">

                                        <div id="error-message-calm" class="alert alert-danger" role="alert"> </div>

                                        <br>


                                        
                                        <h2>Aimeriez-vous participer à des activités cardio ?</h2>
                                            <br>
                                            <div id="cardio" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="cardio" type="radio" value=NULL hidden>
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/cardio/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="cardio" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                        <p>Oui</p>
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/cardio/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="cardio" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                        
                                                                </div>
                        
                                                        </div>
                                            </div>
                                            </div>
                                   
                                            <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 <button class="next-btn btn-sm mx-auto" type="button" id="next-div2" disabled onclick="nextQuestion()" >Suivant</button>
                                                   
                                                 </div>
                                        
                                        </div>


                                                
                                        <div class="row question-div"  id="div3">
                                                        
                                        <h2>Avez vous des difficutés à vous déplacer ?</h2>
                                            <br>
                                            <div id="motion" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="motion" type="hidden" value=NULL>
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/physics/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="motion" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/physics/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="motion" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>

                                            </div>
                                    
                                                 <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button class="next-btn btn-sm mx-auto" type="button" id="next-div3" disabled onclick="nextQuestion()" >Suivant</button>

                            
                                                 </div>

                                                    </div>

                                        <div class="row question-div" id="div4">
                                                         
                                <h2>Es-tu souple ?</h2>
                                            <br>
                                            <div id="wsouplesse" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>
                                            <br>
                                            <input name="souplesse" type="hidden" value=NULL>
                                            <div class="row">

                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/souple/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="souplesse" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/souple/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="souplesse" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>

                                            </div>
                                            <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button class="next-btn btn-sm mx-auto" type="button" id="next-div4"  disabled onclick="nextQuestion()">Suivant</button>

                            
                                                 </div>

                                           
                                    </div>
                        
                        
                                        
                                      

                                        <div class="row question-div" id="div5">

                                        
                                <h2>Êtes-vous en surpoids ?</h2>
                                           <br>
                                            <div id="poids" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>
                                            <br>
                                        
                                    <input name="poids" type="hidden" value=NULL>
                         
                                    <div class="row">
                                            
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/poids/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="poids" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top"src="{{ asset('../assets/images/questionnaire/poids/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="poids" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>

                                        </div>
                                        <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button class="next-btn btn-sm mx-auto"  type="button" id="next-div5"  disabled onclick="nextQuestion()" >Suivant</button>
                 
                                                 </div>
                                                        
                                        
                                </div>

                                        <div class="row question-div" id="div6">

                                        <h2>Aimes-tu la musculation ?</h2>
                                                        <br>
                                                        <div id="wmuscu" class="warning">
                                                Veuillez sélectionner une réponse.
                                                </div>  <br>
                                                    
                                                    <input name="muscu" type="hidden" value=NULL>
                        
                                                    <div class="row">
                                                        <div class="col-6 d-flex justify-content-center">
                                                                    <div class="card" style="width: 7rem;">
                                                                            <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/muscu/y.png') }}" alt="Card image cap">
                                                                            <div class="card-body">
                                                                            <input name="muscu" type="radio" value=0 class="radio-btn">
                                                                                <br>
                                                                            
                                                                            
                                                                            <p>Oui</p>
                                                                                
                                                                            </div>
                                                                    </div>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-center">
                                                                    <div class="card" style="width: 7rem;">
                                                                            <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/muscu/n.png') }}" alt="Card image cap">
                                                                            <div class="card-body">
                                                                            <input name="muscu" type="radio" value=0 class="radio-btn">
                                                                            <br>
                                                                            
                                                                            <p>Non</p>
                                                                            </div>
                                                                    </div>
                                                        </div>
                                                        </div>

                                                        
                                                        <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button class="next-btn  btn-sm mx-auto" type="button" id="next-div6"  disabled onclick="nextQuestion()" >Suivant</button>
               
                                                 </div>
                                                       
                                        </div>



                                        <div class="row question-div" id="div7">
                                                
                                        <h2>Aimez-vous bouger ?</h2>
                                            <br>
                                            <div id="bouger" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="bouger" type="hidden" value=NULL>
                        
                                    <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/bouger/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="bouger" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/bouger/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="bouger" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                        </div>
                                        <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button  type="button" class="next-btn btn-sm mx-auto" id="next-div7"  disabled onclick="nextQuestion()" >Suivant</button>
               
                                                 </div>
                                  
                                                        
                                        </div>
                                        <div class="row question-div" id="div8">
                                                
                            <h2>Aimez vous danser ?</h2>
                                            <br>
                                            <div id="danser" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="danser" type="hidden" value=NULL>
                        
                                    <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/danser/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="danser" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/danser/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="danser" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                        </div>
                                    
                                        <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                 <button  class="next-btn btn-sm mx-auto" type="button"  id="next-div8"  disabled onclick="nextQuestion()" >Suivant</button>
               
                                                 </div>
                                                       
                                        </div>


                                        <div class="row question-div" id="div9">
                                        <h5>Souffrez-vous d'un handicap mental ?</h5>
                                            <br>
                                            <div id="mental_handicap" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="mental_handicap" type="hidden" value=NULL>
                                    <div class="row">
                                            
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/handicap/y.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="mental_handicap" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 7rem;">
                                                                <img class="card-img-top"  src="{{ asset('../assets/images/questionnaire/handicap/n.png') }}" alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="mental_handicap" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                            </div>
                                   
                                                 <div class="col-2 d-flex justify-content-center">
                                                 
                                                 </div>
 
                                                 <div class="col-12 d-flex justify-content-center">
                                                 
                                                        <button  class="next-btn btn-sm  mx-auto"   type="button"  id="next-div9"  disabled onclick="nextQuestion()" >Suivant</button>
               
                                                 </div>
                                                
                                                      
                                        </div>
                                        <div class="row question-div" id="div10">

                                        <div class="col-2 d-flex justify-content-center">
                                                <h2>  Voir les résultats </h2>
                                                         <br>
                                        </div>
                                        
                                        <div class="col-12 d-flex justify-content-center">
                                                
                                             <button type="submit" class="btn btn-primary  btn-block "> Valider </button>

                                        </div>
                                                 
                                        </div>
               

                                </form>
                        
                        

                                        

                        </div>

              



                                @else


                                <div class="tranche_3" >

<div class="row question-div2 show" id="page1">

<form action="{{route('questionnaire_6_et_14')}}" method="POST">
        @csrf

        <div id="error-message-calm" class="alert alert-danger" role="alert"> </div>

        <br>

                         <h2>Quel âge as-tu exactement ?</h2>
                                                                <br>
                                                                <div id="wage" class="warning">
                                                                Veuillez selectionner votre age.
                                                                </div>
                                                                <br>
                                                                <input name="age" type="hidden" value=NULL>
                                                                <div class="row">
                                                                <div class="col-4 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/age/6-10.png') }}" alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="age" type="radio" value=0 class="radio-btn">
                                                                                        <br>
                                                                                        <p>6-10 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-4 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/age/10-14.png') }}" alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="age" type="radio" value=1 class="radio-btn">
                                                                                <br>
                                                                                <p>10-14 ans</p>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="col-4 d-flex justify-content-center">
                                                                        <div class="card" style="width: 7rem;">
                                                                                <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/age/14.png') }}" alt="Card image cap">
                                                                                <div class="card-body">
                                                                                <input name="age" type="radio" value=2 class="radio-btn">
                                                                                <br>
                                                                                <p>14 ans et plus</p>
                                                                                </div>
                                                                        </div>
                                                                </div>

                <div class="col-2 d-flex justify-content-center">
                 
                </div>

                <div class="col-12 d-flex justify-content-center">
                   <button class="next-btn btn-sm mx-auto" type="button" id="next-page1" disabled onclick="nextQuestion2()">Suivant</button>
                </div>
                
    </div>

</div>

<div class="row question-div2" id="page2">


	
<h2>Aimes-tu la danse ?</h2>
                    <br>
                    <div id="wdanse" class="warning">
                        Veuillez sélectionner une réponse.
                    </div>
                    <br>
                    <input name="danse" type="radio" value=NULL hidden>
                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/danser/y.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="danse" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                                <p>Oui</p>
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/danser/n.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="danse" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>

                                        </div>

                                </div>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                 
                 </div>
 
                 <div class="col-12 d-flex justify-content-center">
                    <button class="next-btn btn-sm mx-auto" type="button" id="next-page2" disabled onclick="nextQuestion2()">Suivant</button>
                 </div>
            </div>


              
                
    </div>








<div class="row question-div2" id="page3">





<h2>As-tu un esprit d'équipe ?</h2>
                    <br>
                    <div id="wdanse" class="warning">
                        Veuillez sélectionner une réponse.
                    </div>
                    <br>
                    <input name="tw" type="hidden" value=NULL>
                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/team_spirit/y.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="tw" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/team_spirit/n.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="tw" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>

                    <div class="col-2 d-flex justify-content-center">
                 
                 </div>
 
                 <div class="col-12 d-flex justify-content-center">
                    <button class="next-btn btn-sm mx-auto" type="button" id="next-page3" disabled onclick="nextQuestion2()">Suivant</button>
                 </div>

            </div>


                
</div>

<div class="row question-div2" id="page4">

<h2>Es-tu souple ?</h2>
                    <br>
                    <div id="wsouplesse" class="warning">
              Veuillez sélectionner une réponse.
            </div>
                    <br>
                    <input name="souplesse" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/souple/y.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="souplesse" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/souple/n.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="souplesse" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>

                    <div class="col-2 d-flex justify-content-center">
                 
                 </div>
 
                 <div class="col-12 d-flex justify-content-center">
                    <button class="next-btn btn-sm mx-auto" type="button" id="next-page4" disabled onclick="nextQuestion2()">Suivant</button>
                 </div>
            </div>
                
</div>

<div class="row question-div2" id="page5">

<h2>As-tu le vertige ?</h2>
                    <br>
                    <div id="wvertigo" class="warning">
              Veuillez sélectionner une réponse.
            </div>
                    <br>
                
            <input name="vertigo" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/vertige/y.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="vertigo" type="radio" value=0 class="radio-btn">
                                            <br>
                                          
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 7rem;">
                                        <img class="card-img-top" src="{{ asset('../assets/images/questionnaire/vertige/n.png') }}" alt="Card image cap">
                                        <div class="card-body">
                                        <input name="vertigo" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>

                    <div class="col-2 d-flex justify-content-center">
                 
                 </div>
 
                 <div class="col-12 d-flex justify-content-center">
                    <button class="next-btn btn-sm mx-auto" type="button" id="next-page5" disabled onclick="nextQuestion2()">Suivant</button>
                 </div>
            </div>










                
</div>


<div class="row question-div2" id="page6">

NKP 3
                
</div>

<div class="row question-div2" id="page7">

NKP 3
                
</div>







</form>

</div>

        



                                @endif


                    </div>

                    @else

        <div   class="container border border-dark">
        
        <h3 style="text-align: center;">Remplissez d'abord ce formulaire </h3>

        <div class="row">

        <div class="col-md-2"></div>

        <div class="col-md-8">

        <form action="{{ route('include_slider_questionnaire')}}" method="POST">



        @csrf

        <div class="mb-3 ">
        <div class="row d-flex justify-content-center">
        <div class="col-md-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="sexe" id="radio1" value=0 required>
        <label class="form-check-label" for="radio1">
        Fille
        </label>
        </div>
        </div>
        <div class="col-md-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="sexe" id="radio2" value=0 required>
        <label class="form-check-label" for="radio2">
        Garçon
        </label>
        </div>
        </div>
        </div>


        <div class="mb-3 ">
        <div class="row">
        <div class="col-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="tranche_age" id="radio3" value=0 required>
        <label class="form-check-label" for="radio1">
        Moins de 6 ans
        </label>
        </div>
        </div>
        <div class="col-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="tranche_age" id="radio4" value=1 required>
        <label class="form-check-label" for="radio2">
        Entre 6 et 25 ans
        </label>
        </div>
        </div>
        <div class="col-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="tranche_age" id="radio5" value=2 required>
        <label class="form-check-label" for="radio1">
        Entre 25 et 40 ans
        </label>
        </div>
        </div>
        <div class="col-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="tranche_age" id="radio6" value=3 required>
        <label class="form-check-label" for="radio2">
        Plus de 40 ans
        </label>
        </div>
        </div>
        </div>

        </div>


        <button type="submit" class="btn btn-primary">Valider</button>
        </form>
        <br>


        </div>
        <div class="col-md-2"></div>

        </div>


        </div>


                    @endif


</main>




<style>

#error-message {
  display: none;
}

#error-message-calm{
        display: none;  
}

.question-div {
  display: none;
}

.question-div2 {
  display: none;
}

.show{
  display: block;
}

.warning{
        color: red;
}


</style>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

        // formulaire adulte 

const radioButtons = document.querySelectorAll('input[type="radio"]');

const div1RadioButtons = document.querySelectorAll('#div1 input[type="radio"]');
const nextButton = document.querySelector('#next-div1');

div1RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton.removeAttribute('disabled');

});

});



const nextButton2 = document.querySelector('#next-div2');

const div2RadioButtons = document.querySelectorAll('#div2 input[type="radio"]');


div2RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton2.removeAttribute('disabled');

});

});

const nextButton3 = document.querySelector('#next-div3');

const div3RadioButtons = document.querySelectorAll('#div3 input[type="radio"]');


div3RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton3.removeAttribute('disabled');

});

});

const nextButton4 = document.querySelector('#next-div4');

const div4RadioButtons = document.querySelectorAll('#div4 input[type="radio"]');


div4RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton4.removeAttribute('disabled');

});

});


const nextButton5 = document.querySelector('#next-div5');

const div5RadioButtons = document.querySelectorAll('#div5 input[type="radio"]');


div5RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton5.removeAttribute('disabled');

});

});

const nextButton6 = document.querySelector('#next-div6');

const div6RadioButtons = document.querySelectorAll('#div6 input[type="radio"]');


div6RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton6.removeAttribute('disabled');

});

});

const nextButton7 = document.querySelector('#next-div7');

const div7RadioButtons = document.querySelectorAll('#div7 input[type="radio"]');


div7RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton7.removeAttribute('disabled');

});

});

const nextButton8 = document.querySelector('#next-div8');

const div8RadioButtons = document.querySelectorAll('#div8 input[type="radio"]');

div8RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton8.removeAttribute('disabled');

});

});

const nextButton9 = document.querySelector('#next-div9');

const div9RadioButtons = document.querySelectorAll('#div9 input[type="radio"]');


div9RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextButton9.removeAttribute('disabled');

});

});

let currentQuestion = 1;
const questionnaireDivs = document.getElementsByClassName("question-div");

function nextQuestion() {
  if (currentQuestion < questionnaireDivs.length) {
        
    questionnaireDivs[currentQuestion - 1].classList.remove("show");
    currentQuestion++;
    questionnaireDivs[currentQuestion - 1].classList.add("show");
  }
}

// formulaire young

const page1RadioButtons = document.querySelectorAll('#page1 input[type="radio"]');
const page2RadioButtons = document.querySelectorAll('#page2 input[type="radio"]');
const page3RadioButtons = document.querySelectorAll('#page3 input[type="radio"]');
const page4RadioButtons = document.querySelectorAll('#page4 input[type="radio"]');
const page5RadioButtons = document.querySelectorAll('#page5 input[type="radio"]');

const nextpage = document.querySelector('#next-page1');
const nextpage2 = document.querySelector('#next-page2');
const nextpage3 = document.querySelector('#next-page3');
const nextpage4 = document.querySelector('#next-page4');
const nextpage5 = document.querySelector('#next-page5');

page1RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextpage.removeAttribute('disabled');
  
});

});

page2RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextpage2.removeAttribute('disabled');
  
});

});

page3RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextpage3.removeAttribute('disabled');
  
});

});

page4RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextpage4.removeAttribute('disabled');
  
});

});

page5RadioButtons.forEach(radioButton => {

radioButton.addEventListener('change', () => {
  nextpage5.removeAttribute('disabled');
  
});

});






let currentQuestion2 = 1;
const questionnaireDivs2 = document.getElementsByClassName("question-div2");

function nextQuestion2() {
       
  if (currentQuestion2 < questionnaireDivs2.length) {
        
    questionnaireDivs2[currentQuestion2 - 1].classList.remove("show");
    currentQuestion2++;
    questionnaireDivs2[currentQuestion2 - 1].classList.add("show");
  }
}






const form = document.getElementById('myForm');
    const submitBtn = document.getElementById('submitBtn');

    submitBtn.addEventListener('click', function(event) {
        const radioButtons = document.querySelectorAll('input[type=radio][name=agebaby]:checked');
        if (radioButtons.length === 0) {
            event.preventDefault();
            document.getElementById('error-message').innerHTML = 'Veuillez selectionner votre age ?';
            document.getElementById("error-message").style.display = "block";
            event.preventDefault();
        }

    });






  







</script>



@endsection


	

	
