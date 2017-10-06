<?php
/*
  Plugin Name: DZS Zoom Progress Bars
  Plugin URI: http://digitalzoomstudio.net/
  Description: Create and build awesome progress bars. 
  Version: 2.00
  Author: Digital Zoom Studio
  Author URI: http://digitalzoomstudio.net/ 
 */




require_once(dirname(__FILE__).'/class-dzsprg.php');

$dzsprg = new DZSProgressBars();

define("DZSPRG_VERSION", "2.00");