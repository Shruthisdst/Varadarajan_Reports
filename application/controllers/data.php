<?php

class data extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index($cms = 'journal') {

		if ($cms == 'journal') $this->view('data/journal');
	}

	public function insertMetadata($journal = DEFAULT_JOURNAL) {

		$metaData = $this->model->getMetadaData($journal);

		if ($metaData) {

			$this->model->db->createDB($journal, JOURNAL_DB_SCHEMA);

			$dbh = $this->model->db->connect($journal);
			
			$this->model->db->dropTable(METADATA_TABLE, $dbh);

			$this->model->db->createTable(METADATA_TABLE, $dbh, METADATA_TABLE_SCHEMA);

			foreach ($metaData as $row) {
	
				$this->model->db->insertData(METADATA_TABLE, $dbh, $row);
			}
		}
		else{

			$this->view('error/blah');
		}
	}
	
	public function insertFulltext($journal = DEFAULT_JOURNAL) {
		
		$this->model->db->createDB($journal, JOURNAL_DB_SCHEMA);

		$dbh = $this->model->db->connect($journal);

		$this->model->db->dropTable(FULLTEXT_TABLE, $dbh);

		$this->model->db->createTable(FULLTEXT_TABLE, $dbh, FULLTEXT_TABLE_SCHEMA);
		
		$this->model->getFulltextAndInsert($journal, $dbh);
		// Create Fulltext index
		$this->model->db->executeQuery($dbh, FULLTEXT_INDEX_SCHEMA);
	}


	private function updateAll($journal, $user) {

		$statusMsg = array();

		$repo = Git::open(PHY_BASE_URL . '.git');

		// Before all operations, a git pull is done to sync local and remote repos.
		$repo->run('pull ' . GIT_REMOTE . ' master');
		array_push($statusMsg, 'Repo synced with remote');
		
		// Get all files (added, deleted, modified) by doing a git status and put them in separate bins
		// Fles will have A,M,D and F as keys
		$files = $this->model->getChangesFromGit($repo, $journal);
		array_push($statusMsg, 'Files to be updated listed');

		$dbh = $this->model->db->connect($journal);

		// Both type A and M files follow the same procedure
		foreach (array_merge($files['A'], $files['M']) as $file) {

			$metaData = $this->model->getMetadaDataFromXML($file);
			
			if($metaData) {

				$this->model->db->deleteDataByID(METADATA_TABLE, $dbh, $metaData['id']);
				$this->model->db->insertData(METADATA_TABLE, $dbh, $metaData);
			}
			else {

				$this->view('error/blah');
			}

		}

		// Delete metadata
		foreach ($files['D'] as $file) {

			$file = preg_replace('/public\/Volumes\/|\.xml/', '', $file);
			$id = str_replace('/', '_', $file);

			$this->model->db->deleteDataByID(METADATA_TABLE, $dbh, $id);
		}

		// Insert forthcoming articles by dropping existing data
		if($files['F']) $this->insertForthcoming($journal);
	
		array_push($statusMsg, 'Addtion / Modification / Deletion of published and forthcoming articles completed');

		// Insert text into FullText database and create indexes
		// if ($files['A'] || $files['M'] || $files['D']) $this->insertFulltext($journal);
		// array_push($statusMsg, 'Search indexes rebuilt');

		$dbh = null;

		// Add and commit and changes to git server
		if($files['A']) $this->model->gitProcess($repo, $journal, $files['A'], 'add', GIT_ADD_MSG, $user);
		if($files['M']) $this->model->gitProcess($repo, $journal, $files['M'], 'add', GIT_MOD_MSG, $user);
		if($files['D']) $this->model->gitProcess($repo, $journal, $files['D'], 'rm', GIT_DEL_MSG, $user);
		if($files['F']) $this->model->gitProcess($repo, $journal, $files['F'], 'addAll', GIT_FORTH_MSG, $user);
		
		// Push commits to the Git server
		$repo->run('push ' . GIT_REMOTE . ' master');
		
		array_push($statusMsg, 'Local changes pushed to remote');

		echo $this->model->formatStatus($statusMsg);
	}

	public function verifyAndUpdate() {

		$journal = $_POST['journal'];
		$user['email'] = $_POST['email'];
		$user['password'] = $_POST['password'];
		
		$split = explode('@', $_POST['email']);
		$user['name'] = $split[0];

		if ($this->model->verifyUser($journal, $user)) {

			$this->updateAll($journal, $user);
		}
		else{

			echo 'False';
		}
	}

	public function updateCompleted($type = 'journal') {

		$data = array();
		
		if($type == 'journal') {
			array_push($data, CMS_MSG_STEP1);
			array_push($data, CMS_MSG_STEP2);
			array_push($data, CMS_MSG_STEP3);
			array_push($data, CMS_MSG_STEP4);
			array_push($data, CMS_MSG_STEP5);
		}

		$this->view('data/taskCompleted', $data, '');
	}
}

?>
