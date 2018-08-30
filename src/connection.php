<?php 



USE Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;



$capsule->addConnection([

	'driver' => 'mysql',

	'host' => 'us-cdbr-iron-east-04.cleardb.net',

	'database' => 'heroku_004e7f388adb8a1',

	'username' => 'b85e25b16ad8fa',

	'password' => '02e23bd5',

	'charset' => 'utf8',

	'collation' => 'utf8_spanish_ci',

	'prefix' => '',

]);



$capsule->bootEloquent();



$capsule->setAsGlobal();



?>