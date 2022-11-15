<?php
require_once( 'Model/Class/Question.php' );

$qSimple = new QSimple('Question Simple ?', QSimple::INDEX_VRAI);
$qSimple->ToHTML();
//echo strval($qSimple);	// équivaut à : echo $q->__toString();

$qMulti = new QMultiple('Question choix multiple ?', ['Réponse A', 'Rep B', 'Rep C', 'Rep D', 'Aucune de ces réponse'], [2, 3]);
$qMulti->ToHTML();

$QLibre = new QLibre('Question Libre ?', 'Description de la réponse !');
$QLibre->ToHTML();
?>