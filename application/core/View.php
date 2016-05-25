<?php

class View {

    public $journalShortNames = array("jcsc" => "J. of Chemical Sciences","pmsc" => "Procs. Mathematical Sciences","jess" => "J. of Earth System Science","sadh" => "Sadhana","pram" => "Pramana","jbsc" => "J. of Biosciences","boms" => "Bul. of Materials Science","joaa" => "J. of Astrophysics and Astronomy","jgen" => "J. of Genetics","reso" => "Resonance");
    public $journalFullNames = array("jcsc" => "Journal of Chemical Sciences","pmsc" => "Proceedings – Mathematical Sciences","jess" => "Journal of Earth System Science","sadh" => "Sadhana","pram" => "Pramana – Journal of Physics","jbsc" => "Journal of Biosciences","boms" => "Bulletin of Materials Science","joaa" => "Journal of Astrophysics and Astronomy","jgen" => "Journal of Genetics","reso" => "Resonance – Journal of Science Education");
    public $monthNames = array("00" => "","01" => "January","02" => "February","03" => "March","04" => "April","05" => "May","06" => "June","07" => "July","08" => "August","09" => "September","10" => "October","11" => "November","12" => "December");
    public $monthNamesShort = array("00" => "","01" => "Jan","02" => "Feb","03" => "Mar","04" => "Apr","05" => "May","06" => "Jun","07" => "Jul","08" => "Aug","09" => "Sep","10" => "Oct","11" => "Nov","12" => "Dec");
    public $dateTypes = array("M" => "Manuscript received","R" => "Manuscript revised","A" => "Accepted","E" => "Early published","U" => "Unedited version published online","F" => "Final version published online");

	public function __construct() {

	}
	
	public function getJournalFromPath($path = '') {

		$journal = '';
		$url = explode('/', preg_replace('/_/', ' ', $path));
		if (isset($url[2])) {
		
			$journal = array_search($url[2], $this->journalFullNames);
		}
		$journal = ($journal) ? $journal : '';
		return $journal;
	}

	public function getActualPath($path = '', $folderList = array()) {

		$pathRegex = str_replace('/', '\/[0-9]+\-*', $path) . '$';
		$pathMatched = array_values(preg_grep("/$pathRegex/", $folderList));
		
		if(isset($pathMatched[0])) {

			return str_replace(PHY_FLAT_URL, 'flat/', $pathMatched[0]);
		}
		else{

			// Second pass to check whether the path is pointing to a file other than index in a given folder
			$pathArray = preg_match('/(.*)\/(.*)/', $path, $matches);
			$secondTry = $matches[1];
			$suffix = $matches[2];

			$pathRegex = str_replace('/', '\/[0-9]+\-*', $secondTry) . '$';
			$pathMatched = array_values(preg_grep("/$pathRegex/", $folderList));

			return (isset($pathMatched[0])) ? str_replace(PHY_FLAT_URL, 'flat/', $pathMatched[0]) . '/' . $suffix : '';
		}
	}

	public function getNavigation($path = '') {

		// Include only folders beginning with a number
		$dirs = glob($path . '[0-9]*', GLOB_ONLYDIR);
		natsort($dirs);
		
		if(!(empty($dirs))) {
			
			foreach ($dirs as $key => $value) {

				$subNav = $this->getNavigation($value . '/');
				if($subNav) {

					$dirs{$key} = array($value);
					array_push($dirs{$key}, $subNav);
				}
			}
			return $dirs;
		}
	}

	public function getFolderList($navigation = array()) {

		$folderList = array();
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($navigation));
		foreach($iterator as $value) {
  			array_push($folderList, $value);
		}
		return $folderList;
	}

	public function showDynamicPage($data = array(), $path = '', $actualPath = '', $journal = '', $navigation = array(), $current = array()) {

		require_once 'application/views/viewHelper.php';
		$viewHelper = new viewHelper();
		$pageTitle = $this->getPageTitle($viewHelper, $path, $journal);

		require_once 'application/views/header.php';
		
		if(preg_match('/flat\/Home/', $path)) require_once 'application/views/carousel.php';
		
		if(file_exists('application/views/' . $actualPath . '.php')) {
		    require_once 'application/views/' . $actualPath . '.php';
		}
		elseif(file_exists('application/views/' . $actualPath . '/index.php')) {
		    require_once 'application/views/' . $actualPath . '/index.php';
		}
		else{
		    require_once 'application/views/error/index.php';
		}

		require_once($this->getSideBar($actualPath, $journal));
		require_once 'application/views/footer.php';
	}

	public function showFlatPage($data = array(), $path = '', $actualPath = '', $journal = '', $navigation = array(), $current = array()) {

		require_once 'application/views/viewHelper.php';
		$viewHelper = new viewHelper();
		$pageTitle = $this->getPageTitle($viewHelper, $path, $journal);

		require_once 'application/views/header.php';
		require_once 'application/views/flatPageContainer.php';
		require_once($this->getSideBar($actualPath, $journal));
		require_once 'application/views/footer.php';
    }

    public function printNavigation($navigation = array(), $ulClass = ' class="nav navbar-nav navbar-right"', $liClass = ' class="dropdown"') {

        echo '<ul' . $ulClass . '>' . "\n";
        foreach ($navigation as $mainNav) {
 			
 			// Trailing '/' is appended to href links, as GLOB_MARK is not added in getNavigation method

            if(is_array($mainNav)){

                echo "\t" . '<li' . $liClass . '>' . "\n";
                echo "\t\t" . '<a href="' . $this->getNavLink($mainNav[0]) . '/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $this->processNavPath($mainNav[0]) . ' <span class="caret"></span></a>' . "\n";
                $this->printNavigation($mainNav[1], ' class="dropdown-menu" role="menu"', ' class="dropdown"');
                echo "\t" . '</li>' . "\n";
            }
            else{
            	
            	$anchorText = $this->processNavPath($mainNav);
                if($anchorText) {
                	
                	$isExternal = $this->checkIfExternal($mainNav);
                	$targetBlank = ($isExternal['type'] == 'OutsideDomain') ? 'target="_blank" ' : '';
                	$navLink = $this->getNavLink($mainNav) . '/';

                	if($isExternal){
                		
                		$navLink = $isExternal['URL'];
                		if($isExternal['type'] == 'InsideDomain') $navLink = BASE_URL . $navLink;
                	}
                	echo "\t" . '<li><a ' . $targetBlank . 'href="' . $navLink . '">' . $anchorText . '</a></li>' . "\n";
                }
                else{

                	echo "\t" . '<li role="separator" class="divider"></li>' . "\n";
                }
            }
        }
        echo '</ul>' . "\n";
    }

    private function checkIfExternal($path) {

    	$linkPath = $path . '/link.php';
    	if(file_exists($linkPath)) {

    		$handle = fopen($linkPath, 'r');
 	    	$externalURL['URL'] = fgets($handle);
 	    	$externalURL['type'] = (preg_match('/^http|www/', $externalURL['URL'])) ? 'OutsideDomain' : 'InsideDomain';
 	    	return $externalURL;
    	}
    	return False;
    }

	private function printBreadcrumb($path) {

    	$path = preg_replace('/flat/', 'Home', $path);
    	$path = preg_replace('/\_/', ' ', $path);
    	$pathItems = explode('/', $path);

    	echo '<ol class="breadcrumb">';
        foreach ($pathItems as $item) {

        	echo '<li>' . $item . '</li>';
        }
        echo '</ol>';

    }

    private function processNavPath($path) {

        $path = preg_replace('/\/[0-9]+\-/', '/', $path);
        $path = explode('/', $path);
        $path = htmlentities(str_replace('_', ' ', $path[count($path) - 1]), ENT_COMPAT, "UTF-8");
    	// Letters which are to be forced to lower-case need to handled below
    	return preg_replace('/IASc/', 'IAS<span class="lower-case">c</span>', $path);
    }

    private function getNavLink($path) {

        $path = preg_replace('/\/[0-9]+\-/', '/', $path);
        return htmlentities(str_replace(PHY_FLAT_URL, BASE_URL, $path), ENT_COMPAT, "UTF-8");
    }
	
	private function getPageTitle($viewHelper, $path, $journal) {

		if($journal) {
			
			return $viewHelper->journalFullNames{$journal};			
		}
		else {

			if(preg_match('/flat/', $path)){

				// Remove trailing slashes
				$path = preg_replace('/\/$/', '', $path);
				$paths = explode('/', $path);
				// Remove 'flat' from the URL
				unset($paths[0]);
				$paths = array_reverse($paths);
				$paths = array_unique($paths);
				$pageTitle = implode(' | ', $paths);
				return preg_replace('/_/', ' ', $pageTitle);
			}
			else{

				if(preg_match('/fellow/', $path)){

					return 'Fellowship';
				}
				elseif(preg_match('/associate/', $path)){

					return 'Associateship';
				}
				else{

					return '';
				}
			}
		}
    }
    
    private function getSideBar($path, $journal = '') {

    	if($journal) {

    		switch ($journal) {
			    case 'boms':
					$path = 'flat/4-Journals/2-Bulletin_of_Materials_Science';
					break;
				case 'joaa':
					$path = 'flat/4-Journals/3-Journal_of_Astrophysics_and_Astronomy';
					break;
				case 'jbsc':
					$path = 'flat/4-Journals/4-Journal_of_Biosciences';
					break;
				case 'jcsc':
					$path = 'flat/4-Journals/5-Journal_of_Chemical_Sciences';
					break;
				case 'jess':
					$path = 'flat/4-Journals/6-Journal_of_Earth_System_Science';
					break;
				case 'jgen':
					$path = 'flat/4-Journals/7-Journal_of_Genetics';
					break;
				case 'pram':
					$path = 'flat/4-Journals/8-Pramana_–_Journal_of_Physics';
					break;
				case 'pmsc':
					$path = 'flat/4-Journals/9-Proceedings_–_Mathematical_Sciences';
					break;
				case 'reso':
					$path = 'flat/4-Journals/10-Resonance_–_Journal_of_Science_Education';
					break;
				case 'sadh':
					$path = 'flat/4-Journals/11-Sadhana';
					break;
			}
		}

	    if(file_exists('application/views/' . $path . '/sideBar.php')) {

        	return 'application/views/' . $path . '/sideBar.php'; 
        }
		elseif(file_exists('application/views/' . preg_replace('/(.*)\/(.*)/', "$1", $path) . '/sideBar.php')) {

        	return 'application/views/' . preg_replace('/(.*)\/(.*)/', "$1", $path) . '/sideBar.php'; 
        }
        elseif(file_exists('application/views/' . $path . '/../sideBar.php')) {

        	return 'application/views/' . $path . '/../sideBar.php'; 
        }
        else{

        	return 'application/views/generalSidebar.php';
        }
    }
}

?>