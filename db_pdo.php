<?php
 /**
  * MySQL connection check
  *
  * Checks the connection to the local install of MySQL
  *
  * @author Mengxiang Li <s3262421@student.rmit.edu.au>
  * @version 1.0
  * @package Connect
  */
  
  define('DB_HOST', 'yallara.cs.rmit.edu.au');
  define('DB_PORT', '50644');
  define('DB_NAME', 'winestore');
  define('DB_USER', 'winestore');
  define('DB_PW', '19860309');
  
  $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
   
