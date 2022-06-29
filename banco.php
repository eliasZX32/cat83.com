<?php  

/* Conecta ao Banco de dados 
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt');*/


// Se não usa o mysql
$host = '149.62.37.37';
$db   = 'u957426041_cat83';
$user = 'u957426041_cat83';
$pass = 'YJ#%#kCX9ohG';
$port = "3306";
$charset = 'utf8mb4';

$options = [
	\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
	\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
	\PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";


try {
	$pdo = new \PDO($dsn, $user, $pass, $options);
	//echo "Conectou!";

} catch (\PDOException $e) {
	echo $e->getMessage();
	throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
