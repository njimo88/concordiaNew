<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard - Admin </title>
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    
    
    <link href="{{asset('assetsAdmin/css/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assetsAdmin/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assetsAdmin/css/quill.snow.css')}}" rel="stylesheet">
    <link href="{{asset('assetsAdmin/css/quill.bubble.css')}}" rel="stylesheet">
    <link href="{{asset('assetsAdmin/css/remixicon.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link rel="stylesheet" href="//cdn.tutorialjinni.com/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//g.tutorialjinni.com/mojoaxel/bootstrap-select-country/dist/css/bootstrap-select-country.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('assets\css\bootstrap.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <link href="{{asset('assetsAdmin/css/style.css')}}" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('assetsAdmin/js/tinymce.min.js')}}"></script>
    <script src="{{asset('assetsAdmin/js/validate.js')}}"></script>
    <script src="{{asset('assetsAdmin/js/echarts.min.js')}}"></script>
    <script src="{{asset('assetsAdmin/js/chart.min.js')}}"></script>
    <script src="{{ asset('assets\js\bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('assetsAdmin/js/apexcharts.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="//g.tutorialjinni.com/mojoaxel/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
    <script src="https://cdnjs.cloudflare.om/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script src="../r_js/jquery.nestable.js"></script>
    
    <script src="{{asset('assetsAdmin/js/main.js')}}"></script>
    <script src="../r_js/style.js"></script>
    <style>
        a {
          text-decoration:  none !important;
        }
      </style>
      @livewireStyles
 </head>
 <div class="main" id="main">

    <div class="row px-4" style="text-align: center ; font-size: small; background-color:white;">

    <div class="col-lg-10">
  
    <div class="row" style="padding: 0 10px;text-align: center ; font-size: small; background-color:#00ff3a61;">
    <div class="col-lg-12">
      <h4>Salaire des Professionnels</h4>
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
        <h4>Modifier le SMIC et le SMC</h4>
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
        <h4>Simuler un salaire</h4>
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
  