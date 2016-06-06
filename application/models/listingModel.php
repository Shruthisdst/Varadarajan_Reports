<?php

class listingModel extends Model {

	public function __construct() {

		parent::__construct();
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

		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
	        array_push($data, $result);
		}

		$dbh = null;
		return $data;
	}
}

?>
