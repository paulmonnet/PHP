<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	if( !empty($Title) )
		echo "\t\t<title>$Title</title>\n";
	if( !empty($CSS) )
		echo "\t\t<link href=\"$CSS\" rel=\"stylesheet\" type=\"text/css\">\n";
	if( !empty($JS) )
		echo "\t\t<script src=\"$JS\"></script>\n";
?>
	</head>
	<body>
