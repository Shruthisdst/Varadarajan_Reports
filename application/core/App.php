<?php

class App{

	protected $controller = 'page';

	protected $method = 'flat';

	protected $params = array();
	
	public function __construct() {

		$url = $this->parseUrl();

		if(file_exists('application/controllers/' . $url[0] . '.php')) {

			$this->controller = $url[0];
		}
		else{
			// If controller and in tuen method is not specified in the URL, append default controller and method names to the beginning of the URL array
			array_unshift($url, $this->method);
			array_unshift($url, $this->controller);
		}

		require_once 'application/controllers/' . $this->controller . '.php';

		$this->controller = new $this->controller;
		$this->controller->model = $this->controller->loadModel($url[0] . 'Model');
		unset($url[0]);

		if(isset($url[1])) {

			if(method_exists($this->controller, $url[1])) {

				$this->method = $url[1];
				unset($url[1]);
			}
		}

		$this->params = $url ? array_values($url) : array();

		call_user_func_array(array($this->controller, $this->method), $this->params);
	}

	public function parseUrl() {

		if(isset($_GET['url'])) {

			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_SPECIAL_CHARS));
		}
		else{
			// If url does not have any parameters, then it is assumed that it will be the home page and hence 'Home' is returned 
			return array('Home');
		}
	}

}

?>