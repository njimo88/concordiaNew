@extends('layouts.template')

@section('content')
<main class="main" id="main">
<div class="container">
    <div class="row d-flex justify-content-center" style="text-align: center ; font-size: small; background-color:white;">

    <div class="col-lg-10">
  
    <div class="row " style="padding: 0 10px;text-align: center ; font-size: small; background-color:#00ff3a61;">
    <div class="col-lg-12">
      <h4 class="p-3">Salaire des Professionnels</h4>
      <table id="usersTable" class="table" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Professionnel</th>
            <th>Groupe</th>
            <th>Ancienneté</th>
            <th>Salaire CCNS</th>
            <th>Prime CCNS</th>
            <th>Salaire Actuel</th>
            <th>Prime Actuelle</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($data['resultatrenvoye'])) {
            foreach ($data['resultatrenvoye'] as $row) :
          ?>
                <tr style="padding-left:150px <?php if (($row['salaireMin'] > $row['salaireActuel']) || ($row['primeAnciennete'] > $row['primeActuel'])) : ?> background-color: #FF5050; <?php endif; ?>">
                  <td class="nom"><?php echo $row['professional'] ?></td>
                  <td class="groupe"><?php echo $row['Groupe']  ?></td>
                  <td class="ancien"><?php echo $row['anciennete'] ?> Mois</td>
                  <td class="min"><?php echo $row['salaireMin'] ?> €</td>
                  <td class="prime"><?php echo $row['primeAnciennete'] ?> €</td>
                  <td class="salaire"><?php echo $row['salaireActuel'] ?> €</td>
                  <td class="primeact"><?php echo $row['primeActuel'] ?> €</td>
                </tr>
          <?php
            endforeach;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <hr>
  
  <div class="row" style="padding: 0 10px; font-size: small; background-color:#eb00004d; border: 1px solid #6c757d57">
    

      <div style="col-lg-12">
        <h4 class="p-3">Modifier le SMIC et le SMC</h4>
        <form action="{{ route('proffesional.modifySM') }}" method="post">
          <div class="row">
            <div class="form-group col-lg-5">
              <label for="name"><b>SMIC (€) :</b></label>
              <input type="text" class="form-control" name="smic" id="smic" value="<?php $data['SMIC'] ?>" required>
            </div>
            <div class="form-group col-lg-5">
              <label for="email"><b>SMC (€) : </b></label>
              <input type="text" class="form-control" name="smc" id="smc" value="<?php $data['SMC'] ?>" required>
            </div>
            <div class="form-group col-lg-2" style="margin-top: 26px">
              <input type="submit" class="btn btn-warning" value="Modifier">
            </div>
          </div>
        </form>
      </div>
    </div>
  
      <hr>
  
    
      <div class="row">
      <div class="col-lg-12" style="padding: 0 10px; font-size: small; background-color:#1e77d773; border: 1px solid #6c757d57">
        <h4 class="p-3">Simuler un salaire</h4>
        <form action="{{ route('proffesional.simuleSalary') }}" method="post">
          <table id="" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th style="">Heures Par Semaine</th>
                <th style="">Groupe</th>
                <th style="">Date d'embauche</th>
                <th></th>
              </tr>
              <tr>
                <th style=""></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" id="volHebdo" name="volHebdo"><br>&nbsp;</td>
                <td><input type="text" class="form-control" id="groupe" name="groupe"><br>&nbsp;</td>
                <td><input type="date" class="form-control" id="embauche" name="embauche"><br>&nbsp;</td>
                <td><input type="submit" class="btn btn-success" value="Simuler"><br>&nbsp;</td>
              </tr>
  
            </tbody>
          </table>
        </form>
      </div>
      <?php if (isset($simuleSalary) && isset($simulePrime)) { ?>
        <div class="col-lg-12">
          <h4>Salaire Simulé :</h4>
          <table id="" class="stack" cellspacing="0" width="100%" border="0">
            <thead>
              <tr>
                <th style="">Salaire Minimum</th>
                <th style="">Prime Minimum</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $simuleSalary ?> €</td>
                <td><?php echo $simulePrime ?> €</td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php } ?>
  </div>
  </div>
</div>
</div>
</main>

  <style>
    @media screen and (max-width:1024px) {
      .nom::before {
        content: "Nom";
        position: absolute;
        left: 25px;
        font-weight: bold;
      }
  
      .groupe::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Groupe"
      }
  
      .ancien::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Ancienneté"
      }
  
      .min::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Salaire CCNS"
      }
  
      .prime::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Prime CCNS"
      }
  
      .salaire::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Salaire Actuel"
      }
  
      .primeact::before {
        position: absolute;
        left: 25px;
        font-weight: bold;
        content: "Prime Actuelle"
      }
    }
  </style>
  @endsection