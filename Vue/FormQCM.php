<?php
	require_once("Model/Class/QCM.php");
	
	// Pour aller à l'essentiel, générer automatiquement un QCM constitué de toutes les questions présente en BDD
	$QCM = new QCM();
	$QCM->Generer(-1);
	$QCM->toHTML();
?>