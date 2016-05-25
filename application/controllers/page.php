<?php

class page extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		$this->view('flat/Home/index');
	}

	public function flat() {

		$path = 'flat/' . implode('/', func_get_args());
		$this->view($path);
	}
}

?>