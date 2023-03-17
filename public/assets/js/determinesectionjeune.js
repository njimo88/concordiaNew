//button zone

function appendenf(sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu){
  if (sexe == 1) {$('#txtsexe').append(" une fille.")}
  else if (sexe == 0) {$('#txtsexe').append(" un garçon.")};

  if (age == 0) {$('#txtage').append(" 6 et 10 ans.")}
  else if (age == 1) {$('#txtage').append(" 10 et 14 ans.")}
  else if (age == 2) {$('#txtage').append(" 14 ans et plus.")};

  if (danse == 0) {$('#txtdanse').append(" aimes la danse.")}
  else if (danse == 1) {$('#txtdanse').append(" n'aimes pas la danse.")};

  if (tw == 0) {$('#txttw').append(" as un sens de l'esprit d'équipe.")}
  else if (tw == 1) {$('#txttw').append(" n'as pas de sens de l'esprit d'équipe.")};

  if (souplesse == 0) {$('#txtsouple').append(" es souple.")}
  else if (souplesse == 1) {$('#txtsouple').append(" n'es pas souple.")};

  if (vertigo == 0) {$('#txtvertigo').append(" as le vertige.")}
  else if (vertigo == 1) {$('#txtvertigo').append(" n'as pas le vertige.")};

  if (accrobate == 0) {$('#txtaccrobate').append(" es accrobate.")}
  else if (accrobate == 1) {$('#txtaccrobate').append(" n'es pas accrobate.")};

  if (pers == 0) {$('#txtpers').append(" es persévérant.")}
  else if (pers == 1) {$('#txtpers').append(" n'es pas persévérant.")};

  if (muscu == 0) {$('#txtmuscu').append(" aimes la musculation.")}
  else if (muscu == 1) {$('#txtmuscu').append(" n'aimes pas la musculation.")};
};

function calculenf(Q1, Q2, Q3, Q4, Q5, Q6, Q7, Q8, sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu){
  var calcul = [
  Math.round(2*parseFloat(Q1[sexe]["GAM"])*parseFloat(Q2[age]["GAM"])*parseFloat(Q4[tw]["GAM"])*(parseFloat(Q3[danse]["GAM"])+parseFloat(Q5[souplesse]["GAM"])+parseFloat(Q6[vertigo]["GAM"])+parseFloat(Q7[accrobate]["GAM"])+parseFloat(Q8[pers]["GAM"]))),
  Math.round(2*parseFloat(Q1[sexe]["GAF"])*parseFloat(Q2[age]["GAF"])*parseFloat(Q4[tw]["GAF"])*(parseFloat(Q3[danse]["GAF"])+parseFloat(Q5[souplesse]["GAF"])+parseFloat(Q6[vertigo]["GAF"])+parseFloat(Q7[accrobate]["GAF"])+parseFloat(Q8[pers]["GAF"]))),
  Math.round(2*parseFloat(Q1[sexe]["GAc"])*parseFloat(Q2[age]["GAc"])*parseFloat(Q4[tw]["GAc"])*(parseFloat(Q3[danse]["GAc"])+parseFloat(Q5[souplesse]["GAc"])+parseFloat(Q6[vertigo]["GAc"])+parseFloat(Q7[accrobate]["GAc"])+parseFloat(Q8[pers]["GAc"]))),
  Math.round(2*parseFloat(Q1[sexe]["GR"])*parseFloat(Q2[age]["GR"])*parseFloat(Q4[tw]["GR"])*(parseFloat(Q3[danse]["GR"])+parseFloat(Q5[souplesse]["GR"])+parseFloat(Q6[vertigo]["GR"])+parseFloat(Q7[accrobate]["GR"])+parseFloat(Q8[pers]["GR"]))),
  Math.round(2*parseFloat(Q1[sexe]["AER"])*parseFloat(Q2[age]["AER"])*parseFloat(Q4[tw]["AER"])*(parseFloat(Q3[danse]["AER"])+parseFloat(Q5[souplesse]["AER"])+parseFloat(Q6[vertigo]["AER"])+parseFloat(Q7[accrobate]["AER"])+parseFloat(Q8[pers]["AER"]))),
  Math.round(2*parseFloat(Q1[sexe]["CrossTraining"])*parseFloat(Q2[age]["CrossTraining"])*parseFloat(Q4[tw]["CrossTraining"])*(parseFloat(Q3[danse]["CrossTraining"])+parseFloat(Q5[souplesse]["CrossTraining"])+parseFloat(Q6[vertigo]["CrossTraining"])+parseFloat(Q7[accrobate]["CrossTraining"])+parseFloat(Q8[pers]["CrossTraining"]))),
  Math.round(2*parseFloat(Q1[sexe]["Parkour"])*parseFloat(Q2[age]["Parkour"])*parseFloat(Q4[tw]["Parkour"])*(parseFloat(Q3[danse]["Parkour"])+parseFloat(Q5[souplesse]["Parkour"])+parseFloat(Q6[vertigo]["Parkour"])+parseFloat(Q7[accrobate]["Parkour"])+parseFloat(Q8[pers]["Parkour"])))
  ];
  return calcul;
};

function diagenfant(calcul){
  //GAM
      $("#GAMH").css({'height': calcul[0]+'%'});
      $("#GAMt").append(calcul[0]+'%');

      if (calcul[0] == 0){
        $("#GAMp").css({'height': (81-calcul[0]) +'%'});
        $("#i0").css({'display': 'auto'});
      }
      else{
        $("#GAMp").css({'height': (91-calcul[0]) +'%'});
        $("#i0").css({'display': 'none'});
      }

  //GAF
      $("#GAFH").css({'height': calcul[1]+'%'});
      $("#GAFt").append(calcul[1]+'%');

      if (calcul[1] == 0){
        $("#GAFp").css({'height': (81-calcul[1]) +'%'});
        $("#i1").css({'display': 'auto'});
      }
      else{
        $("#GAFp").css({'height': (91-calcul[1]) +'%'});
        $("#i1").css({'display': 'none'});
      }


  //GAC
      $("#GACH").css({'height': calcul[2]+'%'});
      $("#GACt").append(calcul[2]+'%');

      if (calcul[2] == 0){
        $("#GACp").css({'height': (81-calcul[2]) +'%'});
        $("#i2").css({'display': 'auto'});
      }
      else{
        $("#GACp").css({'height': (91-calcul[2]) +'%'});
        $("#i2").css({'display': 'none'});
      }

  //GR
      $("#GRH").css({'height': calcul[3]+'%'});
      $("#GRt").append(calcul[3]+'%');

      if (calcul[3] == 0){
        $("#GRp").css({'height': (81-calcul[3]) +'%'});
        $("#i3").css({'display': 'auto'});
      }
      else{
        $("#GRp").css({'height': (91-calcul[3]) +'%'});
        $("#i3").css({'display': 'none'});
      }

  //AER
      $("#AERH").css({'height': calcul[4]+'%'});
      $("#AERt").append(calcul[4]+'%');

          if (calcul[4] == 0){
            $("#AERp").css({'height': (81-calcul[4]) +'%'});
            $("#i4").css({'display': 'auto'});
          }
          else{
            $("#AERp").css({'height': (91-calcul[4]) +'%'});
            $("#i4").css({'display': 'none'});
          }

  //CT
      $("#CTH").css({'height': calcul[5]+'%'});
      $("#CTt").append(calcul[5]+'%');

      if (calcul[5] == 0){
        $("#CTp").css({'height': (81-calcul[5]) +'%'});
        $("#i5").css({'display': 'auto'});
      }
      else{
        $("#CTp").css({'height': (91-calcul[5]) +'%'});
        $("#i5").css({'display': 'none'});
      }

  //Pk

      $("#PKH").css({'height': calcul[6]+'%'});
      $("#PKt").append(calcul[6]+'%');

      if (calcul[6] == 0){
        $("#PKp").css({'height': (81-calcul[6]) +'%'});
        $("#i6").css({'display': 'auto'});
      }
      else{
        $("#PKp").css({'height': (91-calcul[6]) +'%'});
        $("#i6").css({'display': 'none'});
      }
}

$(document).ready(function(){

  // $('input:radio').change(function() {
  //   console.log("chips");
  //     // $('div.selected').removeClass('selected');
  //     // $(this).closest('.box2right').addClass('selected');
  //   });

$('.refresh').on('click', function() {
  location.reload();
});

//Question 1
$('#butQ1n').click(function(){
  if ($('input[name=sexe]:checked').val() == 0 || $('input[name=sexe]:checked').val() == 1 ){
    $('#Q1panel').hide(500,function(){
      $('#QAgepanel').show(500);
      $("window").scrollTop(204);
      $('#wsexe').hide(500);
    });
  }
  else {
    $('#wsexe').show(500);
  }
});

$('#but1renf').click(function(){
    $('#Q1panel').hide(500,function(){
      $('#resultenfant').show(500);

    });
    var sexe = 0;
    console.log("SEXE " + sexe);
    var age = 2;
    console.log("AGE " + age);
    var danse = 0;
    console.log("DANSE " + danse);
    var tw = 0;
    console.log("TW " + tw);
    var souplesse = 1;
    console.log("SOUPL " + souplesse);
    var vertigo = 1;
    console.log("VERT " + vertigo);
    var accrobate = 1;
    console.log("ACCR " + accrobate);
    var pers = 0;
    console.log("PERS " + pers);
    var muscu = 0;
    console.log("MUSCU " + muscu);

    //Résumé réponses
    appendenf(sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu);

    //Calcul des coefficients
    var calcul = calculenf(Q1, Q2, Q3, Q4, Q5, Q6, Q7, Q8, sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu);

    // console.log("CALC " + calcul);

    //compléter les diagrammes
  diagenfant(calcul);


  });

$('#debugbb').click(function(){
    $('#Q1panel').hide(500,function(){
      $('#QBabyres4').show(500);
      $('#wage').hide(500);
      $("window").scrollTop(204);
    });
});


//Question 2
$('#butQ2p').click(function(){
  $('#Q2panel').hide(500,function(){
    $('#QAgepanel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ2n').click(function(){
  if ($('input[name=age]:checked').val() == 0 || $('input[name=age]:checked').val() == 1 || $('input[name=age]:checked').val() == 2 ){
    $('#Q2panel').hide(500,function(){
      $('#Q3panel').show(500);
      $('#wage').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wage').show(500);
  }
});


//Question Age:before
$('#butQAgep').click(function(){
  $('#QAgepanel').hide(500,function(){
    $('#Q1panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQAgen').click(function(){
  if ($('input[name=agetranche]:checked').val() == 0 || $('input[name=agetranche]:checked').val() == 1 || $('input[name=agetranche]:checked').val() == 2 || $('input[name=agetranche]:checked').val() == 3 ){
    $('#QAgepanel').hide(500,function(){
      $('#wage').hide(500);
      if ($('input[name=agetranche]:checked').val() == 1){
        $('#Q2panel').show(500);
        $("window").scrollTop(204);
      }
      else if ($('input[name=agetranche]:checked').val() == 0){
        $('#QBabypanel').show(500);
        $("window").scrollTop(204);
      }
      else if ($('input[name=agetranche]:checked').val() == 2){
        $('#Q2ad').show(500);
        $("window").scrollTop(204);
      }
      else if ($('input[name=agetranche]:checked').val() == 3){
        $('#Q2ad').show(500);
        $("window").scrollTop(204);
      }
      else {
        $('#Q1panel').show(500);
      }

    });
  }
  else {
    $('#wage').show(500);
  }
});


//Question baby
$('#butQbabyp').click(function(){
  $('#QBabypanel').hide(500,function(){
    $('#QAgepanel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQbabyn').click(function(){
  if ($('input[name=agebaby]:checked').val() == 4 || $('input[name=agebaby]:checked').val() == 1 || $('input[name=agebaby]:checked').val() == 2 || $('input[name=agebaby]:checked').val() == 3 || $('input[name=agebaby]:checked').val() == 5){
    ajaxcount();
    $('#QBabypanel').hide(500,function(){
      $('#wage').hide(500);
      if ($('input[name=agebaby]:checked').val() == 1){
        $('#QBabyres1').show(500);
      }
      else if ($('input[name=agebaby]:checked').val() == 2){
        $('#QBabyres2').show(500);
      }
      else if ($('input[name=agebaby]:checked').val() == 3){
        $('#QBabyres3').show(500);
      }
      else if ($('input[name=agebaby]:checked').val() == 4 || $('input[name=agebaby]:checked').val() == 5){
        $('#QBabyres4').show(500);
      }
      else {
        $('#Q1panel').show(500);
      }
      $("window").scrollTop(204);

    });
  }
  else {
    $('#wage').show(500);
  }
});


//Question 3
$('#butQ3p').click(function(){
  $('#Q3panel').hide(500,function(){
    $('#Q2panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ3n').click(function(){
  if ($('input[name=danse]:checked').val() == 0 || $('input[name=danse]:checked').val() == 1){
    $('#Q3panel').hide(500,function(){
      $('#Q4panel').show(500);
      $('#wdanse').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wdanse').show(500);
  }
});


//Question 4
$('#butQ4p').click(function(){
  $('#Q4panel').hide(500,function(){
    $('#Q3panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ4n').click(function(){
  if ($('input[name=tw]:checked').val() == 0 || $('input[name=tw]:checked').val() == 1){
    $('#Q4panel').hide(500,function(){
      $('#Q5panel').show(500);
      $('#wtw').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wtw').show(500);
  }
});


//Question 5
$('#butQ5p').click(function(){
  $('#Q5panel').hide(500,function(){
    $('#Q4panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ5n').click(function(){
  if ($('input[name=souplesse]:checked').val() == 0 || $('input[name=souplesse]:checked').val() == 1){
    $('#Q5panel').hide(500,function(){
      $('#Q6panel').show(500);
      $('#wsouplesse').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wsouplesse').show(500);
  }
});


//Question 6
$('#butQ6p').click(function(){
  $('#Q6panel').hide(500,function(){
    $('#Q5panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ6n').click(function(){
  if ($('input[name=vertigo]:checked').val() == 0 || $('input[name=vertigo]:checked').val() == 1){
    $('#Q6panel').hide(500,function(){
      $('#Q7panel').show(500);
      $('#wvertigo').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wvertigo').show(500);
  }
});


//Question 7
$('#butQ7p').click(function(){
  $('#Q7panel').hide(500,function(){
    $('#Q6panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ7n').click(function(){
  if ($('input[name=accrobate]:checked').val() == 0 || $('input[name=accrobate]:checked').val() == 1){
    $('#Q7panel').hide(500,function(){
      $('#Q8panel').show(500);
      $('#waccrobate').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#waccrobate').show(500);
  }
});


//Question 8
$('#butQ8p').click(function(){
  $('#Q8panel').hide(500,function(){
    $('#Q7panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ8n').click(function(){
  if ($('input[name=pers]:checked').val() == 0 || $('input[name=pers]:checked').val() == 1){
    $('#Q8panel').hide(500,function(){
      $('#Q9panel').show(500);
      $('#wpers').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wpers').show(500);
  }
});


//Question 9
$('#butQ9p').click(function(){
  $('#Q9panel').hide(500,function(){
    $('#Q8panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#butQ9n').click(function(){
  if ($('input[name=muscu]:checked').val() == 0 || $('input[name=muscu]:checked').val() == 1){
    $('#Q9panel').hide(500,function(){
      $('#Q10panel').show(500);
      $('#wmuscu').hide(500);
      $("window").scrollTop(204);
    });
  }
  else {
    $('#wmuscu').show(500);
  }
});


//Question 10
$('#butQ10p').click(function(){
  $('#Q10panel').hide(500,function(){
    $('#Q9panel').show(500);
    $("window").scrollTop(204);
  });
});

$('#but10renf').click(function(){
  if ($('input[name=muscu]:checked').val() == 0 || $('input[name=muscu]:checked').val() == 1){
    ajaxcount();
    $('#Q9panel').hide(500,function(){
      $('#resultenfant').show(500);
      $('#wmuscu').hide(500);
      $("window").scrollTop(204);

    });

    var wrappervar = 1;

    if (wrappervar == 1){

    var sexe = $('input[name=sexe]:checked').val();
    console.log("SEXE " + sexe)
    var age = $('input[name=age]:checked').val();
    console.log("AGE " + age);
    var danse = $('input[name=danse]:checked').val();
    console.log("DANSE " + danse);
    var tw = $('input[name=tw]:checked').val();
    console.log("TW " + tw);
    var souplesse = $('input[name=souplesse]:checked').val();
    console.log("SOUPL " + souplesse);
    var vertigo = $('input[name=vertigo]:checked').val();
    console.log("VERT " + vertigo);
    var accrobate = $('input[name=accrobate]:checked').val();
    console.log("ACCR " + accrobate);
    var pers = $('input[name=pers]:checked').val();
    console.log("PERS " + pers);
    var muscu = $('input[name=muscu]:checked').val();
    console.log("MUSCU " + muscu);
    var yasuo = $('input[name=yasuo]:checked').val();
    console.log("YASUO " + yasuo);

    appendenf(sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu);

  };

  var calcul = calculenf(Q1, Q2, Q3, Q4, Q5, Q6, Q7, Q8, sexe, age, danse, tw, souplesse, vertigo, accrobate, pers, muscu);

  diagenfant(calcul);

  }
  else {
    $('#wyasuo').show(500);
  }

});

});

