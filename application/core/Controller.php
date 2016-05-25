<?php

class Controller {

	public function __construct() {

		session_start();
	}

	public function loadModel($model) {

		$path = 'application/models/' . $model . '.php';

		if(file_exists($path)) {

			require_once $path;
			return new $model();
		}
	}
	
	public function view($path, $data = array(), $journal = '') {

		$view = new View();
		$model = new Model();
	
		// Get Navigation array in nested form	
		$navigation = $view->getNavigation(PHY_FLAT_URL);
		// Get folder list in flat form
		$folderList = $view->getFolderList($navigation);
		// Get actual path void of sorting numbers
		$actualPath = $view->getActualPath($path, $folderList);
		// Actual path is given path for dynamic pages
		if(!($actualPath)) $actualPath = $path;
		// Get journal name from URL if not passed
		if ($journal == '') $journal = $view->getJournalFromPath($path);
		// Get current issue details for specific journal
		$current = ($journal) ? $model->getCurrentIssue($journal) : array();
		// Show Page
		(preg_match('/flat\/[^Home]|error|prompt/', $path)) ? $view->showFlatPage($data, $path, $actualPath, $journal, $navigation, $current) : $view->showDynamicPage($data, $path, $actualPath, $journal, $navigation, $current);
	}

	public function isLoggedIn() {

		if(isset($_SESSION['login'])) {

			return ($_SESSION['login'] == 1) ? True : False;
		}
		else {

			return False;
		}
	}

	public function redirect($path) {

		@header('Location: ' . BASE_URL . $path);
	}

	public function absoluteRedirect($path) {

		@header('Location: ' . $path);
	}

}

?>