<?php
//logging setup
ChromePhp::log("page: Config.php");

// Reports all errors
error_reporting(E_ALL);
// Do not display errors for the end-users (security issue)
ini_set('display_errors','Off');
// Set a logging file
ini_set('error_log','ada_errors.log');
// Override the default error handler behavior
set_exception_handler(function($exception) {
   error_log($exception);
   error_page("Something went wrong!");
});


define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","");
define("DB_DATABASE","ada_db");

//logging setup
// Reports all errors
error_reporting(E_ALL);
// Do not display errors for the end-users (security issue)
//ini_set('display_errors','Off');
// Set a logging file
//ini_set('error_log','ada_errors.log');
// Override the default error handler behavior
//set_exception_handler(function($exception) {
//   error_log($exception);
//   error_page("Something went wrong!");
//});

ini_set('log_errors', 1);

?>