<?php
require_once('Question.php');

interface TemplateQCM extends TemplateDisplay
{
    public function Correction() : void;
    public function Generer(int $TypeGenQCM) : void;
}

class QCM implements TemplateQCM
{
	private const NOTE_INCONNU = -1;
	
	private $note;						// la note obtenue au QCM après Correction
	private $noteMaxi;					// le bareme de notation
	private $nbQuestion;				// nombre de question que comprend le QCM
	private $tempsMaxi;					// le temps max accorder pour répondre à l'ensemble des question (en minutes)
	private $entete;					// une entête composé de l'auteur du QCM, un titre etc...
	private $listQ;						// la liste des questions
	
	function __construct( int $nbQuestion = 20, int $noteMaxi = 20, int $tempsMaxi = 120, string $entete = 'QCM Auto Généré')
	{
		$this->note 		= NOTE_INCONNU;
		$this->nbQuestion 	= $nbQuestion;
		$this->tempsMaxi 	= $tempsMaxi;
		$this->entete 		= $entete;
		$this->noteMaxi 	= $noteMaxi;
	}
	
	public function toHtml() : void
	{
		echo "<h1>$entete</h1>\n<br/>\n<pre>Note max : $noteMaxi, Temps accordé : $tempsMaxi minutes<pre><br/><br/>";
		foreach( $this->listQ as Q )
			Q->toHTML();
	}
	
	public function __toString() : string
	{
		$ret = "";
		foreach( $this->listQ as Q )
			$ret .= strval( Q );	// équivaut à : Q->__toString();
			
		return $ret;
	}
	
	public function Correction() : void
	{
		foreach( $this->listQ as Q )
		{
			if( Q->Correction() == AUCCUNE_CORRECTION_MANUEL_EFFECTUE )		// OU ENCORE : if( Q instanceOf QLibre )
				Q->AttribuerPoints();// To DO : si la question est libre, il faut déclancher la correction manuel
			else
				$this->note;
		}
	}
	
	public function Generer(int $TypeGenQCM) : void
	{
		// To Do : reconstituer les Question de la BDD en fonction de $TypeGenQCM (aléatoire ou les premières trouver ou ...)
		
		// DEBUG : le cas -1 reconstitue l'ensemble des éléments en BDD
		if( $TypeGenQCM == -1 )
		{
			if( ($res = GetResult("DatabaseQCM", "Question")) instanceof mysqli_result )
			{
				while( $row = mysqli_fetch_assoc($res) )
				{
					/*
					TYPE_REPONSE_UNIQUE 	
					 	
					TYPE_REPONSE_LIBRE 		
					TYPE_REPONSE_CODE		
					*/
					switch( $row['TypeRep'] )
					{
						case Question::TYPE_REPONSE_SIMPLE :
							$listQ[] = new QSimple($row['question'], $row['reponses'], Question::NOTATION_POSITIVE_FRACTION);
						break;
						
						case Question::TYPE_REPONSE_UNIQUE :
							$possibilités = explode(';', $row['possibilites']);
							$listQ[] = new QUnique($row['question'], $possibilités, $row['reponses'], Question::NOTATION_POSITIVE_FRACTION);
						break;
						
						case Question::TYPE_REPONSE_MULTIPLE :
							$possibilités = explode(';', $row['possibilites']);
							$listQ[] = new QMultiple($row['question'], $possibilités, $row['reponses'], Question::NOTATION_POSITIVE_FRACTION);
						break;
						
						case Question::TYPE_REPONSE_LIBRE :
							$listQ[] = new QLibre($row['question'], $row['reponses']);
						break;
						
						default :
							echo "<br/><h2>Type de question inconnue</h2><br/>";
					}
				}
				Close_result( $res );
			}
			else
				echo 'Error get BDD';
		}
	}
}
?>