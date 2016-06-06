<?php

class dataModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function getMetadaData($journal = DEFAULT_JOURNAL) {

		$fileName = XML_SRC_URL . $journal . '.xml';

		if (!(file_exists(PHY_XML_SRC_URL . $journal . '.xml'))) {
		
			return False;
		}

		//~ $article['book'] = $journal;

		$ID = 1;
		$xml = simplexml_load_file($fileName);

		$metaData = array();
		foreach ($xml->book as $book)
		{
			$article['bcode'] = (string) $book['code'];
			$article['btitle'] = (string) $book['btitle'];
			foreach ($book->s1 as $level1)
			{
				$article['level'] = 1;
				$article['title'] = (string) $level1['title'];
				$article['page'] = (string) $level1['page'];
				$article['id'] = str_pad($ID, 3, '0', STR_PAD_LEFT);
				$ID++;
				array_push($metaData, $article);
				foreach ($level1->s2 as $level2)
				{
					$article['level'] = 2;
					$article['title'] = (string) $level2['title'];
					$article['page'] = (string) $level2['page'];
					$article['id'] = str_pad($ID, 3, '0', STR_PAD_LEFT);
					$ID++;
					array_push($metaData, $article);
					foreach ($level2->s3 as $level3)
					{
						$article['level'] = 3;
						$article['title'] = (string) $level3['title'];
						$article['page'] = (string) $level3['page'];
						$article['id'] = str_pad($ID, 3, '0', STR_PAD_LEFT);
						$ID++;
						array_push($metaData, $article);
						foreach ($level3->s4 as $level4)
						{
							$article['level'] = 4;
							$article['title'] = (string) $level4['title'];
							$article['page'] = (string) $level4['page'];
							$article['id'] = str_pad($ID, 3, '0', STR_PAD_LEFT);
							$ID++;
							array_push($metaData, $article);
						}
					}
				}
			}
		}
		return $metaData;
	}

	public function getFulltextAndInsert($journal = DEFAULT_JOURNAL, $dbh = null) {
	
		$sth = $dbh->prepare('SELECT distinct bcode, btitle FROM ' . METADATA_TABLE . ' ORDER BY bcode');
		$sth->execute();
		
		while($result = $sth->fetch(PDO::FETCH_ASSOC))
		{
			$bookCode = $result['bcode'];
			$bookTitle = $result['btitle'];
			//~ $result['text'] = $this->getContent($bookCode);
			$dir = PHY_TXT_URL . $bookCode;
			if (is_dir($dir)){
				if ($dh = opendir($dir)){
					while (($file = readdir($dh)) !== false){
						if ($file != "." && $file != ".."){
							$page =  explode(".", $file);
							$textPath = PHY_TXT_URL . $bookCode . '/' . $file;
							$result['text'] = file_get_contents($textPath);
							$result['page'] = $page[0];
							$this->db->insertData(FULLTEXT_TABLE, $dbh, $result);
						}
					}
					closedir($dh);
				}
			}
		}
	}

	public function getChangesFromGit($repo, $journal = DEFAULT_JOURNAL) {

		// Get status in porcelain mode
		$status = (string) $repo->status();

		// Replace '??' with A which means untracked files which are to be added
		$status = str_replace('??', 'A', $status);
		$status = preg_replace('/\h+/m', ' ', $status);
		$status = preg_replace('/^\h/m', '', $status);

		$lines = preg_split("/\n/", $status);
		
		$files['A'] = $files['M'] = $files['D'] = $files['F'] = array();

		foreach ($lines as $file){
			
			// Extract files into three bins - A->Added, M->Modified and D->Deleted. Also filter other file not belonging to the journal mentioned
			if((preg_match('/^([AMD])\s(.*)/', $file, $matches)) && (preg_match('/Volumes\/' . $journal . '\//', $file))) {

				// Extract forthcmogng articles to a separate bin
				(preg_match('/Volumes\/' . $journal . '\/forthcoming/', $matches[2])) ? array_push($files['F'], $matches[2]) : array_push($files[$matches[1]], $matches[2]);
			}
		}
		return $files;
	}

	public function gitProcess($repo, $journal, $files, $operation, $message, $user) {

		if(($operation == 'addAll')&&(is_array($files))) {

			$path = preg_replace('/(.*)\/.*/' , "$1", $files[0]);
			$repo->run('add --all ' . $path);
		}
		else{

			foreach ($files as $file) {
				
				$repo->{$operation}($file);
			}
		}

		$message = str_replace(':journal', $journal, $message);
		$repo->run('-c "user.name=' . $user['name'] . '" -c "user.email=' . $user['email'] . '" commit -m "' . escapeshellarg($message) . '"');
	}

	public function verifyUser($journal, $user) {

		$users = constant(strtoupper($journal) . '_USERS');

		return (bool) preg_match('/"'.$user['email'] . ':' . $user['password'].'"/', $users);
	}

	public function formatStatus($statements) {

		$status = '<ul>';
		foreach ($statements as $statement) {
	
			$status .= '<li>' . $statement . '</li>';
		}
		$status .= '</ul>';
		return $status;
	}
}

?>
