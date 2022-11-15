<?php
require_once('../Class/Question.php');
//var_dump( $_POST );

if( isset($_POST['TypeRep']) )
{
	switch( $_POST['TypeRep'] )
	{
		case 'simple':
		{
			if( isset($_POST['VF']) )
			{
				$Question = new QSimple(htmlentities($_POST['q']), htmlentities($_POST['VF']));
				$Question->Enregistrer();
			}
			else
				echo "Impossible d'ajouter la question simple (Vrai / Faux) car aucune de ces réponses n'est sélectionné\n\n";
		}
		break;
		
		case 'unique':
		{
			if( isset($_POST['nbRep-radio']) )
			{
				$nbRep = intval($_POST['nbRep-radio']);
				echo "nbRep = $nbRep !";
				if( $nbRep < 2 )
					echo "Impossible d'ajouter la question à réponse unique car il faut fournir au moins 2 réponses possible\n\n";
				else
				{
					$reponsesPossible = [];
					for($i = 0; $i < $nbRep; ++$i)
						$reponsesPossible[] = htmlentities($_POST["TextRep-radio$i"]);
						
					$Question = new QUnique( htmlentities($_POST['q']), $reponsesPossible, htmlentities($_POST["Rep-radio"]) );
					$Question->Enregistrer();
				}
			}
		}
		break;
		
		case 'multi':
		{
			if( isset($_POST['nbRep-checkbox']) )
			{
				$nbRep = intval($_POST['nbRep-checkbox']);
				if( $nbRep < 2 )
					echo "Impossible d'ajouter la question à réponse à choix multiple car il faut fournir au moins 2 réponses possible\n\n";
				else
				{
					$reponsesPossible = [];
					$Réponses = [];
					for($i = 0; $i < $nbRep; ++$i)
					{
						$reponsesPossible[] = htmlentities($_POST["TextRep-checkbox$i"]);
						
						if( isset($_POST["Rep-checkbox$i"]) )
							$Réponses[] = htmlentities($_POST["Rep-checkbox$i"]);
					}
					
					//var_dump( $Réponses );
					
					$Question = new QMultiple( htmlentities($_POST['q']), $reponsesPossible, $Réponses );
					$Question->Enregistrer();
				}
			}
		}
		break;
		
		case 'libre':
		{
			$Question = new QLibre(htmlentities($_POST['q']), htmlentities($_POST['repLibre']));
			$Question->Enregistrer();
		}
		break;
	}
}

echo '<button type="button" class="btn btn-dark" onclick="document.location.href=\'../../index.php?action=AddQuestion\'">Retour à l\'ajout de question</button>';
?>