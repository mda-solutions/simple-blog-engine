<?php

	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
	{
	  echo "Not Allowed!";
	  die();
	}

	require "engine.class.php";
	$engine = new Engine();

	$action = $_GET['action'];
	$page   = (!isset($_GET['page'])) ? 1 : (int)$_GET['page'];

	switch ($action) 
	{

		case 'menu':
			echo $engine->getPostsJson($page, true);
			break;	

		case 'posts':
			echo $engine->getPostsJson($page);
			break;	

		case 'range':
			echo $engine->getRangePostsJson($from, $to);
			break;						
		
		default:
			echo "No action!";
			break;
	}	