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
  
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
	{
	  echo "Not Allowed!";
	  die();
	}

	require "engine.class.php";
	$engine = new Engine();

	$action = $_GET['action'];
	$page   = (!isset($_GET['page'])) ?    1     : (int)$_GET['page'];
	$from   = (!isset($_GET['from'])) ?    0     : (int)$_GET['from'];
	$to     = (!isset($_GET['to'])) ?      0     : (int)$_GET['to'];
	$first  = (!isset($_GET['first'])) ?   false : (bool)$_GET['first'];

	switch ($action) 
	{

		case 'menu':
			echo $engine->getPostsJson($page, true);
			break;	

		case 'posts':
			echo $engine->getPostsJson($page);
			break;	

		case 'range':
			echo $engine->getRangePostsJson($from, $to, $first);
			break;						
		
		default:
			echo "No action!";
			break;
	}	