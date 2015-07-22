<?php
class errorModule extends Router{
	var $action;
	var $view_path;
	var $view;
	var $module_name = 'error';
	
	function errorModule($action = 'default', $args = false) {
		global $view_path;
		
		$this->action = $action;
		$this->view_path = $view_path;
		$this->view = new View();
		$this->view->module = $this->module_name;
		$this->lang = $this->setLang();
		if (!method_exists($this, $this->action.'Action')) {
			header('location: /'.$this->lang.'/error/notfound/');
		 }
		 else {
			call_user_func_array(array($this, $this->action.'Action'), array($args));
		}
	}
	
	function defaultAction($args) {
		$this->view->setVar('args', $args);
		$this->view->section = 'default';
		$this->view->loadView($this->templatePath(),'index.php');
	}
	
	function notfoundAction($args) {
		$this->view->setVar('args', $args);
		$this->view->section = 'notfound';
		$this->view->loadView($this->templatePath(),'index.php');
	}
	
	function templatePath() {
		return $this->view_path.$this->module_name.'/';
	}
}
?>