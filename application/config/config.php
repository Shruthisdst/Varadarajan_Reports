<?php

// define('BASE_URL', 'http://www.ias.ac.in/stage/');
define('BASE_URL', 'http://192.168.1.24/ias_books/');
define('PUBLIC_URL', BASE_URL . 'public/');
define('XML_SRC_URL', BASE_URL . 'md-src/xml/');
define('VOL_URL', PUBLIC_URL . 'Volumes/');
define('DOWNLOAD_URL', PUBLIC_URL . 'Downloads/');
define('STOCK_IMAGE_URL', PUBLIC_URL . 'images/stock/');
define('RESOURCES_URL', PUBLIC_URL . 'Resources/');

// Physical location of resources
// define('PHY_BASE_URL', '/mnt/datadisk1/ias/');
define('PHY_BASE_URL', '/var/www/ias_books/');
define('PHY_PUBLIC_URL', PHY_BASE_URL . 'public/');
define('PHY_XML_SRC_URL', PHY_BASE_URL . 'md-src/xml/');
define('PHY_VOL_URL', PHY_PUBLIC_URL . 'Volumes/');
define('PHY_TXT_URL', PHY_PUBLIC_URL . 'Text/');
define('PHY_DOWNLOAD_URL', PHY_PUBLIC_URL . 'Downloads/');
define('PHY_FLAT_URL', PHY_BASE_URL . 'application/views/flat/');
define('PHY_STOCK_IMAGE_URL', PHY_PUBLIC_URL . 'images/stock/');
define('PHY_RESOURCES_URL', PHY_PUBLIC_URL . 'Resources/');

define('DB_PREFIX', 'ias');
define('DB_HOST', 'localhost');

// infra will become iasINFRA inside
//~ define('GENERAL_DB_NAME', 'infra');
//~ define('DB_NAME', 'books');

define('iasTOC_USER', 'root');
define('iasTOC_PASSWORD', 'mysql');



?>
