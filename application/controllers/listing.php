<?php

class listing extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->issues();
	}

	public function books($bcode = DEFAULT_BCODE) {

		$Title = $this->model->listTitle($bcode);
		//~ var_dump($Title);
		($Title) ? $this->view('listing/books', $Title) : $this->view('error/index');
	}
}

?>
