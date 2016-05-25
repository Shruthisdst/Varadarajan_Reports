<div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-8 clear-paddings">
            <div class="col-padded"><!-- inner custom column -->                
                <ul class="list-unstyled clear-margins"><!-- widgets -->                    
                    <li class="widget-container widget_recent_news"><!-- widgets list -->               
                        <ol class="breadcrumb">
                            <li><a href="#">Home</a></li>
                            <li>Journals</li>
                            <li>Search Results</li>
                            <li>All Journals</li>
                        </ol>
                        <ul class="list-unstyled">
                            <li class="journal-article">
                                <p class="journal-article-title">Search Results - all journals</p>
                                <p class="journal-article-subtitle"><?=$viewHelper->displayAllJournalsSearchCount($data)?></p>
                                <!-- <div class="journal-article-meta">
                                    <span class="journal-article-meta-feature">Special issue title goes here</span>
                                </div> -->
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
<?php
foreach ($data as $journal => $journalData) {
    echo '<ul id="' . $journal . '" class="list-unstyled">';
    foreach($journalData as $row) { 
?>
                            <li class="journal-article-list">
                                <p class="journal-article-list-page">
                                    <span class="journal-article-meta-feature"><span class="text-green"><?=$viewHelper->journalFullNames{$journal}?></span></span>
                                    <span class="journal-article-meta-feature">Volume <?=$viewHelper->displayNumber($row->volume)?></span>
                                    <span class="journal-article-meta-feature"><?=$viewHelper->displayIssue($row->issue)?></span>
                                    <span class="journal-article-meta-feature"><?=$viewHelper->displayMonth($row->month)?> <?=$row->year?></span>
                                    <span class="journal-article-meta-feature">pp <?=$viewHelper->displayNumber($row->page)?></span>
                                    <span class="journal-article-meta-feature"><?=$row->feature?></span>
                                </p>
                                <p class="journal-article-list-title"><a href="<?=BASE_URL.'describe/article/' . $row->journal . '/' . $row->volume . '/' . $row->issue . '/' . $row->page?>"><?=$row->title?></a></p>
                                <p class="journal-article-list-authors"><?=$viewHelper->displayAuthors($row->authors, $row->journal)?></p>
                                <div class="journal-article-list-meta">
                                    <span><a href="<?=BASE_URL.'describe/article/' . $row->journal . '/' . $row->volume . '/' . $row->issue . '/' . $row->page?>">More Details</a></span>
                                    <?php if ($row->abstract): ?>
                                    <span><a class="trigger-abstract" id="display_<?=$row->id?>" href="javascript:void(0);">Abstract</a></span>
                                    <?php endif; ?>
                                    <span><?=$viewHelper->linkArticle($row)?></span>
                                </div>
                                <?php if($row->abstract) { ?>
                                <div class="journal-article-list-abstract" id="abstract_<?=$row->id?>">
                                    <?=$row->abstract?>
                                </div>
                                <?php } ?>
                            </li>
<?php
    }
    echo '</ul>';
}
?>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
<!-- Clear $journal value before displaying side bar. -->
<?php $journal = '' ?>
