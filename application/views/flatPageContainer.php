<div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-8 clear-paddings">
            <div class="col-padded"><!-- inner custom column -->
<!-- Breadcrumb will be inserted here -->
<?=$this->printBreadcrumb($path)?>
                <div class="flat-page">
<!-- Flat page will be included here -->
<?php

if(file_exists('application/views/' . $actualPath . '.html')) {
    require_once 'application/views/' . $actualPath . '.html';
}
elseif(file_exists('application/views/' . $actualPath . '.php')) {
    require_once 'application/views/' . $actualPath . '.php';
}
elseif(file_exists('application/views/' . $actualPath . '/index.html')) {
    require_once 'application/views/' . $actualPath . '/index.html';
}
elseif(file_exists('application/views/' . $actualPath . '/index.php')) {
    require_once 'application/views/' . $actualPath . '/index.php';
}
else{
    require_once 'application/views/error/index.php';
}

?>
                </div>
            </div>
        </div>
