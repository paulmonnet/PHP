<main>
	<div class="container py-4">
		<header class="pb-3 mb-4 border-bottom">
			<a href="." class="d-flex align-items-center text-dark text-decoration-none">
				<span class="fs-4">Générateur de QCM</span>
			</a>
		</header>
		
		<div class="p-5 mb-4 bg-light rounded-3">
			<div class="container-fluid py-5">
				<h1 class="display-5 fw-bold">Trouver un QCM <i>(lancer un examen)</i></h1>
				<p class="col-md-8 fs-4">Dans cette rubrique, les QCM déjà générés pourront être édités à nouveaux. Une phase d'examen pourra être déclenché, un QCM sera alors distribué pour l'ensemble d'une classe et se terminera à la fin du temps impartie. Cette rubrique permet aussi la correction automatique ou partiel du QCM d'examen.</p>
				<button class="btn btn-primary btn-lg" type="button" onclick='document.location.href="?action=FindQCM";'>Rechercher un QCM</button>
			</div>
		</div>
		
		<div class="row align-items-md-stretch">
			<div class="col-md-6">
				<div class="h-100 p-5 text-white bg-dark rounded-3">
					<h2>Ajouter des Questions</h2>
					<p>La rubrique permet l'ajout d'ensemble question-réponse qui pourront être exploitées par la suite pour générer des QCM.</p>
					<button class="btn btn-outline-light" type="button" onclick='document.location.href="index.php?action=AddQuestion";'>Ajouter une question</button>
				</div>
			</div>
			<div class="col-md-6">
				<div class="h-100 p-5 bg-light border rounded-3">
					<h2>Générer un QCM</h2>
					<p>Cette rubrique permet de rassembler des questions précédemment inscrite en Base de données afin de constituer un QCM.</p>
					<button class="btn btn-outline-secondary" type="button" onclick='document.location.href="index.php?action=AddQCM";'>Editer un QCM</button>
				</div>
			</div>
		</div>
		
		<footer class="pt-3 mt-4 text-muted border-top">© 2022</footer>
	</div>
</main>