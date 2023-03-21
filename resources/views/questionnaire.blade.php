@extends('layouts.app')

@section('content')
<script src="{{ asset('assets\js\determinesectionjeune.js') }}"></script>

<script>
    var Q1 = <?php echo json_encode($scoreQ1) ?>;
    var Q2 = <?php echo json_encode($scoreQ2) ?>;
    var Q3 = <?php echo json_encode($scoreQ3) ?>;
    var Q4 = <?php echo json_encode($scoreQ4) ?>;
    var Q5 = <?php echo json_encode($scoreQ5) ?>;
    var Q6 = <?php echo json_encode($scoreQ6) ?>;
    var Q7 = <?php echo json_encode($scoreQ7) ?>;
    var Q8 = <?php echo json_encode($scoreQ8) ?>;
    var Q9 = <?php echo json_encode($scoreQ9) ?>;
    var Q10 = <?php echo json_encode($scoreQ10) ?>;
    var result;
    var sexe;
    var test;
    var compte;
</script>
    @foreach($quadall as $quest2)
        
            $basename = "scoread{$quest2->id_q}";
            $data = DB::select("SELECT * FROM sectionadultesreponses WHERE id_q = ?", [$quest2->id_q]);
            $encodedData = json_encode($data);
        
        <script>
            var QAD $quest2->id_q = $encodedData ;
        </script>
    @endforeach

  
    function ajaxcount() {
      $.ajax({
        type: "POST",
        url: '/determinesection/count',
        data: {},
        datatype: 'html',
        success: function() {
          console.log('Yes')
        },
        error: function() {
          console.log('No')
        },
      });
    };
  </script>
    function calculad(QAD1, sexe, <?php foreach ($quad as $liste) : echo "QAD" . $liste->id_q . ',repad' . $liste->id_q . ',';
                                  endforeach; ?>) {
      result = {
        <?php foreach ($adcat as $calc) : ?> "<?= $calc->nom ?>": Math.round(parseFloat(QAD1[sexe]['<?= $calc->nom ?>']) * parseFloat(QAD10[repad10]['<?= $calc->nom ?>']) * parseFloat(QAD4[repad4]['<?= $calc->nom ?>']) * (parseFloat(QAD2[repad2]['<?= $calc->nom ?>']) + parseFloat(QAD3[repad3]['<?= $calc->nom ?>']) + parseFloat(QAD5[repad5]['<?= $calc->nom ?>']) + parseFloat(QAD6[repad6]['<?= $calc->nom ?>']) + parseFloat(QAD7[repad7]['<?= $calc->nom ?>']) + parseFloat(QAD8[repad8]['<?= $calc->nom ?>']) + parseFloat(QAD9[repad9]['<?= $calc->nom ?>']))),
        <?php endforeach; ?>
      };
      return result;
    }
  </script>

  <script id="graph">
    function graphad(result) {
      <?php foreach ($adcat as $graph2) : ?>
        $("#barre<?= $graph2->id ?>").css({
          'height': result["<?= $graph2->nom ?>"]  + '%'
        });
        if (result["<?= $graph2->nom ?>"] == 0) {
          $("#in<?= $graph2->id ?>").css({
            'display': 'auto'
          });
          $("#place<?= $graph2->id ?>").css({
            'height': (70) + '%'
          });
        } else {
          $("#in<?= $graph2->id ?>").css({
            'display': 'none'
          });
          $("#place<?= $graph2->id ?>").css({
            'height': (100 - result["<?= $graph2->nom ?>"]) + '%'
          });
        }
      <?php endforeach; ?>
    }
  
    function sortarray() {
      test = [<?php foreach ($adcat as $calc2) : ?>
          Math.round(parseFloat(QAD1[sexe]['<?= $calc2->nom ?>']) * parseFloat(QAD10[repad10]['<?= $calc2->nom ?>']) * parseFloat(QAD4[repad4]['<?= $calc2->nom ?>']) * (parseFloat(QAD2[repad2]['<?= $calc2->nom ?>']) + parseFloat(QAD3[repad3]['<?= $calc2->nom ?>']) + parseFloat(QAD5[repad5]['<?= $calc2->nom ?>']) + parseFloat(QAD6[repad6]['<?= $calc2->nom ?>']) + parseFloat(QAD7[repad7]['<?= $calc2->nom ?>']) + parseFloat(QAD8[repad8]['<?= $calc2->nom ?>']) + parseFloat(QAD9[repad9]['<?= $calc2->nom ?>']))),
        <?php endforeach; ?>
      ]
      test.sort(function(a, b) {
        return b - a;
      });
      return test;
    }
  
    function altergraph(compte, result) {
      <?php foreach ($adcat as $tri) : ?>
        if (parseFloat(result['<?= $tri->nom ?>']) == parseFloat(test[0]) || parseFloat(result['<?= $tri->nom ?>']) == parseFloat(test[1]) || parseFloat(result['<?= $tri->nom ?>']) == parseFloat(test[2]) || parseFloat(result['<?= $tri->nom ?>']) == parseFloat(test[3]) || parseFloat(result['<?= $tri->nom ?>']) == parseFloat(test[4])) {} else {
          compte -= 1;
          $("#adultbar<?= $tri->id ?>").css({
            'display': 'none'
          });
          $("#img<?= $tri->id ?>").css({
            'display': 'none'
          });
        }
        result['<?= $tri->nom ?>'] = (result['<?= $tri->nom ?>'] / test[0]) * 100;
        $("#pourentad<?= $tri->id ?>").append((Math.round(result['<?= $tri->nom ?>']) + '%'));
      <?php endforeach; ?>
      $(".adulte").css({
        'width': (1 / compte) * 100 + '%'
      });
      $('#graphadfull').addClass('medium-7');
      return compte;
    }
    $(document).ready(function() {
      $('#but1radu').click(function() {
        ajaxcount();
        $('#Q1panel').hide(500, function() {
          $('#Qrepad').show(500);
          sexe = Math.random() < 0.5;
          if (sexe == true) {
            sexe = 0;
            var sexetxt = "Homme";
          } else {
            sexe = 1;
            var sexetxt = "Femme";
          }
          $('#txtad').append("SEXE : " + sexetxt + '<br>');
          <?php foreach ($quad as $quest) : ?>
            repad<?php echo $quest->id_q ?> = Math.random() < 0.5;
            if (repad<?php echo $quest->id_q ?> == true) {
              repad<?php echo $quest->id_q ?> = 0;
              var repadtxt<?php echo $quest->id_q ?> = "Oui";
            } else {
              repad<?php echo $quest->id_q ?> = 1
              var repadtxt<?php echo $quest->id_q ?> = "Non";
            }
            $('#txtad').append("<?php echo $quest->question ?> " + repadtxt<?php echo $quest->id_q ?> + '<br>');
          <?php endforeach; ?>
          result = calculad(QAD1, sexe, <?php foreach ($quad as $liste2) : echo "QAD" . $liste2->id_q . ',repad' . $liste2->id_q . ',';
                                        endforeach; ?>);
          test = sortarray();
          compte = test.length;
          altergraph(compte, result);
          graphad(result);
        })
      });
      $('#but1radufull').click(function() {
        $('#Q1panel').hide(500, function() {
          $('#Qrepad').show(500);
          sexe = Math.random() < 0.5;
          if (sexe == true) {
            sexe = 0;
            var sexetxt = "Homme";
          } else {
            sexe = 1;
            var sexetxt = "Femme";
          }
          $('#txtad').append("SEXE : " + sexetxt + '<br>');
          <?php foreach ($quad as $quest) : ?>
            repad<?php echo $quest->id_q ?> = Math.random() < 0.5;
            if (repad<?php echo $quest->id_q ?> == true) {
              repad<?php echo $quest->id_q ?> = 0;
              var repadtxt<?php echo $quest->id_q ?> = "Oui";
            } else {
              repad<?php echo $quest->id_q ?> = 1
              var repadtxt<?php echo $quest->id_q ?> = "Non";
            }
            $('#txtad').append("<?php echo $quest->question ?> " + repadtxt<?php echo $quest->id_q ?> + '<br>');
          <?php endforeach; ?>
          result = calculad(QAD1, sexe, <?php foreach ($quad as $liste2) : echo "QAD" . $liste2->id_q . ',repad' . $liste2->id_q . ',';
                                        endforeach; ?>);
          console.log(result);
          test = sortarray();
          compte = test.length;
          <?php foreach ($adcat as $recalc) : ?>
            result['<?= $recalc->nom ?>'] = (result['<?= $recalc->nom ?>'] / test[0]) * 100;
            $("#pourentad<?= $recalc->id ?>").append((Math.round(result['<?= $recalc->nom ?>']) + '%'));
          <?php endforeach; ?>
          $(".adulte").css({
            'width': (1 / 12) * 100 + '%'
          });
          graphad(result);
        })
      });
    });
  </script>
  <?php $counterss = 0; ?>
  <div class="row" style="padding:16px; justify-content:center;margin-left: 0px; margin-right: 0px">
    <div class="col-lg-10" style="background-color: white; padding: 16px; border-radius: 10px">
      <div class="panel" style="border:Opx">
        <div class="row">
          <div id="title" class="col-md-12">
            <h4>Test de personnalité et d'orientation</h4>
            <hr>
            <!-- Question 1 -->
            <div id="Q1panel">
              <h5>Es-tu une fille ou un garçon ?</h5>
              <br>
              <div id="wsexe" class="warning">
                Veuillez selectionner votre sexe biologique.
              </div>
              <br>
              <input name="sexe" type="hidden" value=NULL>
              <div class="row" style="justify-content: space-evenly">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q1/woman.png') ?>">
                    <br>
                    <input name="sexe" type="radio" value=1>
                    <br>
                    <p>Fille</p>
                  </div>
                </label>
              
                <label class="box2right">
                  <div>
                    <img class="imgbox" src="/assets/imgs/questionnaire/q1/man.png') ?>">
                    <br>
                    <input name="sexe" type="radio" value=0>
                    <br>
                    <p>Garçon</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ1n" class="buggon" type="button" value="Suivant">
                <!-- <input id="but1renf" class="buggon" type="button" value="Débug Enfant">
                <input id="but1radu" class="buggon" type="button" value="Débug Adulte"> 
                <input id="but1radufull" class="buggon" type="button" value="Débug Adulte Full">   -->
                <br><br>
              </div>
            </div>
            <!-- Question age:before -->
            <div id="QAgepanel" class="paneloui">
              <h5>Quel âge as-tu ?</h5>
              <br>
              <div id="wage" class="warning">
                Veuillez selectionner votre age.
              </div>
              <br>
              <input name="agetranche" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agetranche" type="radio" value=0>
                    <br>
                    <p>Moins de 6 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/10-14.png') ?>">
                    <br>
                    <input name="agetranche" type="radio" value=1>
                    <br>
                    <p>Entre 6 et 25 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/14+.png') ?>">
                    <br>
                    <input name="agetranche" type="radio" value=2>
                    <br>
                    <p>Entre 25 et 40 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/40+.png') ?>">
                    <br>
                    <input name="agetranche" type="radio" value=3>
                    <br>
                    <p>Plus de 40 ans</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQAgep" class="buggon" type="button" value="Précédent">
                <input id="butQAgen" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question age:baby -->
            <div id="QBabypanel" class="paneloui">
              <h5>Quel âge as-tu exactement ?</h5>
              <br>
              <div id="wage" class="warning">
                Veuillez selectionner votre age.
              </div>
              <br>
              <input name="agebaby" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agebaby" type="radio" value=1>
                    <br>
                    <p>1 an</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agebaby" type="radio" value=2>
                    <br>
                    <p>2 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agebaby" type="radio" value=3>
                    <br>
                    <p>3 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agebaby" type="radio" value=4>
                    <br>
                    <p>4 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/-5.png') ?>">
                    <br>
                    <input name="agebaby" type="radio" value=5>
                    <br>
                    <p>5 ans</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQbabyp" class="buggon" type="button" value="Précédent">
                <input id="butQbabyn" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 2 -->
            <div id="Q2panel" class="paneloui">
              <h5>Quel âge as-tu exactement ?</h5>
              <br>
              <div id="wage" class="warning">
                Veuillez selectionner votre age.
              </div>
              <br>
              <input name="age" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/6-10.png') ?>">
                    <br>
                    <input name="age" type="radio" value=0>
                    <br>
                    <p>6-10 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/10-14.png') ?>">
                    <br>
                    <input name="age" type="radio" value=1>
                    <br>
                    <p>10-14 ans</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q2/14+.png') ?>">
                    <br>
                    <input name="age" type="radio" value=2>
                    <br>
                    <p>14 ans et plus</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ2p" class="buggon" type="button" value="Précédent">
                <input id="butQ2n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 3 -->
            <div id="Q3panel" class="paneloui">
              <h5>Aimes-tu la danse ?</h5>
              <br>
              <div id="wdanse" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="sexe" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q3/y.png') ?>">
                    <br>
                    <input name="danse" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q3/n.png') ?>">
                    <br>
                    <input name="danse" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ3p" class="buggon" type="button" value="Précédent">
                <input id="butQ3n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 4 -->
            <div id="Q4panel" class="paneloui">
              <h5>As-tu un esprit d'équipe ?</h5>
              <br>
              <div id="wtw" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="tw" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q4/y.png') ?>">
                    <br>
                    <input name="tw" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q4/n.png') ?>">
                    <br>
                    <input name="tw" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ4p" class="buggon" type="button" value="Précédent">
                <input id="butQ4n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 5 -->
            <div id="Q5panel" class="paneloui">
              <h5>Es-tu souple ?</h5>
              <br>
              <div id="wsouplesse" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="souplesse" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q5/y.png') ?>">
                    <br>
                    <input name="souplesse" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q5/n.png') ?>">
                    <br>
                    <input name="souplesse" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ5p" class="buggon" type="button" value="Précédent">
                <input id="butQ5n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 6 -->
            <div id="Q6panel" class="paneloui">
              <h5>As-tu le vertige ?</h5>
              <br>
              <div id="wvertigo" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="vertigo" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q6/y.png') ?>">
                    <br>
                    <input name="vertigo" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q6/n.png') ?>">
                    <br>
                    <input name="vertigo" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ6p" class="buggon" type="button" value="Précédent">
                <input id="butQ6n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 7 -->
            <div id="Q7panel" class="paneloui">
              <h5>Es-tu acrobate ?</h5>
              <br>
              <div id="waccrobate" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="accrobate" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q7/y.png') ?>">
                    <br>
                    <input name="accrobate" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q7/n.png') ?>">
                    <br>
                    <input name="accrobate" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ7p" class="buggon" type="button" value="Précédent">
                <input id="butQ7n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 8 -->
            <div id="Q8panel" class="paneloui">
              <h5>Es-tu persévérant ?</h5>
              <br>
              <div id="wpers" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="pers" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q8/y.png') ?>">
                    <br>
                    <input name="pers" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q8/n.png') ?>">
                    <br>
                    <input name="pers" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ8p" class="buggon" type="button" value="Précédent">
                <input id="butQ8n" class="buggon" type="button" value="Suivant">
                <br><br>
              </div>
            </div>
            <!-- Question 9 -->
            <div id="Q9panel" class="paneloui">
              <h5>Aimes-tu la musculation ?</h5>
              <br>
              <div id="wmuscu" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="muscu" type="hidden" value=NULL>
              <div style="justify-content: space-evenly" class="row">
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q9/y.png') ?>">
                    <br>
                    <input name="muscu" type="radio" value=0>
                    <br>
                    <p>Oui</p>
                  </div>
                </label>
                <label>
                  <div class="box2right">
                    <img class="imgbox" src="/assets/imgs/questionnaire/q9/n.png') ?>">
                    <br>
                    <input name="muscu" type="radio" value=1>
                    <br>
                    <p>Non</p>
                  </div>
                </label>
              </div>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ9p" class="buggon" type="button" value="Précédent">
                <input id="but10renf" class="buggon" type="button" value="Résultats">
                <br><br>
              </div>
            </div>
            <!-- Question 10 -->
            <div id="Q10panel" class="paneloui">
              <h5>undefined ?</h5>
              <br>
              <div id="wyasuo" class="warning">
                Veuillez sélectionner une réponse.
              </div>
              <br>
              <input name="yasuo" type="hidden" value=undefined checked>
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input id="butQ10p" class="buggon" type="button" value="Précédent">
                <input id="but10renf" class="buggon" type="button" value="Résultats">
                <br><br>
              </div>
            </div>
            <!-- Questions adulte -->
            <?php foreach ($quad as $quad) :
              $idq = $quad->id_q; ?>
              <div id="Q<?= $quad->id_q ?>ad" class="paneloui">
                <h5><?= $quad->question ?></h5>
                <br>
                <div id="warn<?= $quad->id_q ?>" class="warning">
                  Veuillez sélectionner une réponse.
                </div>
                <br>
                <input name="Q<?= $quad->id_q ?>adrep" type="hidden" value=NULL>
                  <div class="row" style="justify-content: space-evenly">
                    <label>
                    <div class="box2right">
                      <img class="imgbox" src="{{ $adQgen[$counterss]->img }}">
                      <br>
                      <input name="Q<?= $quad->id_q ?>adrep" type="radio" value=0>
                      <br>
                      <p>Oui</p>
                    </div>
                  </label>
                <?php $counterss = $counterss + 1; ?>
                  <label>
                    <div class="box2right">
                      <img class="imgbox" src="{{ $adQgen[$counterss]->img }}">
                      <br>
                      <input name="Q<?= $quad->id_q ?>adrep" type="radio" value=1>
                      <br>
                      <p>Non</p>
                    </div>
                  </label>
                </div>
                <?php $counterss = $counterss + 1; ?>
                <div class="col-sm-12" style="text-align:center">
                  <br><br class="mobhide"><br class="mobhide">
                  <input id="butadp<?= $quad->id_q ?>" class="buggon" type="button" value="Précédent">
                  <input id="butadn<?= $quad->id_q ?>" class="buggon" type="button" value="Suivant">
                  <br><br>
                </div>
              </div>
              <script>
                <?php if ($idq == 2) : ?>
                  $('#butadp<?= $quad->id_q ?>').click(function() {
                    $('#Q<?= $quad->id_q ?>ad').hide(500, function() {
                      $('#QAgepanel').show(500);
                      $("window").scrollTop(204);
                    });
                  });
                <?php else : ?>
                  $('#butadp<?= $quad->id_q ?>').click(function() {
                    $('#Q<?= $quad->id_q ?>ad').hide(500, function() {
                      $('#Q<?= $quad->id_q - 1 ?>ad').show(500);
                      $("window").scrollTop(204);
                    });
                  });
                <?php endif ?>
                <?php if ($quad->id_q == $idmax) : ?>
                  $('#butadn<?= $quad->id_q ?>').click(function() {
                    if ($('input[name=Q<?= $quad->id_q ?>adrep]:checked').val() == 0 || $('input[name=Q<?= $quad->id_q ?>adrep]:checked').val() == 1) {
                      $('#Q<?= $quad->id_q ?>ad').hide(500, function() {
                        $('#Qrepad').show(500);
                        $('#warn<?= $quad->id_q ?>').hide(500);
                        $("window").scrollTop(204);
                        var sexe = $('input[name=sexe]:checked').val();
                        $('#txtad').append("SEXE " + sexe + '<br>');
                        <?php for ($i = 2; $i <= 10; $i++) : ?>
                          var repad<?= $i ?> = $('input[name=Q<?= $i ?>adrep]:checked').val();
                          $('#txtad').append("<?= $i ?> " + repad<?= $i ?> + '<br>');
                        <?php endfor; ?>
                        calculad(QAD1, sexe, <?php for ($i = 2; $i <= 10; $i++) : ?> QAD<?= $i ?>, repad<?= $i ?>, <?php endfor; ?>);
                        console.log(result);
                        test = [<?php foreach ($adcat as $calc2) : ?>
                            Math.round(parseFloat(QAD1[sexe]['<?= $calc2->nom ?>']) * parseFloat(QAD10[repad10]['<?= $calc2->nom ?>']) * parseFloat(QAD4[repad4]['<?= $calc2->nom ?>']) * (parseFloat(QAD2[repad2]['<?= $calc2->nom ?>']) + parseFloat(QAD3[repad3]['<?= $calc2->nom ?>']) + parseFloat(QAD5[repad5]['<?= $calc2->nom ?>']) + parseFloat(QAD6[repad6]['<?= $calc2->nom ?>']) + parseFloat(QAD7[repad7]['<?= $calc2->nom ?>']) + parseFloat(QAD8[repad8]['<?= $calc2->nom ?>']) + parseFloat(QAD9[repad9]['<?= $calc2->nom ?>']))),
                          <?php endforeach; ?>
                        ]
                        test.sort(function(a, b) {
                          return b - a;
                        });
                        compte = test.length;
                        altergraph(compte, result);
                        graphad(result);
                      });
                    } else {
                      $('#warn<?= $quad->id_q ?>').show(500);
                    }
                  });
                <?php else : ?>
                  $('#butadn<?= $quad->id_q ?>').click(function() {
                    if ($('input[name=Q<?= $quad->id_q ?>adrep]:checked').val() == 0 || $('input[name=Q<?= $quad->id_q ?>adrep]:checked').val() == 1) {
                      $('#Q<?= $quad->id_q ?>ad').hide(500, function() {
                        $('#Q<?= $quad->id_q + 1 ?>ad').show(500);
                        $('#warn<?= $quad->id_q ?>').hide(500);
                        $("window").scrollTop(204);
                      });
                    } else {
                      $('#warn<?= $quad->id_q ?>').show(500);
                    }
                  });
                <?php endif; ?>
              </script>
            <?php endforeach; ?>
            <!-- Résultats enfants -->
            <div id="resultenfant" class="paneloui">
              <h5>Voici les 7 sections qui te correspondent le plus :</h5>
              <p>&nbsp;</p>
              <div id="graph" class="col-lg-12" style="padding:0px">
                <div style="height:300px;border-radius: 5px ;vertical-align:bottom; text-align:center">
                  <div class="col histo" style="width:13%; text-align:center">
                    <img class="blocc" id="GAMp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="GAMt"></p>
                    <img id="i0" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="GAMH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:13%; text-align:center">
                    <img class="blocc" id="GAFp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="GAFt"></p>
                    <img id="i1" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="GAFH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:13%; text-align:center">
                    <img class="blocc" id="GACp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="GACt"></p>
                    <img id="i2" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="GACH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:12%; text-align:center">
                    <img class="blocc" id="GRp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="GRt" style="padding:0"></p>
                    <img id="i3" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="GRH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:13%; text-align:center">
                    <img class="blocc" id="AERp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="AERt"></p>
                    <img id="i4" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="AERH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:13%; text-align:center">
                    <img class="blocc" id="CTp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="CTt"></p>
                    <img id="i5" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="CTH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                  <div class="col histo" style="width:13%; text-align:center; border: 0px">
                    <img class="blocc" id="PKp" src="/Resources/Images/Logos/Placeholder.png"></img>
                    <p class="blocc" id="PKt"></p>
                    <img id="i6" style="width:30px" src="/Resources/Images/Logos/SensInterdit.png"></img>
                    <img class="blocc" id="PKH" style="width: 60%;height: 62%;margin: auto;" src="/Resources/Images/Logos/Histo.png"></img>
                  </div>
                </div>
                <div style="text-align:center">
                  <p style="font-size:5px">&nbsp;</p>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/a8pgym-masculine') ?>"><img class="responsive" id="GAM" src="/uploads/Categories/2-GAM.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Gym Masculine</p>
                  </div>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/cpmgym-feminine') ?>"><img id="GAF" class="responsive"  src="/uploads/Categories/2-GAF.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Gym Féminine</p>
                  </div>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/szogym-acrobatique') ?>"><img id="GAC" class="responsive" src="/uploads/Categories/2-GAC.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Gym Accrobatique</p>
                  </div>
                  <div class="col divsous" style="width:12%;">
                    <a target="_blank" href="index.php/shop/category/juigym-rytmique') ?>"><img id="GR" class="responsive" src="/uploads/Categories/2-GR.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Gym Rythmique</p>
                  </div>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/tyhaerobic') ?>"><img id="AER" class="responsive" src="/uploads/Categories/2-AEROBIC.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Gym Aerobique</p>
                  </div>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/tupcrossfit') ?>"><img id="CT" class="responsive" src="/assets/imgs/3-CrossFit.png"></img></a>
                    <p class="pchide" style="font-size:8px">&nbsp;</p>
                    <p class="descdiag">Cross Training</p>
                  </div>
                  <div class="col divsous" style="width:13%;">
                    <a target="_blank" href="index.php/shop/category/hfdparkour-jeunes') ?>"><img id="PK" class="responsive" src="/uploads/Categories/2-PARKOUR.png"></img></a>
                    <p class="pchide" style="font-size:13px">&nbsp;</p>
                    <p class="descdiag">Parkour</p>
                  </div>
                </div>
                <br class="pchide"><br class="pchide"><br class="pchide"><br><br><br>
              </div>
              <!-- <div hidden class="col-md-6">
                <p id="txtsexe">Tu es</p>
                <p id="txtage">Tu as entre</p>
                <p id="txtdanse">Tu</p>
                <p id="txttw">Tu</p>
                <p id="txtsouple">Tu</p>
                <p id="txtvertigo">Tu</p>
                <p id="txtaccrobate">Tu</p>
                <p id="txtpers">Tu</p>
                <p id="txtmuscu">Tu</p>
                <p id="txtyasuo"></p>
              </div> -->
              <div class="col-sm-12" style="text-align:center">
                <br><br class="mobhide"><br class="mobhide">
                <input type="button" class="buggon refresh" value="Recommencer" id="refresh">
                <br><br>
              </div>
            </div>
            <!-- Résultats adulte -->
            <div id="Qrepad" class="paneloui">
              <h5>Résultats :</h5>
              <p>&nbsp;</p>
              <!-- <div id="txtad"></div> -->
              <div id="graphadfull" class="col">
                <div id="graphad" class="">
                  <div style="height:400px;border-radius: 5px ;vertical-align:bottom; text-align:center;">
                    <?php foreach ($adcat as $graph) : ?>
                      <div class="col histo adulte largeur" id="adultbar<?= $graph->id ?>">
                        <img class="blocc" id="place<?= $graph->id ?>" src="/Resources/Images/Logos/Placeholder.png"></img>
                        <img class="blocc impossible" id="in<?= $graph->id ?>" src="/Resources/Images/Logos/SensInterdit.png"></img>
                        <img class="blocc advancement" id="barre<?= $graph->id ?>" src="/Resources/Images/Logos/Histo.png" style="width: 100%"></img>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div style="text-align:center">
                  <p>&nbsp;</p>
                  <?php foreach ($adcat as $pic) : ?>
                    <div class="col divsous adulte largeur" id="img<?= $pic->id ?>">
                      <p id='pourentad<?= $pic->id ?>' style="font-size:12px"></p>
                      <a target="_blank" href=""><img class="responsive" id="ad<?= $pic->id ?>" src="<?= $pic->img ?>"></img></a>
                      <p class="pchide" style="font-size:14px">&nbsp;</p>
                      <p class="descdiag"><?= $pic->nom ?></p>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="col-lg-12" style="text-align:center; margin-top: 575px">
                <br><br class="mobhide"><br class="mobhide">
                <input type="button" class="buggon refresh" value="Recommencer" id="refresh">
                <br><br>
              </div>
            </div>
          </div>
          <!-- Résultats Bébé 1 an -->
          <div id="QBabyres1" class="paneloui">
            <h4 style="margin-left: 15px;">Résultats :</h4>
            <p>&nbsp;</p>
            <div class="col-md-2">&nbsp;</div>
            <div id="" class="col-lg-12" style="padding:0px; text-align:center">
              <a target="" href="index.php/shop/category/jrv0-2-ans') ?>">
                <img style="max-width:200px" src="/uploads/Categories/1-MBG.png">
                <h5 style="padding-right: 10px;">Mini-BabyGym</h5>
              </a>
              <br>
            </div>
            <div class="col-lg-12">
              <p>Cette section concerne des tous petits ayant entre 10 mois et 2 ans. Accompagnés de leurs parents, les enfants développent leur motricité et leur créativité à travers les circuits mis en place par les cadres formés et diplômés. Les animations mises en place permettent aux plus petits de découvrir leur corps et l'espace</p>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-sm-12" style="text-align:center">
              <br><br class="mobhide"><br class="mobhide">
              <input type="button" class="buggon refresh" value="Recommencer" id="">
              <br><br>
            </div>
          </div>
          <!-- Résultats Bébé 2 ans -->
          <div id="QBabyres2" class="paneloui">
            <h5 style="margin-left: 15px;">Résultats :</h5>
            <p>&nbsp;</p>
            <div class="col-md-2">&nbsp;</div>
            <div id="" class="col-lg-12" style="padding:0px; text-align:center">
              <a target="" href="/index.php/shop/category/1uy2-ans">
                <img style="max-width:200px" src="/uploads/Categories/1-BG.png">
                <h5 style="padding-right: 10px;">BabyGym</h5>
              </a>
              <br>
            </div>
            <div class="col-lg-12">
              <p>Ce cours concerne les enfants âgés de 2 ans, accompagnés obligatoirement de leurs parents (ou d’un adulte). Ils développent leur motricité et leur créativité à travers les circuits mis en place par les cadres, formés et diplômés. Comptines et chansons à gestes font parties du programme.</p>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-sm-12" style="text-align:center">
              <br><br class="mobhide"><br class="mobhide">
              <input type="button" class="buggon refresh" value="Recommencer" id="">
              <br><br>
            </div>
          </div>
          <!-- Résultats Bébé 3 ans -->
          <div id="QBabyres3" class="paneloui">
            <h5 style="margin-left: 15px;">Résultats :</h5>
            <p>&nbsp;</p>
            <div class="col-md-2">&nbsp;</div>
            <div id="" class="col-lg-12" style="padding:0px; text-align:center">
              <a target="" href="/index.php/shop/category/rfp3-ans-eveil-gymnique">
                <img style="max-width:200px" src="/uploads/Categories/1-EvG.png">
                <h5 style="padding-right: 10px;">Eveil Gymnique</h5>
              </a>
              <br>
            </div>
            <div class="col-lg-12">
              <p>Les cours d'éveil Gymnique sont la parfaite continuité des séances de Baby-Gym. Le contenu des séances est singulièrement identique, mais la grande différence réside dans le fait que les parents ne sont plus présents. Comptines et chansons à gestes font encore partie du programme, mais les enfants évoluent maintenant seuls.</p>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-sm-12" style="text-align:center">
              <br><br class="mobhide"><br class="mobhide">
              <input type="button" class="buggon refresh" value="Recommencer" id="">
              <br><br>
            </div>
          </div>
          <!-- Résultats Bébé 4/5 ans -->
          <div id="QBabyres4" class="paneloui">
            <h5 style="margin-left: 15px;">Résultats :</h5>
            <p>&nbsp;</p>
            <div class="col-md-2">&nbsp;</div>
            <div id="" class="col-lg-12" style="padding:0px; text-align:center">
              <a target="" href="/index.php/shop/category/d8g4-5-ans-ecole-de-gym">
                <img style="max-width:200px" src="/uploads/Categories/1-EcG.png">
                <h5 style="padding-right: 10px;">Ecole de Gym</h5>
              </a>
              <br>
            </div>
            <div class="col-lg-12">
              <p>L'école de Gymnastique concerne des enfants ayant de 4 et 5 ans. Lors de ces séances, les enfants appréhendent les premiers exercices de gymnastique à travers les circuits mis en place par les cadres formés et diplômés. Ils découvrent également les agrès utilisés en gymnastique artistique.</p>
            </div>
            <div class="col-md-2">&nbsp;</div>
            <div class="col-sm-12" style="text-align:center">
              <br><br class="mobhide"><br class="mobhide">
              <input type="button" class="buggon refresh" value="Recommencer" id="">
              <br><br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  
  <style>
    .paneloui {
      display: none;
    }
  
    .selected {
      background-color: #D2FFD8;
    }
  
    .histo {
      /* border-left: 1px grey solid; */
      /* border-right: 1px grey solid; */
      padding: 1px;
      display: table-cell;
      vertical-align: bottom;
    }
  
    .histo>img {
      padding: 0;
    }
  
    .descdiag {
      font-size: 9px;
    }
  
    .box2right {
      height: 150px;
      width: 150px;
      cursor: pointer;
      text-align: center;
      display: inline-block;
    }
    .advancement{
      width:60% !important;
      margin: auto !important;
    }
    
  
    @media screen and (max-width:640px) {
      .mobhide {
        display: none
      }
      .responsive{
        max-width: 30px !important;
      }
      .impossible{
      width: 15px !important;
    }
      .invisible{
        display :none;
      }
      .largeur{
        width: 12% !important;
      }
      .box2right {
        height: 120px;
        width: 120px;
      }
  
      .histo {
        height: 100%;
        padding: 0 5px
      }
  
      .divsous {
        padding: 1px;
      }
  
      .descdiag {
        font-size: 9px;
        transform: rotate(90deg);
        text-align: left;
      }
  
      input[type='radio']:before {
        width: 125px;
        height: 125px;
        border-radius: 30px;
        border: 1px solid grey;
        position: relative;
        display: grid;
        top: -105px;
        left: -55px;
        content: '';
      }
  
      input[type='radio']:checked:before {
        background-color: rgba(87, 214, 88, .1);
        width: 125px;
        height: 125px;
        border-radius: 30px;
        border: 1px solid green;
        position: relative;
        display: grid;
        top: -105px;
        left: -55px;
        content: '';
      }
  
      input[type='radio']:after {
        width: 20px;
        height: 20px;
        border-radius: 15px;
        top: -128px;
        left: 0px;
        position: relative;
        background-color: #f2f2f2;
        content: '';
        display: inline-block;
        visibility: visible;
      }
  
      input[type='radio']:checked:after {
        width: 20px;
        height: 20px;
        border-radius: 15px;
        top: -128px;
        left: -1px;
        position: relative;
        background-color: rgba(226, 239, 226, 1);
        content: '';
        display: inline-block;
        visibility: visible;
      }
    }
  
    @media screen and (min-width:640px) {
  
      .box2right {
        position: relative;
        top: 50px;
      }
      .invisible{
        display: block;
      }
      .impossible{
      width: 30px !important;
    }
      .largeur{
        width: 12% !important;
      }
      .responsive{
        max-width: 70px !important;
      }
      .pchide {
        display: none
      }
  
      .histo {
        height: 100%;
        padding: 0 10px;
        vertical-align: bottom;
      }
  
      .box5left {
        padding-left: 18%
      }
  
      .box5right {
        padding-right: 18%
      }
  
      input[type='radio']:before {
        width: 150px;
        height: 150px;
        border-radius: 30px;
        border: 1px solid grey;
        position: absolute;
        top: 0px;
        left: 0px;
        content: '';
        display: inline-block;
      }
  
      input[type='radio']:checked:before {
        width: 150px;
        height: 150px;
        border-radius: 30px;
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: rgba(87, 214, 88, .1);
        content: '';
        display: inline-block;
        visibility: visible;
        border: 1px solid green;
      }
  
      input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #f2f2f2;
        content: '';
        display: inline-block;
        visibility: visible;
      }
  
      input[type='radio']:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: rgba(226, 239, 226, 1);
        content: '';
        display: inline-block;
        visibility: visible;
      }
    }
  
    .imgbox {
      width: 80%;
      padding-top: 5px
    }
  
    .buggon {
      color: black;
      background-color: rgba(255, 255, 255, 0);
      border: 1px solid grey;
      border-radius: 5px;
      font-size: 20px;
      padding: 5px 20px
    }
  
    .buggon:hover {
      color: #07b5db;
      background-color: rgba(255, 255, 255, 0);
      border: 1px solid #07b5db;
      border-radius: 5px;
      font-size: 20px;
      padding: 5px 20px
    }
  
    .warning {
      display: none;
      font: 18px;
      color: red;
      padding: 20px;
      border: 1px solid red;
      border-radius: 5px;
    }
  
    .blocc {
      display: block;
    }
  </style>

@endsection