<?php

class reports extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		$this->view('flat/Reports/');
	}
}

?>
