<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for your application.
| It's a breeze. Just tell Illuminate the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

$app->get('/', function() use ($app)
{
	return $app->blade->render('hello.blade.php');
});