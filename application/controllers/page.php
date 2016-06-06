<?php

class page extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		$this->view('flat/Home/index');
	}

	public function flat() {
		$data = func_get_args();
		
		if($data[0] == "Home")
		{
			$this->redirect('listing/books/01');
		}
		else
		{
			$path = 'flat/' . implode('/', $data);
			$this->view($path);
		}
	}
}

?>
