<?php
	/**
	 * created by Abdelrahman salem 
	 * for instant Search
	 */
	$class_path = dirname(__FILE__).'/Application/classes/';
	$module_path = dirname(__FILE__).'/Modules/';
	$view_path = dirname(__FILE__).'/Views/';
	
	include $module_path.'xss.php' ; 
	include $class_path.'router.class.php' ;
	include $class_path.'view.class.php' ;
	
	$router = new Router($_SERVER['REQUEST_URI']);

	$router->Execute();