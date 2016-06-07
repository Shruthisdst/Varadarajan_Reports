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
			//~ $journal = $data['journal'];
			unset($data['journal']);

			$data = $this->model->preProcessPOST($data);
			$data = $this->model->searchPatches($data);
			
			$query = $this->model->formQuery($data, ' ORDER BY bcode, page ASC');

			$result = $this->model->executeQuery($query);
			($result) ? $this->view('search/result', $result) : $this->view('error/noResults', 'search/index/');
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

}

?>
