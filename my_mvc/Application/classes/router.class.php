<?php
class Router {
	public $module_path;
	public $segments = array();
	public $module = 'shawweet';
	public $action = 'default';
	public $args = false;
	public $request;
	public $ignore = array();


	function Router($request) {
		global $module_path;	
		
		$this->request = $request;
		$url_array = explode('/', $this->request);
		//shawweet?COMPETION_ID_KEYS=1,2,3,4,5,7,9&MATCH_DATE=2011-09-20&SERVICE=getTodayLeaguesMatchs
		
		//remove the findeverything directory [temp solution] // after production deployment we can remove this line
		unset($url_array[0]);unset($url_array[1]);
		$this->segments = array_filter($url_array);		
		//rebuild the indexes
		sort($this->segments) ;

		$temp = explode ('?',$this->segments[0]) ;
	
		parse_str($temp[1],$arr);

		
		$this->segments = $arr ; 
		$this->module = $this->setModule($temp[0]);
		//Added en,ar for languages [we can add more in Future]
		$this->ignore = array('Application', 'Views', 'Modules','findeverything');
		$this->action = $this->setAction();
		$this->args = $this->setArgs();
		$this->module_path = $module_path.$this->module.'Module.php';
		//var_dump($this->module_path);
		/*if ($this->isExecuteable()) {
			if (!file_exists($this->module_path)) {
				//header 403 Forbidden
				header('location: /findeverything/'.$this->lang.'/error/notfound');
			}
		}*/
		
	}
	
	
	function setModule($moduleName) {
		return (isset($moduleName) && !empty($moduleName) ) ? $moduleName : $this->module ;
	}
	
	function setAction() {
		return (isset($this->segments["SERVICE"]) && !empty($this->segments["SERVICE"]) && !in_array($this->segments["SERVICE"], $this->ignore) ) ? $this->segments["SERVICE"] : $this->action ;
	}
	
	function setArgs() {
		//enhanced version to remove the Module and the action and return only the arguments
		return $this->segments ;
	}
	
	function isExecuteable() {
		if (in_array($this->module, $this->ignore) || empty ($this->module)) {
			return false;
		} else {
			return true;
		}
	}
	
	function Execute() {
		if ($this->isExecuteable()) {
			require_once($this->module_path);
			$classname = $this->module.'Module';
			$module = new $classname($this->action, $this->args);
		}
	}
}
?>