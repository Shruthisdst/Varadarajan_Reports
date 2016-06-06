<?php

class viewHelper extends View {

	public function __construct() {

	}

    public function displaySearchCount($data = array()) {

        $articleCount = count($data);
        echo $articleCount;
        echo ($articleCount > 1) ? ' Pages' : ' Page';
    }

    public function displayAllJournalsSearchCount($data = array()) {

        $journalCount = count($data);
        echo $journalCount;
        echo ($journalCount > 1) ? ' journals' : ' journal';

        echo ' | ';

        // While calculating articleCount we should remove journalCount from it as COUNT_RECURSIVE will also include array keys
        $articleCount = count($data, COUNT_RECURSIVE) - $journalCount;
        echo $articleCount;
        echo ($articleCount > 1) ? ' articles' : ' article';
    }

    public function displayTextResult($row = array()) {

        echo '<p class="others search-result" style="max-height: 4em;overflow: hidden;"><span style="font-weight: 600">';
        echo (sizeof($row['cur_page']) > 1) ? 'Pages' : 'Page';
        echo '&nbsp;&nbsp;</span>';
        $lastPage = array_pop($row['cur_page']);
        foreach($row['cur_page'] as $page) {
            // Temporarily disabled till Book reader is in place
            // echo '<span><a href="viewReport.php?snum=snum&amp;projectid=projectid&amp;page=cur_page&amp;find=textSearchBox">'. $this->formatPageNumber($page) . '</a>, </span>';
            echo '<span><a title="coming soon" href="#">'. $this->formatPageNumber($page) . '</a>, </span>';
        }
        // echo '<span><a href="viewReport.php?snum=snum&amp;projectid=projectid&amp;page=cur_page&amp;find=textSearchBox">'. $this->formatPageNumber($lastPage) . '</a></span>';
        echo '<span><a title="coming soon" href="#">'. $this->formatPageNumber($lastPage) . '</a></span>';
        echo '</p>';
    }

    public function insertReCaptcha() {

        require_once('../vendor/recaptchalib.php');

        $publickey = "6Lc6KPMSAAAAAJ-yzoW7_KCxyv2bNEZcLImzc7I8";
        $privatekey = "6Lc6KPMSAAAAANrIJ99zGx8wxzdUJ6SwQzk1BgXX";

        echo recaptcha_get_html($publickey);
    }

 


    public function displayBcode($val = '') {

        // Removes leading zeros
        $val = preg_replace('/^0+/', '', $val);
        $val = preg_replace('/\-0+/', '-', $val);

        //Special case: Some page ranges have alphabets in the beginnig such as L0332
        $val = preg_replace('/^([A-Za-z])0+/', "$1", $val);
        $val = preg_replace('/\-([A-Za-z])0+/', "-$1", $val);

        return $val;
    }    
    public function displayNumber($val = '') {

        // Removes leading zeros
        $val = preg_replace('/^0+/', '', $val);
        $val = preg_replace('/\-0+/', '-', $val);

        //Special case: Some page ranges have alphabets in the beginnig such as L0332
        $val = preg_replace('/^([A-Za-z])0+/', "$1", $val);
        $val = preg_replace('/\-([A-Za-z])0+/', "-$1", $val);

        return $val;
    }    

    public function displayAuthors($authors = '[]', $journal = DEFAULT_JOURNAL, $showAffiliation = 0, $uniqueAffiliations = array()) {

        if ($authors == '[]') {
            return '';
        }

        $Authors = json_decode($authors);
        
        $authorsString = '';
        foreach ($Authors as $Author) {

            $authorsString .= '<span>';
            $authorsString .= '<a href="' . BASE_URL . 'listing/bibliography/' . $journal . '/' . preg_replace('/ /', '_', $Author->name->full) . '">' . $this->formatAuthor($Author->name->full, $journal). '</a>';

            if($showAffiliation == 1) {

                // Display author affiliation matching from from unique list
                foreach ($Author->affiliation as $affl) {

                    $authorsString .= '<sup>' . (array_search($affl, $uniqueAffiliations) + 1) . '</sup> ';
                }
                // Display email
                foreach ($Author->email as $mail) {

                    $authorsString .= '<sup><a href="mailto:' . $mail . '" title="' . $mail . '"><i class="fa fa-envelope-o"></i></a></sup> ';
                }
                // Strip trailing comma in superscript
                $authorsString = preg_replace('/,\<\/sup\> $/', '</sup>', $authorsString);
            }
            $authorsString .= '</span> ';
        }
        return $authorsString;
    }

    public function displayTitle($bcode, $title, $page)
    {
		$path = VOL_URL . $bcode . '.pdf' . '#page=' . $page;
		$path = '<a href="'.$path.'" target="_blank">'.$title.'</a>';
		return $path;
	}
	public function displayPages($val = '') {

        // Removes leading zeros
        $val = preg_replace('/^0+/', '', $val);
        $val = preg_replace('/\-0+/', '-', $val);

        //Special case: Some page ranges have alphabets in the beginnig such as L0332
        $val = preg_replace('/^([A-Za-z])0+/', "$1", $val);
        $val = preg_replace('/\-([A-Za-z])0+/', "-$1", $val);
        
        $val = '&nbsp;' . $val;
        //~ $val = preg_replace('/^;/', '', $val);
        return $val;
    }   
}

?>
