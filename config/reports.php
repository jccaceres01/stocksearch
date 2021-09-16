<?php

/**
 * 
 * Report Server config
 */

return [
  /**
   * Report URL
   */
  'rpt_server' => env('RPT_SERVER', 'http://localhost:8080/jasperserver'),
  
  /**
   * Report Server Credentials
   */
  'rpt_username' => env('RPT_USER', 'jasperadmin'),
  'rpt_user_password' => env('RPT_USER_PASSWORD', 'jasperadmin')
];
