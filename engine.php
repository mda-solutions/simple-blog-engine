<?php

	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
	{
	  //echo "Not Allowed!";
	  //die();
	}

	require "engine.class.php";
	$engine = new Engine();

	$action = $_GET['action'];

	switch ($action) 
	{
		case 'menu':
			echo $engine->getListPostsJSON();
			break;

		case 'posts':
			echo $engine->getPosts();
			break;			
		
		default:
			echo "No action!";
			break;
	}	