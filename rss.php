<?php
	header('Content-Type: text/xml');

	require "engine.class.php";
	$engine   = new Engine();
	$settings = json_decode($engine->getSettingsJSON());

	echo '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0"> 
			<channel> 
		    	<title>'. $settings->blog_name . '</title> 
		    	<link>'. $settings->base_url .'/</link> 
		    	<language>'. $settings->language .'</language> 
		    	<description>'. $settings->description .'</description> 
		    	<generator>'. $settings->author .'</generator> 
    ';

	echo $engine->getFeedItems();

	echo '	</channel>
		   </rss>
		 ';