<?php
	header('Content-Type: text/xml');

	require "engine.class.php";
	$engine = new Engine();

	echo '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0"> 
			<channel> 
		    	<title>Nombre de nuestro blog o web</title> 
		    	<link>http://www.miurl.com/</link> 
		    	<language>es-CL</language> 
		    	<description>Descripción de nuestro blog o web</description> 
		    	<generator>Autor del RSS</generator> 
    ';

	echo $engine->getFeedItems();

	echo '	</channel>
		   </rss>
		 ';