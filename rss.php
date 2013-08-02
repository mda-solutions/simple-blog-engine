<?php
/**
 * 
 *     repo:   https://github.com/mda-solutions
 *     author: moises.rangel@gmail.com
 *
 * Licensed under the MIT License (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://opensource.org/licenses/MIT
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
  
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