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

                        

                                @if ($tranche_age == 0 )

               

                                
                                <div class="my-divs" id="div1">
                               


       <!-- Question age:baby -->
                
       <h5>Quel âge as-tu exactement ?</h5>
                        <br>
                        <div id="wage" class="warning">
                        <form action="{{route('baby_formulaire')}}" method="POST">
                            Veuillez selectionner votre age.
                        </div>
                        <br>
                        <input name="agebaby" type="hidden" value=NULL>
                        <div class="row">
                                <div class="col-4 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="agebaby" type="radio" value=1 class="radio-btn">
                                                        <br>
                                                        <p>1 an</p>
                                                    </div>
                                            </div>
                                </div>
                                <div class="col-4 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="agebaby" type="radio" value=2 class="radio-btn">
                                                    <br>
                                                    <p>2 ans</p>
                                                    </div>
                                            </div>
                                </div>
                                <div class="col-4 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="agebaby" type="radio" value=3 class="radio-btn">
                                                        <br>
                                                        <p>3 ans</p>
                                                    </div>
                                            </div>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="agebaby" type="radio" value=4 class="radio-btn">
                                                        <br>
                                                        <p>4 ans</p>
                                                    </div>
                                            </div>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="agebaby" type="radio" value=5 class="radio-btn">
                                                        <br>
                                                        <p>5 ans</p>
                                                    </div>
                                            </div>
                                </div>
                                
                        </div>
                        
                        <button type="button" class="next-btn">Next</button>

                        
                                    </div>

     <div class="my-divs" id="div2">
   
        @csrf
        <h2>Voir les résultats</h2>
            <p></p>
            <input type="submit" class="btn btn-primary" value="Valider">

            
	</div>

    </form>                
          @elseif ($tranche_age == 1 )
                                <!-- Questionnaire pour les Entre 6 et 25 ans -->
                                <div class="my-divs" id="div1">
		
        
        
      <h2>Quel âge as-tu exactement ?</h2>
                        <br>
                        <div id="wage" class="warning">
                            Veuillez selectionner votre age.
                        </div>
                        <br>
                        <input name="age" type="hidden" value=NULL>
                        <div class="row">
                        <div class="col-4 d-flex justify-content-center">
                                    <div class="card" style="width: 18rem;">
                                            <img class="card-img-top" src="..." alt="Card image cap">
                                            <div class="card-body">
                                            <input name="age" type="radio" value=0 class="radio-btn">
                                                <br>
                                                <p>6-10 ans</p>
                                            </div>
                                    </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center">
                                    <div class="card" style="width: 18rem;">
                                            <img class="card-img-top" src="..." alt="Card image cap">
                                            <div class="card-body">
                                            <input name="age" type="radio" value=1 class="radio-btn">
                                            <br>
                                            <p>10-14 ans</p>
                                            </div>
                                    </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center">
                                    <div class="card" style="width: 18rem;">
                                            <img class="card-img-top" src="..." alt="Card image cap">
                                            <div class="card-body">
                                            <input name="age" type="radio" value=2 class="radio-btn">
                                            <br>
                                            <p>14 ans et plus</p>
                                            </div>
                                    </div>
                        </div>

                      
                        </div>


	
		<button class="next-btn">Next</button>
	</div>

	<div class="my-divs" id="div2">
		
		
        <h2>Aimes-tu la danse ?</h2>
                    <br>
                    <div id="wdanse" class="warning">
                        Veuillez sélectionner une réponse.
                    </div>
                    <br>
                    <input name="danse" type="radio" value=NULL hidden>
                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="danse" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                                <p>Oui</p>
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="danse" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>

                                        </div>

                                </div>
                    </div>
            </div>




		<button class="next-btn">Next</button>
	</div>

	<div class="my-divs" id="div3">
		
        <h2>As-tu un esprit d'équipe ?</h2>
                    <br>
                    <div id="wdanse" class="warning">
                        Veuillez sélectionner une réponse.
                    </div>
                    <br>
                    <input name="tw" type="hidden" value=NULL>
                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="tw" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="tw" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>
            </div>








		<button class="next-btn">Next</button>
	</div>

    
	<div class="my-divs" id="div4">
	
        <h2>Es-tu souple ?</h2>
                    <br>
                    <div id="wsouplesse" class="warning">
              Veuillez sélectionner une réponse.
            </div>
                    <br>
                    <input name="souplesse" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="souplesse" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="souplesse" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>
            </div>


		<button class="next-btn">Next</button>
	</div>
    


	<div class="my-divs" id="div5">
		
        <h2>As-tu le vertige ?</h2>
                    <br>
                    <div id="wvertigo" class="warning">
              Veuillez sélectionner une réponse.
            </div>
                    <br>
                
            <input name="vertigo" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="vertigo" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="vertigo" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>
            </div>








		<button class="next-btn">Next</button>
	</div>


    <div class="my-divs" id="div6">
		
	 
        <h2>Es-tu acrobate ?</h2>
                    <br>
                    <div id="waccrobate" class="warning">
              Veuillez sélectionner une réponse.
            </div>
                    <br>
                
                    <input name="accrobate" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="accrobate" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="accrobate" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>
            </div>
    
    


		<button class="next-btn">Next</button>
	</div>
    <div class="my-divs" id="div7">
		
        <h2>Es-tu persévérant ?</h2>
                    <br>
                    <div id="wpers" class="warning">
              Veuillez sélectionner une réponse.
            </div>  <br>
                
            <input name="pers" type="hidden" value=NULL>

                    <div class="row">
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="pers" type="radio" value=0 class="radio-btn">
                                            <br>
                                           
                                           
                                           <p>Oui</p>
                                            
                                        </div>
                                </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                        <img class="card-img-top" src="..." alt="Card image cap">
                                        <div class="card-body">
                                        <input name="pers" type="radio" value=1 class="radio-btn">
                                        <br>
                                           
                                           <p>Non</p>
                                        </div>
                                </div>
                    </div>
            </div>
    
    
    

        
		<button class="next-btn">Next</button>
	</div>
    <div class="my-divs" id="div8">
	
        <h2>Aimes-tu la musculation ?</h2>
                                <br>
                                <div id="wmuscu" class="warning">
                        Veuillez sélectionner une réponse.
                        </div>  <br>
                            
                            <input name="muscu" type="hidden" value=NULL>

                                <div class="row">
                                <div class="col-6 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="muscu" type="radio" value=0 class="radio-btn">
                                                        <br>
                                                    
                                                    
                                                    <p>Oui</p>
                                                        
                                                    </div>
                                            </div>
                                </div>
                                <div class="col-6 d-flex justify-content-center">
                                            <div class="card" style="width: 18rem;">
                                                    <img class="card-img-top" src="..." alt="Card image cap">
                                                    <div class="card-body">
                                                    <input name="muscu" type="radio" value=0 class="radio-btn">
                                                    <br>
                                                    
                                                    <p>Non</p>
                                                    </div>
                                            </div>
                                </div>
                            </div>
    


		<button class="next-btn">Next</button>
	</div>


    <div class="my-divs" id="div8">
		<h2>Voir les résultats</h2>
		<p></p>
		<button >Valider</button>
	</div>
              
         
              
               
                            
                                    @elseif($tranche_age == 2 ||$tranche_age == 3  )

                                <!-- Questionnaire Entre 25 et 40 ans  // Plus de 40 ans  --> 
                          
                                                
                                <div class="my-divs" id="div1">
		
                                <h2>Aimez-vous les sports calmes ?</h2>
                                            <br>
                                            <div id="sport_calm" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="sport_calm" type="radio" value=NULL hidden>
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="sport_calm" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                        <p>Oui</p>
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="sport_calm" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                        
                                                                </div>
                        
                                                        </div>
                                            </div>
                                    </div>
                        
                        
                        
                        
                                <button class="next-btn">Next</button>
                              
                            </div>
                        
                            <div class="my-divs" id="div2">
                               
                                
                                <h2>Aimeriez-vous participer à des activités cardio ?</h2>
                                            <br>
                                            <div id="cardio" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="cardio" type="radio" value=NULL hidden>
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="cardio" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                        <p>Oui</p>
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="cardio" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                        
                                                                </div>
                        
                                                        </div>
                                            </div>
                                    </div>
                        
                        
                        
                        
                                <button class="next-btn">Next</button>
                            </div>
                        
                            <div class="my-divs" id="div3">
                               
                                <h2>Avez vous des difficutés à vous déplacer ?</h2>
                                            <br>
                                            <div id="motion" class="warning">
                                                Veuillez sélectionner une réponse.
                                            </div>
                                            <br>
                                            <input name="motion" type="hidden" value=NULL>
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="motion" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="motion" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                        
                                <button class="next-btn">Next</button>
                            </div>
                        
                            
                            <div class="my-divs" id="div4">
                                
                                <h2>Es-tu souple ?</h2>
                                            <br>
                                            <div id="wsouplesse" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>
                                            <br>
                                            <input name="souplesse" type="hidden" value=NULL>
                        
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="souplesse" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="souplesse" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                        
                        
                                <button class="next-btn">Next</button>
                            </div>
                            
                        
                        
                            <div class="my-divs" id="div5">
                               
                                <h2>Êtes-vous en surpoids ?</h2>
                                           <br>
                                            <div id="poids" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>
                                            <br>
                                        
                                    <input name="poids" type="hidden" value=NULL>
                        
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="poids" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="poids" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                        
                        
                        
                        
                        
                        
                        
                        
                                <button class="next-btn">Next</button>
                            </div>
                        
                        
                            <div class="my-divs" id="div6">
                              
                                <h2>Aimes-tu la musculation ?</h2>
                                                        <br>
                                                        <div id="wmuscu" class="warning">
                                                Veuillez sélectionner une réponse.
                                                </div>  <br>
                                                    
                                                    <input name="muscu" type="hidden" value=NULL>
                        
                                                        <div class="row">
                                                        <div class="col-6 d-flex justify-content-center">
                                                                    <div class="card" style="width: 18rem;">
                                                                            <img class="card-img-top" src="..." alt="Card image cap">
                                                                            <div class="card-body">
                                                                            <input name="muscu" type="radio" value=0 class="radio-btn">
                                                                                <br>
                                                                            
                                                                            
                                                                            <p>Oui</p>
                                                                                
                                                                            </div>
                                                                    </div>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-center">
                                                                    <div class="card" style="width: 18rem;">
                                                                            <img class="card-img-top" src="..." alt="Card image cap">
                                                                            <div class="card-body">
                                                                            <input name="muscu" type="radio" value=0 class="radio-btn">
                                                                            <br>
                                                                            
                                                                            <p>Non</p>
                                                                            </div>
                                                                    </div>
                                                        </div>
                                                    </div>
                            
                            
                        
                        
                        
                        
                        
                        
                        
                                <button class="next-btn">Next</button>
                            
                            </div>
                            <div class="my-divs" id="div7">
                               
                                <h2>Aimez-vous bouger ?</h2>
                                            <br>
                                            <div id="bouger" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="bouger" type="hidden" value=NULL>
                        
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="bouger" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="bouger" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                            
                            
                            
                        
                                
                                <button class="next-btn">Next</button>
                            </div>
                            <div class="my-divs" id="div8">
                        
                        
                            <h2>Aimez vous danser ?</h2>
                                            <br>
                                            <div id="danser" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="danser" type="hidden" value=NULL>
                        
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="danser" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="danser" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                            
                            
                            
                        
                                
                                <button class="next-btn">Next</button>
                            
                            </div>
                        
                        
                            <div class="my-divs" id="div9">
                        
                            <h5>Souffrez-vous d'un handicap mental ?</h5>
                                            <br>
                                            <div id="mental_handicap" class="warning">
                                      Veuillez sélectionner une réponse.
                                    </div>  <br>
                                        
                                    <input name="mental_handicap" type="hidden" value=NULL>
                        
                                            <div class="row">
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="mental_handicap" type="radio" value=0 class="radio-btn">
                                                                    <br>
                                                                   
                                                                   
                                                                   <p>Oui</p>
                                                                    
                                                                </div>
                                                        </div>
                                            </div>
                                            <div class="col-6 d-flex justify-content-center">
                                                        <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top" src="..." alt="Card image cap">
                                                                <div class="card-body">
                                                                <input name="mental_handicap" type="radio" value=1 class="radio-btn">
                                                                <br>
                                                                   
                                                                   <p>Non</p>
                                                                </div>
                                                        </div>
                                            </div>
                                    </div>
                            
                                
                                <button class="next-btn">Next</button>
                            
                            </div>
                        
                        
                            <div class="my-divs" id="div8">
                                <h2>Voir les résultats</h2>
                                <p></p>
                                <button >Valider</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>

.my-divs {
  display: none; /* Hide all divs by default */
 
}

element{
    display: none;
}


.maindiv{
    width: 4380px; /* set your preferred width */
  margin: 0 auto; /* set margin-left and margin-right to auto */
  margin-top: 117px;
  background-color: white;

}
.btnextp{
    width: 500px; /* set your preferred width */
  margin: 0 auto; /* set margin-left and margin-right to auto */
  margin-top: 117px;

}
.container {
  width: 900px; /* set your preferred width */
  margin: 0 auto; /* set margin-left and margin-right to auto */
  margin-top: 117px;
  
}

.next-btn {
    display: block;
    margin: 0 auto;
    width: 200px;
    height: 50px;
    font-size: 1.2em;
  }

.my-divs:first-of-type {
			display: block;
		}


.warning{
    display:none;
}



</style>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var currentDiv = 1;
			$('#div' + currentDiv).show();

			// Add click event listener to the "Next" button
			$('.next-btn').click(function() {

                // Hide the current div and show the next one
    $(this).parent().hide().next().show();
				
  


				// Update the current div index
				currentDiv++;
				if (currentDiv > $('.my-divs').length) {
					currentDiv = 1;
				}
			});
		});
	
 




</script>


@endsection


	

	
