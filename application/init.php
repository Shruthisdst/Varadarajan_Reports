<?php

require_once 'core/App.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

require_once 'vendor/PHPMailerAutoload.php';
require_once 'vendor/Git.php';

require_once 'config/config.php';
require_once 'config/settings.php';
require_once 'config/schema.php';
require_once 'config/messages.php';
require_once 'config/users.php';

$app = new App;

?>