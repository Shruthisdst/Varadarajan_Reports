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
                            <li><?=$viewHelper->journalFullNames{$data[0]->journal}?></li>
                            <li>Search Results</li>
                        </ol>
                        <ul class="list-unstyled">
                            <li class="journal-article">
                                <p class="journal-article-title">Search Results</p>
                                <p class="journal-article-subtitle"><?=$viewHelper->displaySearchCount($data)?></p>
                                <!-- <div class="journal-article-meta">
                                    <span class="journal-article-meta-feature">Special issue title goes here</span>
                                </div> -->
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
<?php
foreach($data as $row) { 
?>

                            <li class="journal-article-list">
                                <p class="journal-article-list-page">
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
<?php } ?>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
                    
