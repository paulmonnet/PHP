<div class="d-flex justify-content-center">
<fieldset style="width: 800px">
	<legend>Ajouter une Question en BDD</legend>
	<form method='post' action='Model/Database/AddQuestion.php' class="needs-validation" novalidate >
		<div class="form-row">
			<div class="form-group">
				<label for="q">La question</label>
				<input type="text" class="form-control" id="q" name="q" placeholder="???" required >
				<div class="valid-feedback">valide</div>
				<div class="invalid-feedback">une question est requise et doit se terminer par '?'</div>
			</div>
			<div class="form-group">
				<label for="SelectType">Type de réponse souhaité :</label>
				<select class="form-control" id="SelectType" name="TypeRep" required>
					<option value=''>Choisir...</option>
					<option value="simple">Simple - Vrai ou Faux</option>
					<option value="unique">Unique - Une seul réponse possible</option>
					<option value="multi" >Multi	- Plusieurs réponses possibles</option>
					<option value="libre" >Libre	- Rédaction de la réponse libre</option>
				</select>
			</div>
			<div class="form-group" id="simple" hidden>
				<label for="rep">Sélectionnez la réponse :</label>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="VF" id="vrai" value="0" required checked>
					<label class="form-check-label" for="vrai">Vrai</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="VF" id="faux" value="1" required>
					<label class="form-check-label" for="faux">Faux</label>
					<div class="invalid-feedback">Sélectionner la réponse attendue</div>
				</div>
			</div>
			<div id="unique" hidden>
				<div class="row">
					<div class="col"><label for="rep">Nombre de réponses à ajouter :</label></div>
					<div class="col"><input type="number" class="form-control" id="nbRep-radio" name="nbRep-radio" value="0" onchange="addRepMulti('radio')"></div>
					<div class="col"><button type="button" class="btn btn-success" onclick="addRepMulti('radio')">Générer les éléments</button></div>
				</div>
				<div id="form-group-radio" hidden>
					<div class="invalid-feedback">Sélectionner la réponse attendue</div>
				</div>
			</div>
			<div id="multi" hidden>
				<div class="row">
					<div class="col"><label for="rep">Nombre de réponses à ajouter :</label></div>
					<div class="col"><input type="number" class="form-control" id="nbRep-checkbox" name="nbRep-checkbox" onchange="addRepMulti('checkbox')"></div>
					<div class="col"><button type="button" class="btn btn-success" onclick="addRepMulti('checkbox')">Générer les éléments</button></div>
				</div>
				<div id="form-group-checkbox" hidden>
					<div class="invalid-feedback">Sélectionner la ou les réponse(s) attendue(s)</div>
				</div>
			</div>
			<div id="libre" hidden>
				<div class="form-group">
					<label for="repLibre">Réponse libre :</label>
					<textarea class="form-control" rows="3" id="repLibre" name="repLibre" placeholder="Exemple de réponse..."></textarea>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary" name='btInsert' >Ajouter</button>
	</form>
</fieldset>
<div/>

<script>
var oldRepVisible = '';

function ChangeOldrepTypeVisible(newVisible) {
	if( this.oldRepVisible !== '' )
		document.getElementById(this.oldRepVisible).hidden = true;
	
	this.oldRepVisible = newVisible;
}

var bt = document.getElementById("SelectType");
if( bt )
{
	bt.addEventListener("change", function() {
		ChangeOldrepTypeVisible( bt.value );
		document.getElementById(bt.value).hidden = false;
	}, false);
}

function addRepMulti(type) {
	var nbRep = document.getElementById('nbRep-'+type);
	nbRep = ( nbRep != null ) ? nbRep.value : 0;
	if( nbRep < 1 )
		return;
	
	var addRep = document.getElementById('form-group-'+type);
	if( addRep )
	{
		addRep.hidden = false;
		addRep.innerHTML = '';
		for( i = 0; i < nbRep; ++i )
			addRep.innerHTML += '<div class="form-check"><input type="'+type+'" class="form-check-input" id="Rep-'+type+i+'" name="Rep-'+type+( type == 'radio' ? '' : i )+'" value="'+i+'"><input type="text" class="form-control" id="TextRep-'+type+i+'" name="TextRep-'+type+i+'" required></div>';
	}
}

(function() {
	'use strict';
	window.addEventListener('load', function() {
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();
</script>