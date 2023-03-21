<!DOCTYPE HTML> 
<html>

	<head>
		<title>Filtre JS</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
		
		<style>
			.no-record{
				text-align: center;
				color: orange;
				font-weight: bold;
			}
			.hide { display: none;}
		</style>


<script type="text/javascript">

</script>

@php
$data_user =  json_encode($data) ;
@endphp
		<script>
			// Sample data to use in my HTML table
            var data = <?php echo  $data_user ?>;
			console.log(data);
			
			// store the filter objects
			var filters = {
				nom: [],
				email : [], 
                id: [],
			};
			
			function applyFilters(){
				var tmp = [].concat(data); // one way to clone the array
				
				// apply LastName filter
				if(filters.nom.length > 0){
					tmp = tmp.filter(function (e){ return filters.nom.indexOf(e.nom) >= 0; })
				}
                if(filters.email.length > 0){
					tmp = tmp.filter(function (e){ return filters.email.indexOf(e.email) >= 0; })
				}
				// apply City filter
				if(filters.id.length > 0){
					tmp = tmp.filter(function (e){ return filters.id.indexOf(e.id) >= 0; })
                    console.log(tmp);
				}
				return tmp;
			}
			
			function distinct(value, index, array) {
			  return array.indexOf(value) === index;
			}


			
			function build(){
				var html = "<table class=\"table table-hover\">"
				// build header
				html += "<thead><tr><th>nom</th><th>email</th><th>id</th></tr></thhead><tbody>"
				
				var rowCollection = applyFilters();
				// build rows
				$.each(rowCollection, function (index, value){
					html += "<tr><td>" + value.nom + "</td><td>" + value.email + "</td><td>" + value.id + "</td></tr>";
				});
				if(rowCollection.length === 0)
					// build empty row
					html += "<tr class=\"no-record\"><td colspan=\"4\" class=\"no-record\">Aucun enregistrement</td></tr></thhead>"
				
				// end
				html += "</tbody></table>";
				$("#tbl").empty();
				$("#tbl").append(html);
				
			}
			function displayMessage(txt){
				alert(txt);
			}
			
		</script>
	</head>
	
	<body>


		<div class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-md-3">Filtre sur Nom</label>
				<div class="col-md-3">
				<select id="cboLastName" class="form-control" multiple></select>
                
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Filtre sur Ville</label>
				<div class="col-md-3">
				<select id="cboCity" class="form-control" multiple></select>
				</div>
			</div>
            <div class="form-group">
				<label class="control-label col-md-3">Filtre sur Ville</label>
				<div class="col-md-3">
				<select id="cboid" class="form-control" multiple></select>
				</div>
			</div>


            
			<div class="form-group">
				<div id="tbl"></div>
			</div>
		</div>
	</body>

 
	<script>
		$(document).ready(function (){

 

			// Names filters
			var availableNames = data.map(function (e) { return e.nom}).filter(distinct).sort();
			$.each(availableNames, function (index, value){ $("#cboLastName").append("<option>" + value + "</option>"); });
			
			// City filters
			var availableCities = data.map(function (e) { return e.email}).filter(distinct).sort();
			$.each(availableCities, function (index, value){ $("#cboCity").append("<option>" + value + "</option>"); });
            
            var availableid = data.map(function (e) { return e.id}).filter(distinct).sort();
			$.each(availableid, function (index, value){ $("#cboid").append("<option>" + value + "</option>"); });
			build();
			
			$('#cboLastName').change(function(){
				filters.nom = [];
				$.each($(this).find(":selected"), function (index, value){ filters.nom.push(value.value); });
				build();
			});
			
			$('#cboCity').change(function(){
				filters.email = [];
				$.each($(this).find(":selected"), function (index, value){ filters.email.push(value.value); });
				build();
			});

            $('#cboid').change(function(){
				filters.id = [];
				$.each($(this).find(":selected"), function (index, value){ filters.id.push(value.value); });
				build();
			});
			
		});
	</script>




</html>