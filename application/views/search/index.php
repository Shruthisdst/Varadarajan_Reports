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
                            <li><?=$viewHelper->journalFullNames{$journal}?></li>
                            <li>Search</li>
                        </ol>                           
                        <ul class="list-unstyled">                          
                            <li class="journal-article">
                                <p class="journal-article-title">Search</p>
                                <p class="journal-article-subtitle"><?=$viewHelper->journalFullNames{$journal}?></p>
                                <!-- <div class="journal-article-meta">
                                    <span class="journal-article-meta-feature">Special issue title goes here</span>
                                </div> -->
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
                            <li class="journal-article-list">
                                <form method="post" action="<?=BASE_URL . 'search/doSearch'?>" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="title" class="control-label col-xs-2">Title</label>
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control" id="title" name="title" placeholder="">
                                        </div>
                                        <div class="col-xs-2">&nbsp;</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="authors" class="control-label col-xs-2">Author</label>
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control" id="authors" name="authors" placeholder="">
                                        </div>
                                        <div class="col-xs-2">&nbsp;</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keywords" class="control-label col-xs-2">Keywords</label>
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="">
                                        </div>
                                        <div class="col-xs-2">&nbsp;</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fulltext" class="control-label col-xs-2">Fulltext</label>
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control" id="fulltext" name="fulltext" placeholder="">
                                        </div>
                                        <div class="col-xs-2">&nbsp;</div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-offset-2 col-xs-10">
                                            <input type="hidden" id="journal" name="journal" value="<?=$journal?>">
                                            <button type="submit" class="btn btn-primary naked">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
                    
