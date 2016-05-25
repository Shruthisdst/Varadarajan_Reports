<?php

class listingModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function listIssues($journal = DEFAULT_JOURNAL) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		// Issues which are actually Online resources are not included here
		$sth = $dbh->prepare('SELECT DISTINCT volume, issue, month, year FROM ' . METADATA_TABLE . ' WHERE journal=:journal and issue NOT REGEXP \'online\' ORDER BY volume DESC, issue');
		$sth->bindParam(':journal', $journal);
		
		$sth->execute();

		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$volume = $result->volume;
			$details = array($result->issue, $result->month, $result->year);

			if(isset($data{$volume})) {
				array_push($data{$volume}, $details);
			}
			else {
				$data{$volume} = array($details);
			}
		}

		if($data) {

			$data['journal'] = $journal;
		}

		$dbh = null;
		return $data;
	}
	
	public function listOnline($journal = DEFAULT_JOURNAL) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		// Only online resources are fethced here
		$sth = $dbh->prepare('SELECT DISTINCT volume, issue, month, year FROM ' . METADATA_TABLE . ' WHERE journal=:journal and issue REGEXP \'online\' ORDER BY volume DESC, issue');
		$sth->bindParam(':journal', $journal);
		
		$sth->execute();

		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$volume = $result->volume;
			$details = array($result->issue, $result->month, $result->year);

			if(isset($data{$volume})) {
				array_push($data{$volume}, $details);
			}
			else {
				$data{$volume} = array($details);
			}
		}

		if($data) {

			$data['journal'] = $journal;
		}

		$dbh = null;
		return $data;
	}

	public function listArticles($journal = DEFAULT_SERIES, $volume = DEFAULT_VOLUME, $issue = DEFAULT_ISSUE) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE journal=:journal AND volume=:volume AND issue=:issue ORDER BY page');
		$sth->bindParam(':journal', $journal);
		$sth->bindParam(':volume', $volume);
		$sth->bindParam(':issue', $issue);

		$sth->execute();
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

	public function listAuthorArticles($journal = DEFAULT_SERIES, $author = '') {

		if($author == '') {

			return null;
		}

		// In the url, spaces will be replaced by '_', and hence need to get back the actual authorname
		$author = preg_replace('/_/', ' ', $author);
	
		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE authors regexp :author');
		
		// json_encode is used to put unicode back to \uxxxx format so that it will match with whatever is there in the database
		// An alternative to this (> php v5.4.0) is to use JSON_UNESCAPED_UNICODE in dataModel::getAuthorJSON so that everything will be handled in unicode
		$authorJSONEncoded = json_encode($author);
		$authorQuoted = preg_quote($authorJSONEncoded);
		$sth->bindParam(':author', $authorQuoted);

		$sth->execute();
		$data = null;
		$i = 0;

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}

		if($data) {

			$data['author'] = $author;
		}

		$dbh = null;
		return $data;
	}

	public function listFellows($type,$select) {

		$dbh = $this->db->connect(GENERAL_DB_NAME);
		if(is_null($dbh))return null;
		
		if($type == 'women') {

			$sth = $dbh->prepare('SELECT * FROM ' . FELLOW_TABLE . ' WHERE sex=\'F\' AND type=\'\' ORDER BY lname, fname');
		}
		elseif($type == 'deceased,women') {

			$sth = $dbh->prepare('SELECT * FROM ' . FELLOW_TABLE . ' WHERE sex=\'F\' AND type REGEXP \'deceased\' ORDER BY lname, fname');
		}
		else{

			$sth = $dbh->prepare('SELECT * FROM ' . FELLOW_TABLE . ' WHERE type=:type AND name REGEXP :select ORDER BY lname, fname');
		}

		$sth->bindParam(':type', $type);
		$sth->bindParam(':select', $select);

		$sth->execute();
		$data = null;
		$i = 0;

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}

		if($data) {

			if($type == '') {

				$data['typeTitle'] = 'Present Fellows';
			}
			else {

				$type = preg_replace('/\,/', ' ', $type);
				$data['typeTitle'] = ucwords($type) . ' Fellows';
			}
			$data['type'] = $type;
		}
		$dbh = null;
		return $data;
	}

	public function listAssociates($type) {

		$dbh = $this->db->connect(GENERAL_DB_NAME);
		if(is_null($dbh))return null;

		$currentYear = date('Y');
		
		if($type == 'women') {

			$sth = $dbh->prepare('SELECT * FROM ' . ASSOCIATE_TABLE . ' WHERE sex=\'F\'');
		}
		elseif($type == 'former') {

			$sth = $dbh->prepare('SELECT * FROM ' . ASSOCIATE_TABLE . ' WHERE RIGHT(period, 4) < ' . $currentYear);
		}
		else{

			$sth = $dbh->prepare('SELECT * FROM ' . ASSOCIATE_TABLE . ' WHERE RIGHT(period, 4) >= ' . $currentYear);
		}


		$sth->bindParam(':type', $type);

		$sth->execute();
		$data = null;
		$i = 0;

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}

		if($data) {

			if($type == '') {

				$data['typeTitle'] = 'Current Associates';
			}
			else {

				$type = preg_replace('/\,/', ' ', $type);
				$data['typeTitle'] = ucwords($type) . ' Associates';
			}
		}
		$dbh = null;
		return $data;
	}

	public function listCategories($journal = DEFAULT_JOURNAL) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;

		$sth = $dbh->prepare('SELECT DISTINCT feature FROM ' . METADATA_TABLE . ' WHERE journal=:journal ORDER BY feature');
		$sth->bindParam(':journal', $journal);
		
		$sth->execute();
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

	public function listCategoryArticles($journal = DEFAULT_SERIES, $feature = '') {

		if($feature == '') {

			return null;
		}

		// In the url, spaces will be replaced by '_', and hence need to get back the actual feature
		$feature = preg_replace('/_/', ' ', $feature);
	
		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE feature regexp :feature');
		
		$sth->bindParam(':feature', $feature);

		$sth->execute();
		$data = null;
		$i = 0;

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}

		if($data) {

			$data['feature'] = $feature;
		}

		$dbh = null;
		return $data;
	}

	public function listCoverPages($journal = DEFAULT_SERIES) {

		$path = PHY_VOL_URL . $journal . '/**/**/thumb.jpg';
		$files = glob($path);
		$covers = array();
		foreach ($files as $file) {
			
			$relPath = str_replace(PHY_VOL_URL, '', $file);
			$peices = explode('/', $relPath);
			
			array_push($covers, $peices);
		}
		return array_reverse($covers);
	}

	public function listForthcoming($journal = DEFAULT_SERIES) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . FORTHCOMING_TABLE);
		$sth->execute();
	
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
	
	//~ public function listBooks($journal = DEFAULT_JOURNAL)
	//~ {
		//~ $dbh = $this->db->connect($journal);
		//~ if(is_null($dbh))return null;
		//~ 
		//~ $sth = $dbh->prepare('select distinct bcode from ' . METADATA_TABLE);
		//~ $sth->execute();
	//~ 
		//~ $data = array();
		//~ $i = 0;
//~ 
		//~ while($result = $sth->fetch(PDO::FETCH_OBJ))
		//~ {
			//~ $data[$i] = $result;
	        //~ $i++;
	        //~ array_push($data, $result);
		//~ }
//~ 
		//~ $dbh = null;
		//~ return $data;
	//~ }
	
	public function listTitle($bcode = DEFAULT_BCODE)
	{
		$dbh = $this->db->connect(DEFAULT_JOURNAL);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('select * from ' . METADATA_TABLE . ' where bcode=:bcode order by page ');
		$sth->bindParam(':bcode', $bcode);
		$sth->execute();
	
		$data = array();
		//~ $i = 0;

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			//~ $data[$i] = $result;
	        //~ $i++;
	        array_push($data, $result);
		}

		$dbh = null;
		return $data;
	}
}

?>
