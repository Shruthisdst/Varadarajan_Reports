<div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-8 clear-paddings">
            <div class="col-padded"><!-- inner custom column -->
                <ul class="list-unstyled clear-margins"><!-- widgets -->
                    <li class="widget-container widget_recent_news"><!-- widgets list -->
                        <ul class="list-unstyled">
                            <li class="journal-article">
                                <p class="journal-article-title">
                                    <?=$data[0]->btitle?>
                                </p><hr />
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
<?php foreach($data as $row) { if($row->level == '1') { ?>
							<li class="journal-article-level">
								<p class="journal-article-list-title">
									<?php echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page); ?>
								</p>
							</li>
						<?php } if($row->level == '2') { ?>
							<li class="journal-article-first-level">
								<?php echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page); ?>
							</li>
						<?php } if($row->level == '3') { ?>
							<li class="journal-article-second-level">
								<?php echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page); ?>
							</li>
						<?php } if($row->level == '4') { ?>
							<li class="journal-article-third-level">
								<?php echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page); ?>
							</li>
		<?php } }?>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
