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
                                </p>
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
<?php foreach($data as $row) { ?>
							<li class="journal-article-list">
								<p class="journal-article-list-title">
									<?php if($row->level == '1') echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page)?>
								</p>
								<ul class="list-unstyled">
									<li class="journal-article-authors"><?php if($row->level == '2') echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page)?>
										<ul class="list-unstyled">
											<li><?php if($row->level == '3') echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page)?>
												<ul class="list-unstyled">
													<li><?php if($row->level == '4') echo $viewHelper->displayTitle($row->bcode, $row->title, $row->page)?></li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<?php } ?>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
