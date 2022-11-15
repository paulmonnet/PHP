<?php
$Title = 'QCM Gen';
$CSS = 'CSS/bootstrap.min.css';
$JS = 'JS/bootstrap.bundle.min.js';
include( 'Common/HTMLHead.php' );

//include( 'Controller/TestQuestion.php' );
if( isset($_GET['action']) )
{
	$action = htmlentities($_GET['action']);
	switch( $action )
	{
		case 'FindQCM':
			include( 'Vue/FormQCM.php' );
			break;
		
		case 'AddQuestion':
			include( 'Vue/FormQuestion.php' );
			break;
			
		case 'AddQCM':
			include( 'Vue/FormQCM.php' );
			break;
			
		default:
			include( 'Vue/Accueil.php' );
			break;
	}
}
else
{
	include( 'Vue/Accueil.php' );
	//header( 'location:http://localhost/Correction%20TD5/Vue/Accueil.php');
}

include( 'Common/HTMLEnd.php' );
?>