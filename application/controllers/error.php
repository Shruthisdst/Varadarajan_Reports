<?php

class error extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		$this->view('error/index');
	}

	public function noResults() {
		
		$this->view('error/noResults');
	}

	public function blah() {
		
		$this->view('error/blah');
	}

	public function prompt($data = array()) {
		
		$this->view('error/prompt', $data);
	}
}

?>