<?php

define('HOST', 'localhost');
define('USER', 'root');
define('PWD',  '');

// test mysqli procédural
function test_mysqli_proc(?string $DbName, ?string $Table) : bool
{
	$ret = true;
	
	$link = mysqli_connect(HOST, USER, PWD, $DbName);
	if( $err = mysqli_connect_errno() )
	{
		echo "impossible de se connecter a la base $DbName via mysqli procédural : $err";
		return false;
	}
	
	$res = mysqli_query($link, "SELECT * FROM $Table");	// return un result à clore ou un bool si CREATE / INSERT / UPDATE / DELETE
	if( !$res )
	{
		echo "echec mysqli_query !!";
		$ret = false;
	}
	else
	{
		echo "La table $Table contiens ".mysqli_num_rows($res)." ligne(s) (mysqli procédural)<br/>";
		
		while( $row = mysqli_fetch_assoc($res) )
			print_r( $row );
		
		mysqli_free_result( $res );		// fermer le result Query !!
	}
	
	mysqli_close( $link );				// fermer le lien BDD !!
	
	return $ret;
}

// mysqli POO
function test_mysqli_obj(?string $DbName, ?string $Table) : bool
{
	$ret = true;
	
	$mysqliObj = new mysqli(HOST, USER, PWD, $DbName);
	if( $err = $mysqliObj->connect_errno )
	{
		echo "impossible de se connecter a la base $DbName via mysqli POO : $err";
		return false;
	}
	
	$res = $mysqliObj->query("SELECT * FROM $Table");	// return un result à clore ou un bool si CREATE / INSERT / UPDATE / DELETE
	if( !$res )	
	{
		echo "echec mysqli_query POO !!";
		$ret = false;
	}
	else
	{
		echo "La table $Table contiens ".$res->num_rows." ligne(s) (mysqli POO)<br/>";
		
		while( $row = $res->fetch_assoc() )
		{
			print_r( $row );
		}
		
		$res->close();				// fermer le result Query !!
	}
	
	$mysqliObj->close();			// fermer le lien BDD !!
	
	return $ret;
}

// PDO
function test_PDO(?string $DbName, ?string $Table) : bool
{
	$ret = true;
	
	try
	{
		$user = "root";
		$pwd = "";
		$pdo = new PDO('mysql:host='.HOST.';dbname='.$DbName, USER, PWD	);
	}
	catch( PDOException $Exception )
	{
		echo "impossible de se connecter a la base $DbName via PDO : ".$Exception->getMessage();
		return false;
	}
	$Statement = $pdo->query("SELECT * FROM $Table");
	if( !$Statement )
	{
		echo "echec query PDO !!<br/>";
		print_r( $statement->errorInfo() );
		$ret = false;
	}
	else
	{
		echo "La table $Table contiens ".$Statement->rowCount()." ligne(s) (PDO)<br/>";
		
		while( $row = $Statement->fetch(PDO::FETCH_ASSOC) )
			print_r( $row );
	}
	
	return $ret;
}









function Start_Mysqli(?string $DbName) //: mysqli|bool
{
	$link = mysqli_connect(HOST, USER, PWD, $DbName);
	if( $err = mysqli_connect_errno() )
	{
		echo "impossible de se connecter a la base $DbName via mysqli procédural : $err<br/>";
		return false;
	}
	
	return $link;
}

function Stop_Mysqli(?mysqli& $link) : bool
{
	$ret = mysqli_close( $link );		// fermer le lien BDD !!
	if( $ret === false )
		echo "Fermeture du lien BDD en échec !!<br/>";
	
	return $ret;
}

function Close_result(mysqli_result $res) : void
{
	mysqli_free_result( $res );
}

function PerformeQuery(?mysqli& $link, ?string& $req, ?bool $Print = false, ?bool $Log = false) //: mysqli_result|bool
{
	if( $link === false )
	{
		echo "Erreur PerformeQuery : la connection n'est pas établie ou \$link est erroné<br/>";
		return false;
	}
	
	$res = mysqli_query($link, $req);	// return un result à clore ou un bool si CREATE / INSERT / UPDATE / DELETE
	if( is_bool($res) )
	{
		if( $res === false )
		{
			echo "echec mysqli_query : $req <br/>";
			return false;
		}
		else
			echo "mysqli_query réussi : $req <br/>";
	}
	else								// cas result (Select)
	{
		if( $Print )
		{
			while( $row = mysqli_fetch_assoc($res) )
				print_r( $row );
			
			mysqli_free_result( $res );	// fermer le result Query !!
		}
		else
			return $res;				// ATTENTION, il faudra clore le 'mysqli_result' $res
	}
	
	return true;
}

function PerformeQueryOnly(?string $DbName, ?string $req, ?bool $Print = false, ?bool $Log = false) : void
{
	$link = Start_Mysqli( $DbName );
	if( is_bool($link) )
	{
		if( $Log )
			echo "link est un bool : $link";
		
		if( !$link )
			return;						// on arrête si la connection à échoué
	}
	
	$res = PerformeQuery($link, $req, $Print, $Log);
	
	Stop_Mysqli( $link );
}

function GetResult(?string $DbName, ?string $TableName, ?string $clause = '1=1', ?bool $Log = false) //: mysqli_result|bool
{
	$link = Start_Mysqli( $DbName );
	if( is_bool($link) )
	{
		if( $Log )
			echo "link est un bool : $link";
		
		if( !$link )
			return;						// on arrête si la connection à échoué
	}
	
	$req = "SELECT * FROM $TableName WHERE $clause";
	$res = PerformeQuery($link, $req, false, $Log);
	if( ($res instanceof mysqli_result) && (mysqli_num_rows($res) > 0) )
		$ret = $res;					// ATTENTION, il faudra clore le 'mysqli_result' $res
	else
		$ret = false;
	
	Stop_Mysqli( $link );
	
	return $ret;
}

function RowExist(?string $DbName, ?string $TableName, ?string $clause, ?bool $Log = false) : bool
{
	$res = GetResult($DbName, $TableName, "$clause LIMIT 1", $Log);
	$ret = ( $res instanceof mysqli_result );
	
	if( $ret )
		Close_result( $res );
	
	return $ret;
}

function GetElementFromResult(?mysqli_result $res, ?string $element) : string
{
	if( $res instanceof mysqli_result )
	{
		$row = mysqli_fetch_assoc($res);
		return $row[$element];
	}
	
	return "";
}

?>