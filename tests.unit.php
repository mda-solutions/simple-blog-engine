<?php
	
	require "engine.class.php";
	$engine = new Engine();	
	$posts  = json_decode($engine->getRangePostsJson(1, 3));

	foreach ($posts as $post) 
	{
		echo $post->id . "\n";
	}


