<?php

class Model {

	public function __construct() {

		$this->db = new Database();
	}

	public function getPostData() {

		if (isset($_POST['submit'])) {

			unset($_POST['submit']);	
		}

		if(!array_filter($_POST)) {
		
			return false;
		}
		else {

			return array_filter(filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		}
	}

	public function getGETData() {

		if(!array_filter($_GET)) {
		
			return false;
		}
		else {

			return filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
	}

	public function listSeries() {

		$dbh = $this->db->connect();
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('select distinct snum,year from project order by snum');
		$sth->execute();

		$i = 0;
		while($result = $sth->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i] = $result;
	        $i++;
		}
		$dbh = null;
		return $data;
	}

	public function listDepartments() {

		$dbh = $this->db->connect();
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT DISTINCT department FROM project ORDER BY department');
		$sth->execute();

		$i = 0;
		while($result = $sth->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i] = $result;
	        $i++;
		}
		$dbh = null;
		return $data;
	}

	public function listColleges() {

		$dbh = $this->db->connect();
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT DISTINCT college FROM project ORDER BY college');
		$sth->execute();

		$i = 0;
		while($result = $sth->fetch(PDO::FETCH_ASSOC))
		{
			$data[$i] = $result;
	        $i++;
		}
		$dbh = null;
		return $data;
	}

	public function getCurrentIssue($journal = DEFAULT_JOURNAL) {

		$this->db = new Database();
		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		// Online issues are to filtered from appearing as current issues	
		$sth = $dbh->prepare('SELECT DISTINCT volume, issue, year, month from ' . METADATA_TABLE . ' WHERE issue != \'online\' ORDER BY volume DESC, issue DESC LIMIT 1');
		$sth->execute();
		
		$result = $sth->fetch(PDO::FETCH_OBJ);
		return $result;
	}

	public function preProcessPOST ($data) {

		return array_map("trim", $data);
	}

	public function encrypt ($data) {

		return sha1(SALT.$data);
	}
	
	public function sendLetterToPostman ($fromName = SERVICE_NAME, $fromEmail = SERVICE_EMAIL, 
		$toName = SERVICE_NAME, $toEmail = SERVICE_EMAIL, $subject = 'Bounce', 
		$message = '', $successMessage = 'Bounce', $errorMessage = 'Error') {

	    $mail = new PHPMailer();
        $mail->isSendmail();
        $mail->isHTML(true);
        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        return $mail->send();
 	}

 	public function bindVariablesToString ($str = '', $data = array()) {

 		unset($data['count(*)']);
	    
	    while (list($key, $val) = each($data)) {
	    
	        $str = preg_replace('/:'.$key.'/', $val, $str);
		}
	    return $str;
 	}

 	public function listFiles ($path = '') {

 		if (!(is_dir($path))) return array();

 		$files = scandir($path);
 
 		unset($files[array_search('.', $files)]);
 		unset($files[array_search('..', $files)]);
 
 		return $files;
 	}
}

?>