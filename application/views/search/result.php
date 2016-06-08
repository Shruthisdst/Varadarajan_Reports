<div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-8 clear-paddings">
            <div class="col-padded"><!-- inner custom column -->                
                <ul class="list-unstyled clear-margins"><!-- widgets -->                    
                    <li class="widget-container widget_recent_news"><!-- widgets list -->               
<!--
                        <ol class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li>Search Results</li>
                        </ol>
-->
                        <ul class="list-unstyled">
                            <li class="journal-article">
                                <p class="journal-article-title">Search Results</p>
                                <p class="journal-article-subtitle"><?=$viewHelper->displaySearchCount($data)?></p>
                                <!-- <div class="journal-article-meta">
                                    <span class="journal-article-meta-feature">Special issue title goes here</span>
                                </div> -->
                            </li><hr />
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
<?php
$temp = 1;
foreach($data as $row) {
	if($temp != $row->btitle)
	{?>
	 <li class="journal-article-list">
		 <p class="journal-article-list-page">
			<span class="journal-article-meta-feature">Report <?=$viewHelper->displayBcode($row->bcode)?></span>
		</p>
		<p class="journal-article-list-title"><?=$row->btitle?></p>
			<span class="journal-article-subtitle">Pages : </span>
<?php
$temp = $row->btitle;
}
//~ echo '<p>' . $row->page . '</p>';
?>
<span class="journal-article-list-authors"><a href="<?=VOL_URL. $row->bcode . '.pdf#page=' . $viewHelper->displayNumber($row->page)?>" target="_blank"><?=$viewHelper->displayPages($row->page)?></a></span>
<?php } ?>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
                    
