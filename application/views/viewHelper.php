<?php

class viewHelper extends View {

	public function __construct() {

	}

	public function displayThumb($row = array()) {

        echo (file_exists('images/cover/' . $row['snum'] . '/' . $row['projectid'] . '.jpg')) ? '<img src="' . PUBLIC_URL . 'images/cover/' . $row['snum'] . '/' . $row['projectid'] . '.jpg" alt="Cover ' . $row['snum'] . ' ' . $row['projectid'] . '" />' : '<i class="fa fa-file-text-o shadow-thumb"></i>';
    }

    public function displayIssueCover($data = array()) {

        $path = $data->journal . '/' . $data->volume . '/' . $data->issue;
        $title = 'Vol. ' . $this->displayNumber($data->volume) . ', Issue ' . $this->displayNumber($data->issue);

        if (file_exists(PHY_VOL_URL . '/' . $path . '/cover.jpg')) {

            echo '
            <p class="issue-thumb">
                <a title="' . $title . '" href="' . VOL_URL . '/' . $path . '/cover.jpg' . '" target="_blank"><img src="' . VOL_URL . '/' . $path . '/cover.jpg' . '" alt="Issue front cover thumbnail"></a>';
            
            if (file_exists(PHY_VOL_URL . '/' . $path . '/back.jpg')) {
            
                echo '<a title="' . $title . '" href="' . VOL_URL . '/' . $path . '/back.jpg' . '" target="_blank"><img src="' . VOL_URL . '/' . $path . '/back.jpg' . '" alt="Issue back cover thumbnail"></a>';
            }
            echo '</p>';
        }
    }

    public function displayCoverGalleryItem($data = array()) {

        $thumbURL = VOL_URL . implode('/', $data);
        $coverURL = str_replace('thumb', 'cover', $thumbURL);

        echo '
        <div class="cover-gallery-item">
            <a href="' . $coverURL . '" data-lightbox="Cover pages" data-title="Volume ' . $this->displayNumber($data[1]) . ', Issue ' . $this->displayNumber($data[2]) . '" title="Volume ' . $this->displayNumber($data[1]) . ', Issue ' . $this->displayNumber($data[2]) . '"><img src="' . $thumbURL . '" alt="Cover page"></a>
        </div>
        ';
    }

	//~ public function linkArticle($data = array(), $anchorText = 'Fulltext PDF', $action = 'view', $hoverText = '') {
//~ 
        //~ echo '<a';
        //~ echo ($action == 'download') ? ' download ' : '';
        //~ echo ' title="' . $hoverText . '" target="_blank" class="text-blue" href="'. BASE_URL . 'article/fulltext/' . $data->journal . '/' . $data->volume . '/' . $data->issue . '/' . $data->page . '">' . $anchorText . '</a>';
    //~ }
	public function linkArticle($data = array(), $anchorText = 'Fulltext PDF', $action = 'view', $hoverText = '') {

        echo '<a';
        echo ($action == 'download') ? ' download ' : '';
        echo ' title="' . $hoverText . '" target="_blank" class="text-blue" href="'. BASE_URL . 'article/fulltext/' . $data->bcode . '/' . $data->page . '">' . $anchorText . '</a>';
    }

    public function linkViewOnline($row = array(), $page = 1, $text = '', $anchorText = 'Read online') {

        // Temporarily disabled title JPG book reader is in place
        // echo '<a href="' . BASE_URL . 'show/report/' . $row['snum'] . '/' . $row['projectid'] . '/">' . $anchorText . '</a>';
        echo '<a title="Coming Soon" href="#">' . $anchorText . '</a>';
    }

    public function linkDjVu($row = array()) {
        
        if (intval($row['snum']) >= DIGITIZED_FROM) {             
            echo ' | <span class="link-span clr5 yes-ul">';
            echo $this->linkAbstract($row, 1);
            echo '</span> | <span class="link-span clr5 yes-ul">';
            echo $this->linkReport($row, 1, '');
            echo '</span>';
        }
	}

    public function displaySearchCount($data = array()) {

        $articleCount = count($data);
        echo $articleCount;
        echo ($articleCount > 1) ? ' articles' : ' article';
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

    public function formatPageNumber($page) {

        // Extract last character from the page number if page number belongs to preface (ex. 0000a) or else return int value of page number
        if(preg_match('/[a-z]$/', $page)) {

            return $this->toRoman(ord(substr($page, -1, 1)) - 96);
        }
        else {

            return intval($page);
        }
    }

    public function toRoman($integer) {

        // Hindu arabic to roman conversion
  
        // $c='ivxlcdm'; 
        // echo $c;
        // for($a=5,$b=$s='';$N;$b++,$a^=7) 
        //         for($o=$N%$a,$N=$N/$a^0;$o--;$s=$c[$o>2?$b+$N-($N&=-2)+$o=1:$b].$s); 
        // return $s; 
  
        $table = array('m'=>1000, 'cm'=>900, 'd'=>500, 'cd'=>400, 'c'=>100, 'xc'=>90, 'l'=>50, 'xl'=>40, 'x'=>10, 'ix'=>9, 'v'=>5, 'iv'=>4, 'i'=>1); 
        $return = ''; 
        while($integer > 0) { 
            foreach($table as $rom=>$arb) { 
                if($integer >= $arb) { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                }
            }
        }
        return $return; 
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

    public function displayArtId($val = '') {

        // Removes leading zeros
        $val = preg_replace('/^0+/', '', $val);

        return $val;
    }

    public function displayKeywords($keywords = '') {

        $keywords = preg_replace('/;/', '; ', $keywords);
        $keywords = preg_replace('/[ ]+/', ' ', $keywords);
        
        return $keywords;
    }

    public function displayMonth($month = '', $type = 'long') {

        $monthArray = ($type == 'long') ? $this->monthNames : $this->monthNamesShort;

        foreach ($monthArray as $key => $value) {
            $month = preg_replace("/$key/", $value, $month);
        }

        return $month;
    }

    public function displayIssue($issue = '') {

        return ($issue == 'online') ? 'Online resources' : 'Issue ' . $this->displayNumber($issue);
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

    public function displayAuthorsInForthcoming($authors = '[]', $journal = DEFAULT_JOURNAL, $showAffiliation = 0, $uniqueAffiliations = array()) {

        if ($authors == '[]') {
            return '';
        }

        $Authors = json_decode($authors);
        
        $authorsString = '';
        foreach ($Authors as $Author) {

            $authorsString .= '<span>';
            $authorsString .=  $this->formatAuthor($Author->name->full, $journal);
            $authorsString .= '</span> ';
        }
        return $authorsString;
    }


    public function displayAffiliationList($uniqueAffiliations = array()) {

        echo '<ol>';
        foreach ($uniqueAffiliations as $affl) {

            echo '<li>' . $affl . '</li>';
        }
        echo '</ol>';
    }

    public function getUniqueAffiliations($authors = '') {

        $Authors = json_decode($authors);

        $affiliations = array();
        foreach ($Authors as $Author) {

            foreach ($Author->affiliation as $affl) {

                array_push($affiliations, $affl);
            }
        }

        return array_values(array_unique($affiliations));
    }

    public function displayDates($dates = '') {

        $Dates = json_decode($dates);

        foreach ((array) $Dates as $type => $date) {
            echo ($date) ? '<dt>' . $this->dateTypes{$type} . '</dt><dd>' . $this->formatDate($date) . '</dd>' : '';
        }
    }

    public function displayThumbnail($name = '', $path = PUBLIC_URL) {

        $imagePhysicalPath = PHY_PUBLIC_URL . $path . str_replace(' ', '_', $name) . '.jpg';
        $imagePath = PUBLIC_URL . $path . str_replace(' ', '_', $name) . '.jpg';
        if (file_exists($imagePhysicalPath)) echo '<p><img class="pull-right fellowThumb" src="' . $imagePath . '" alt="' . $name . '"/></p>';
    }

    public function displayIssuePageRange($data = array()) {

        $pages = array();
        $onlinePages = array();
        foreach ($data as $row) {
            
            if(!(preg_match('/^[a-zA-Z]/', $row->page)))
                array_push($pages, $row->page);
            else
                array_push($onlinePages, $row->page);
        }

        $range = '';

        if($pages) {
         
            $firstPage = explode('-', $pages[0]);
            $lastPage = explode('-', $pages[count($pages) - 1]);
            $range = $this->displayNumber($firstPage[0] . '-' . $lastPage[1]);
        }

        if($onlinePages){
    
            $firstPage = explode('-', $onlinePages[0]);
            $lastPage = explode('-', $onlinePages[count($onlinePages) - 1]);
            $range = $range . ' and ' . $this->displayNumber($firstPage[0] . '-' . $lastPage[1]);
        }

        $range = preg_replace('/^ and /', '', $range);

        return $range;
    }

    public function displayAssociatePeriod($period) {

        $fromTo = explode('-', $period);
        $period = preg_replace('/\-/', '–', $period);
        $validTill = $fromTo[1];
        $currentYear = date('Y');
        return ($validTill < $currentYear) ? 'Period: ' . $period : '<span class="text-primary">Period: ' . $period . '</span>';
    }

    public function displaySupplementaryMaterial($data = array()) {

        $list = '';
        if(!($data->supplementary)) return '';
        foreach ($data->supplementary as $item) {
            $list .= '<li><a download="' . $item . '" href="' . VOL_URL . $data->journal . '/' . $data->volume . '/' . $data->issue . '/' . $data->page . '/' . $item . '">' . $item . '</a></li>';
        }

        return $list;
    }

    public function checkAndDisplayOnlineResource($page = '') {

        if(preg_match('/^e/', $page)){

            return '<span class="journal-article-meta-feature text-primary">Online resource</span>';
        }
        else{

            return '';
        }
    }

    public function formatDate($dateString = '') {

        date_default_timezone_set('Asia/Kolkata');

        $dateStringVars = explode('-', $dateString);

        // Date formatting should include cases like 2105-10-00 and 2015-00-00

        $realDateString = $dateString;
        $realDateString = preg_replace('/\-00/', '-01', $realDateString);
        $timestamp = strtotime($realDateString);

        $dateFormatted = '';

        // $dateFormatted = (intval($dateStringVars[2])) ? $dateFormatted . date('j', $timestamp) . '<sup>' . date('S', $timestamp) . '</sup>' : $dateFormatted;
        $dateFormatted = (intval($dateStringVars[2])) ? $dateFormatted . date('j', $timestamp) : $dateFormatted;
        $dateFormatted = (intval($dateStringVars[1])) ? $dateFormatted . ' ' . date('F', $timestamp) : $dateFormatted;
        $dateFormatted = (intval($dateStringVars[0])) ? $dateFormatted . ' ' . date('Y', $timestamp) : $dateFormatted;

        return $dateFormatted;
    }

    public function formatAuthor($authorName = '', $journal = DEFAULT_JOURNAL) {

        return (preg_match('/^joaa|jgen$/', $journal)) ? $authorName : str_replace('.', '', $authorName);
    }
    
    public function displayTitle($bcode, $title, $page)
    {
		$path = VOL_URL . $bcode . '.pdf' . '#page=' . $page;
		$path = '<a href="'.$path.'" target="_blank">'.$title.'</a>';
		return $path;
	}
}

?>
