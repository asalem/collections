<?php

class shawweetModule extends Router{
	var $module_name = 'shawweet';
	var $action;
	var $args;
	var $view;
	
	function shawweetModule($action = 'default', $args = false) {

		$this->action = $action;
		$this->args = $args ;
		if (!method_exists($this, $this->action.'Action')) {
			header('location: /'.$this->lang.'/error/notfound/');
		 } else {
			call_user_func_array(array($this, $this->action.'Action'), array($args));
		}
	}
	
	function getTodayLeaguesAction (){
		print($this->args[MATCH_DATE]);
		print 'hello' ;
	}
	
	function defaultAction($args) {
		//to trigger and error 403
	}
	
	

}
?>