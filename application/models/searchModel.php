<?php

class searchModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function formQuery($data, $orderBy = '') {

		$textArray = array();
		$textQuery = '';
	
		$filter = '';
		$words = array();

		if(isset($data['fulltext'])) {

			$filter = 'id IN (SELECT id FROM fulltextsearch WHERE MATCH (text) AGAINST (? IN BOOLEAN MODE))';
			array_push($words, $data['fulltext']);
			unset($data['fulltext']);
		}

		$data = $this->regexFilter($data);

		if($filter != '') array_unshift($data['filter'], $filter);
		$data['words'] = array_merge($words, $data['words']);


		$sqlFilter = (count($data['filter'] > 1)) ? implode(' and ', $data['filter']) : array_values($data['filter']);
		$sqlStatement = 'SELECT * FROM ' . METADATA_TABLE . ' WHERE ' . $textQuery . $sqlFilter . $orderBy;

		$data['query'] = $sqlStatement;
		$data['words'] = array_merge($textArray, $data['words']);

		return $data;
	}

	public function executeQuery($data, $journal = DEFAULT_JOURNAL) {

		$dbh = $this->db->connect($journal);

		$sth = $dbh->prepare($data['query']);
		$sth->execute($data['words']);

		$data = null;
		$i = 0;
		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}
		$dbh = null;

		return $data;
	}

	public function regexFilter($var) {

		$data['filter'] = array();
		$data['words'] = array();

		if (empty($var)) return $data;

		while (list($key, $val) = each($var)) {

			$filterArr = array();

			$val = html_entity_decode($val, ENT_QUOTES);

			// Only paranthesis and hyphen will be quoted to include them in search
		    $val = preg_replace('/(\(|\)|\-)/', "\\\\$1", $val);
		    $words = preg_split('/ /', $val);
		    $words = array_filter($words, 'strlen');
		    
			$data['words'] = array_merge($data['words'], $words);

		    foreach($words as $word) {
		    	$filterArr[] = $key . ' REGEXP ?';
		    }

		    $filter[$key] = implode(' ' . SEARCH_OPERAND . ' ', $filterArr);
		}

		$data['filter'] = $filter;

		return $data;
	}

	public function formGeneralQuery($data, $table, $orderBy = '') {

		$data = $this->regexFilter($data);

		$sqlFilter = (count($data['filter'] > 1)) ? implode(' and ', $data['filter']) : array_values($data['filter']);
		$sqlStatement = 'SELECT * FROM ' . $table . ' WHERE ' . $sqlFilter . $orderBy;

		$data['query'] = $sqlStatement;

		return $data;
	}

	public function handleSpecialCases($data) {

		// This method allows us to do a 'this or that' kind of search; special cases need to be listed here
		$return = array();
		foreach ($data as $word) {
			
			$newWord = preg_replace('/bangalore/', 'bengaluru|bangalore', $word);
			array_push($return, $newWord);
		}
		return $return;
	}

	public function searchPatches($data) {

		// Special cases in searches are dealt with here
		if(isset($data['authors'])) $data['authors'] = '"full":".*' . $data['authors'] . '[^"]*","first"';
		return $data;
	}
}

?>