<?php

define('DEFAULT_JOURNAL', 'toc');
define('DEFAULT_BCODE', '01');
define('METADATA_TABLE', 'book');
define('FULLTEXT_TABLE', 'fulltextsearch');


// search settings
define('SEARCH_OPERAND', 'AND');

// user settings (login and registration)
define('SALT', 'ias');
define('REQUIRE_EMAIL_VALIDATION', True);//Set these values to True only
define('REQUIRE_RESET_PASSWORD', True);//if outbound mails can be sent from the server

// mailer settings
define('SERVICE_EMAIL', 'webadmin@ias.ernet.in');
define('SERVICE_NAME', 'Indian Academy of Sciences');

?>
