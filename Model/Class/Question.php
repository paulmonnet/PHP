<?php
require_once('../../Common/Database.php');

interface TemplateDisplay
{
    public function toHtml() : void;		// forcer l'implémentation d'une fonction permettant la visualisation de l'objet sous format HTML
    public function __toString() : string;	// forcer l'implémentation d'une fonction permettant la visualisation de l'objet sous format Text
}


abstract class Question implements TemplateDisplay
{
	public const TYPE_REPONSE_SIMPLE		= 1;	// réponse de type vrai ou faux
	public const TYPE_REPONSE_UNIQUE 		= 2;	// une réponse possible parmi plusieurs réponse
	public const TYPE_REPONSE_MULTIPLE 		= 3;	// plusieurs réponse possible 
	public const TYPE_REPONSE_LIBRE 		= 4;	// réponse libre : du texte
	public const TYPE_REPONSE_CODE			= 5;	// un espace dédié à du code source
	
	public const NOTATION_POSITIVE_STRICTE  =  2;	// Seul l'ensemble des bonne réponse confère les points, 0 sinon
	public const NOTATION_POSITIVE_FRACTION =  1;	// les éléments correctes sont comptabilisé et se soustrait en cas d'élément incorrect mais ne descend pas en dessous de 0)
	public const NOTATION_POSITIVE_NEGATIVE =  3;	// les éléments s'ajoute en cas de bonne réponse et se soustrait en cas d'éléments erroné avec résultat négatif possible
	public const NOTATION_NEGATIVE_STRICTE  = -1;	// un éléments de réponse incorecte implique immédiatement la note négative
	//protected const NOTATION_AUTO_IMPOSSIBLE = 99;	// notation spécifique pour la notation de question libre nécessitant une notation manuel
	
	protected 	$str_question;						// la question
	protected 	$path_illustration;					// le chemin vers une image facultative (ou code)
	protected 	$possibilités;						// la liste de réponses possible ! (en fonction du type de la question)
	protected 	$réponses;							// la / les réponses à la question !
	protected 	$difficulté;						// le degré de difficulté de 0 à 5 (5 étant la difficulté maximal)
	protected 	$tempsEstimatif;					// temps estimé / accordé pour répondre à la question en secondes !
	protected 	$pointMaxi;							// les points maximal attribué pour cette question
	protected 	$typeNotation;						// la notation à appliquer poir la correction de la question (négative si erreur, 0 si erreur, 0 si réponse partiel ou fractionné, etc...)
	private   	$typeRep;							// le type de la réponse
	private		$réponsesFournies;					// les réponse saisie par l'utilisateur final
	
	// attributs optionnel
	private 	$rubrique;					// rubrique informatique par exemple
	private 	$section;					// PHP par exemple

	function __construct( int 		$typeRep,
						  string	$str_question,
						  array 	$possibilités,		// les réponses possibles sous forme de tableau itératif
						  array 	$réponses,			// les index vers le tableau de réponses
						  int		$typeNotation		= self::NOTATION_POSITIVE_FRACTION,
						  float		$pointMaxi 			= 1,	// 1 point par défaut
						  int 		$tempsEstimatif 	= 120,	// 2 minutes par défaut
						  int		$difficulté 		= 0,	// facile
						  string 	$path_illustration 	= '')
	{
		$this->str_question 		= $str_question;
		$this->path_illustration	= $path_illustration;
		$this->possibilités			= $possibilités;
		$this->réponses				= $réponses;
		$this->difficulté			= $difficulté;
		$this->tempsEstimatif		= $tempsEstimatif;
		$this->pointMaxi			= $pointMaxi;
		$this->typeRep				= $typeRep;
		$this->typeNotation			= $typeNotation;
	}
	
	/*******************************************
	/************   GET Functions   ************/
	
	public function Question() : string {
		return $str_question;
	}
	
	public function Réponses() : array {
		return $réponses;
	}
	
	public function Possibilités() : array {
		return $possibilités;
	}
	
	public function Illustration() : string {
		$path_illustration;
	}
	
	public function Difficulté() : int {
		return $difficulté;
	}
	
	public function TempsEstimatif() : int {
		return $tempsEstimatif;
	}
	
	public function PointMaxi() : int {
		return $pointMaxi;
	}
	
	public function Rubrique() : string {
		return $rubrique;
	}
	
	public function Section() : string {
		return $section;
	}
	
	public function RéponsesFournies() : array {
		return $réponsesFournies;
	}
	
	/*******************************************/
	
	/*******************************************
	/************   SET Functions   ************/
	
	public function SetRubrique(string $rubrique) : void {
		$this->rubrique = $rubrique;
	}
	
	public function SetSection(string $section) : void {
		$this->section = $section;
	}
	
	public function Répondre(array $réponsesFournies) : void {
		$this->réponsesFournies = $réponsesFournies;
	}
	
	/*******************************************/
	
	// affichage de la question et des réponses possible sous format HTML simplifié et commun
	// __toString() est appelé par nativement par strval() !
	public function __toString() : string
	{
		$ret = "<pre><b>$this->str_question</b> ($this->pointMaxi point(s) - $this->tempsEstimatif secondes)\n";
		foreach($this->possibilités as $index => $rep)
			$ret .= "\t".chr($index+65)." - $rep\n";
			
		return $ret."</pre>";
	}
	
	protected function DisplayQuestion() : void
	{
		echo "<pre><b>$this->str_question</b> ($this->pointMaxi point(s) - $this->tempsEstimatif secondes)\n";
	}
	
	public function Enregistrer() : void
	{
		$_possibilités = implode(';', $this->possibilités);
		$_réponses = implode(';', $this->réponses);
		$req = "INSERT INTO `question` (`question`, `possibilites`, `reponses`, `difficulte`, `tempsEstimatif`, `pointMaxi`, `typeNotation`, `TypeRep`) VALUES ( '$this->str_question', '$_possibilités', '$_réponses', '$this->difficulté', '$this->tempsEstimatif', '$this->pointMaxi', '$this->typeNotation', '$this->typeRep')";
		PerformeQueryOnly('databaseQCM', $req, true, true);
	}
	
	abstract public function ToHTML() : void;			// affichage de la question et des réponses possible sous format HTML
	abstract public function correction() : float;		// retourne les points obtenu à la question pour la réponse $répondu
}



// Question de type vrai ou faux
class QSimple extends Question
{
	public const INDEX_VRAI = 0;
	public const INDEX_FAUX = 1;
	
	function __construct( string	$str_question,
						  int 		$réponse,
						  int		$typeNotation		= self::NOTATION_POSITIVE_FRACTION,
						  float		$pointMaxi 			= 1,	// 1 point par défaut
						  int 		$tempsEstimatif 	= 120,	// 2 minutes par défaut
						  int		$difficulté 		= 1,	// très facile
						  string 	$path_illustration 	= '')
	{
		parent::__construct(self::TYPE_REPONSE_SIMPLE, $str_question, ['Vrai', 'Faux'], [$réponse], $typeNotation, $pointMaxi, $tempsEstimatif, $difficulté, $path_illustration);
	}
	
	public function Correction() : float
	{
		$ptErr = 0;
		if( $this->typeNotation == self::NOTATION_NEGATIVE_STRICTE )
			$ptErr = 0 - $this->pointMaxi;
		
		if( count($réponseFournie) < 1 )
			return $ptErr;
		
		return ( $réponseFournie[0] == $this->$réponse ) ? $this->pointMaxi : $ptErr;
	}
	
	public function ToHTML() : void
	{
		$hashCode = hash('crc32', $this->str_question);
		$this->DisplayQuestion();
		
		echo "\t<input type='radio' name='n$hashCode' id='A$hashCode' value='".self::INDEX_VRAI."' /><label for='A$hashCode'>A - VRAI</label>\n"
			."\t<input type='radio' name='n$hashCode' id='B$hashCode' value='".self::INDEX_FAUX."' /><label for='B$hashCode'>B - FAUX</label>\n"
			."</pre>\n";
	}
}



// Question à choix multiple
class QMultiple extends Question
{
	function __construct( string	$str_question,
						  array 	$possibilités,		// les réponses possibles sous forme de tableau itératif
						  array 	$réponses,			// les index vers le tableau de réponses
						  int		$typeNotation		= self::NOTATION_POSITIVE_FRACTION,
						  float		$pointMaxi 			= 1,	// 1 point par défaut
						  int 		$tempsEstimatif 	= 120,	// 2 minutes par défaut
						  int		$difficulté 		= 2,	// facile
						  string 	$path_illustration 	= '')
	{
		parent::__construct(self::TYPE_REPONSE_MULTIPLE, $str_question, $possibilités, $réponses, $typeNotation, $pointMaxi, $tempsEstimatif, $difficulté, $path_illustration);
	}
	
	// les réponses fournies sont ordonné et corresponde à l'ordre des réponse attendues
	public function Correction() : float
	{
		switch( $this->typeNotation )
		{
			case self::NOTATION_POSITIVE_STRICTE :
			case self::NOTATION_NEGATIVE_STRICTE :
			{
				$ptErr = 0 - $this->pointMaxi;
				if( $this->typeNotation == self::NOTATION_POSITIVE_STRICTE )
					$ptErr = 0;
					
				if( count($this->réponses) != count($réponsesFournies) )
					return $ptErr;
				
				foreach( $this->réponses as $index => $repAttendue )
				{
					if( $réponsesFournies[$index] != $repAttendue )
						return $ptErr;
				}
				
				return $this->pointMaxi;
			}
			break;
			
			case self::NOTATION_POSITIVE_FRACTION :
			case self::NOTATION_POSITIVE_NEGATIVE :
			{
				$ptFract = $this->pointMaxi / count($this->réponses);
				$ptTotal = 0.0;
				
				foreach( $this->réponses as $index => $repAttendue )
				{
					if( $réponsesFournies[$index] == $repAttendue )
						$ptTotal += $ptFract;
					else
						$ptTotal -= $ptFract;
				}
				
				if( ($this->typeNotation == self::NOTATION_POSITIVE_FRACTION) && ($ptTotal < 0) )
					$ptTotal = 0;
				
				return $ptTotal;
			}
			break;
			
			default:
				return 0;
		}
	}
	
	public function ToHTML() : void
	{
		$hashCode = hash('crc32', $this->str_question);
		$this->DisplayQuestion();
		
		foreach($this->possibilités as $index => $rep)
		{
			$lettre = chr($index+65);
			echo "\t<input type='checkbox' name='chk$lettre$hashCode' id='$lettre$hashCode' value='$index' /><label for='$lettre$hashCode'>$lettre - $rep</label>\n";
		}
		
		echo "</pre>\n";
	}
}



// Question à choix multiple mais une seul réponse possible
class QUnique extends Question
{
	function __construct( string	$str_question,
						  array		$possibilités,		// les réponses possibles sous forme de tableau itératif
						  int		$réponse,			// l'index vers le tableau de réponses
						  int		$typeNotation		= self::NOTATION_POSITIVE_FRACTION,
						  float		$pointMaxi 			= 1,	// 1 point par défaut
						  int 		$tempsEstimatif 	= 120,	// 2 minutes par défaut
						  int		$difficulté 		= 1,	// très facile
						  string 	$path_illustration 	= '')
	{
		parent::__construct(self::TYPE_REPONSE_UNIQUE, $str_question, $possibilités, [$réponse], $typeNotation, $pointMaxi, $tempsEstimatif, $difficulté, $path_illustration);
	}
	
	public function Correction() : float
	{
		$ptErr = 0;
		if( $this->typeNotation == self::NOTATION_NEGATIVE_STRICTE )
			$ptErr = 0 - $this->pointMaxi;
		
		if( count($réponseFournie) < 1 )
			return $ptErr;
		
		return ( $réponseFournie[0] == $this->$réponses[0] ) ? $this->pointMaxi : $ptErr;
	}
	
	public function ToHTML() : void
	{
		$hashCode = hash('crc32', $this->str_question);
		$this->DisplayQuestion();
		
		foreach($this->possibilités as $index => $rep)
		{
			$lettre = chr($index+65);
			echo "\t<input type='radio' name='radio$hashCode' id='$lettre$hashCode' value='$index' /><label for='$lettre$hashCode'>$lettre - $rep</label>\n";
		}
		
		echo "</pre>\n";
	}
}




// Question libre, correspond à générer de l'espace suffisant pour répondre librement
class QLibre extends Question
{
	public const AUCCUNE_CORRECTION_MANUEL_EFFECTUE = -9;
	
	private $PointsAttribuéParLeCorrecteur;
	private $height;
	private $width;
	
	function __construct( string	$str_question,
						  string 	$réponse,			// réponse attendu pour une correction manuel
						  int		$width				= 80,
						  int		$height				= 10,
						  float		$pointMaxi 			= 2,	// 2 point par défaut
						  int 		$tempsEstimatif 	= 360,	// 6 minutes par défaut
						  int		$difficulté 		= 4,	// normal
						  string 	$path_illustration 	= '')
	{
		$this->PointsAttribuéParLeCorrecteur 	= self::AUCCUNE_CORRECTION_MANUEL_EFFECTUE;
		$this->height 							= $height;
		$this->width 							= $width;
		parent::__construct(self::TYPE_REPONSE_LIBRE, $str_question, [], [$réponse], self::NOTATION_AUTO_IMPOSSIBLE, $pointMaxi, $tempsEstimatif, $difficulté, $path_illustration);
	}
	
	public function AttribuerPoints(int $pt)
	{
		$this->PointsAttribuéParLeCorrecteur = $pt;
	}
	
	// les réponses fournies sont ordonné et corresponde à l'ordre des réponse attendues
	public function Correction() : float
	{
		return $PointsAttribuéParLeCorrecteur;
	}
	
	public function ToHTML() : void
	{
		$hashCode = hash('crc32', $this->str_question);
		$this->DisplayQuestion();
		
		echo "\t<textarea name='$hashCode' id='$hashCode' cols='$this->width' rows='$this->height'></textarea>\n</pre>\n";
	}
}
?>