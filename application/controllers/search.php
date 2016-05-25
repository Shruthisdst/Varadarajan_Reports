<?php

class search extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index($journal = DEFAULT_JOURNAL) {
		
		if(file_exists('application/views/search/' . $journal . '.php')) {
		    
		    $this->view('search/' . $journal, array(), $journal);
		}
		else {
	
		    $this->view('search/index', array(), $journal);
		}
	}

	public function doSearch() {
		
		$data = $this->model->getPostData();

		// Check if any data is posted. For this journal name should be excluded.
		$checkData = $data;unset($checkData['journal']);

		if($checkData) {
			
			// Journal name is passed using a hidden input element in the search form
			$journal = $data['journal'];
			unset($data['journal']);

			$data = $this->model->preProcessPOST($data);
			$data = $this->model->searchPatches($data);
			
			$query = $this->model->formQuery($data, ' ORDER BY volume DESC, issue DESC');
			$result = $this->model->executeQuery($query, $journal);
			($result) ? $this->view('search/result', $result, $journal) : $this->view('error/noResults', 'search/index/', $journal);
		}
		else {

			$this->redirect('search/index');
		}
	}

	public function allJournals() {
		
		$data = $this->model->getPostData();
		$data = $this->model->preProcessPOST($data);
		$data = $this->model->searchPatches($data);
		if($data) {

			$query = $this->model->formQuery($data);
		
			$view = new View();
			$journals = $view->journalFullNames;

			$result = array();
			foreach ($journals as $journal => $journalName) {

				$journalWiseResult = $this->model->executeQuery($query, $journal);
				if($journalWiseResult) $result{$journal} = $journalWiseResult;
			}
			($result) ? $this->view('search/allJournalsResult', $result) : $this->view('error/noResults', 'page/flat/Journals/Overview/');
		}
		else{

			$this->redirect('page/flat/Journals/Overview/');
		}
	}

	public function fellow() {
		
		$data = $this->model->getPostData();
		if($data) {
			
			$data = $this->model->preProcessPOST($data);
			$query = $this->model->formGeneralQuery($data, FELLOW_TABLE, ' ORDER BY lname, fname');

			// Search for both bengaluru and bangalore should work
			$query['words']  = $this->model->handleSpecialCases($query['words']);

			$result = $this->model->executeQuery($query, GENERAL_DB_NAME);
			if($result) {
			
				$result['typeTitle'] = 'Fellowship: Search Results';
				$result['type'] = 'search';
			}
	
			($result) ? $this->view('listing/fellows', $result) : $this->view('error/noResults', 'page/flat/Initiatives/Fellowship/');
		}
		else {

			$this->redirect('page/flat/Initiatives/Fellowship/');
		}
	}

	public function associate() {
		
		$data = $this->model->getPostData();
		if($data) {
			
			$data = $this->model->preProcessPOST($data);
			$query = $this->model->formGeneralQuery($data, ASSOCIATE_TABLE);

			// Search for both bengaluru and bangalore should work
			$query['words']  = $this->model->handleSpecialCases($query['words']);

			$result = $this->model->executeQuery($query, GENERAL_DB_NAME);
			if($result) $result['typeTitle'] = 'Associateship: Search Results';
			($result) ? $this->view('listing/associates', $result) : $this->view('error/noResults', 'page/flat/Initiatives/Associateship/');
		}
		else {

			$this->redirect('page/flat/Initiatives/Associateship/');
		}
	}
}

?>