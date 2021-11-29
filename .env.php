<?php

/**
 * ENVIRONMENT
 *      development
 *      testing
 *      production
 */
define('ENVIRONMENT', "development");

/**
 * config
 */
define('BASE_URL', "http://localhost:8080/");
define("PERMITTED_URL_CHARS", "a-z \x{4e00}-\x{9fa5} \x{FF01}-\x{FF5E} 0-9~%.:_\-&");

/**
 * database
 */
define('DATABASE', array(
  'dsn'  => '',
  'hostname' => 'db',
  'username' => 'root',
  'password' => 'actibee',
  'database' => 'actibee',
  'dbdriver' => 'mysqli',
  'dbprefix' => '',
  'pconnect' => FALSE,
  'db_debug' => FALSE,
  'cache_on' => FALSE,
  'cachedir' => '',
  'char_set' => 'utf8',
  'dbcollat' => 'utf8_general_ci',
  'swap_pre' => '',
  'encrypt' => FALSE,
  'compress' => FALSE,
  'stricton' => FALSE,
  'failover' => array(),
  'save_queries' => TRUE
));

define('DATABASE_LOG', FCPATH."database_log".DIRECTORY_SEPARATOR);

/**
 * mail
 */
define('EMAIL_SET', [
  "smtp_crypto"    => "smtp",
  "smtp_host"      => "",
  "smtp_port"      => "",
  "smtp_timeout"   => "30",
  "smtp_user"      => "",
  "smtp_pass"      => "",
  "charset"        => "utf-8",
  "newline"        => "\r\n",
  "mailtype"       => "html",
]);
define("EMAIL_KEY", "");

/**
 * file Update Path
 */
define('USERFILE_PATH', FCPATH);

/**
 * ECPAY設定
 */
define("PAYMENT", [
  "ServiceURL" => "",
  "sSPCheckOut_Url" => "",
  "HashKey" => "",
  "HashIV" => "",
  "MerchantID" => "",
  "EncryptType" => "1"
]);
//是否開啟信用卡支付
define("ISCREDIT", false);
