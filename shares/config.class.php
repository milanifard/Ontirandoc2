<?php
define('ROOT_PATH',str_repeat("../",substr_count($_SERVER['SCRIPT_NAME'],'/')-1));
require_once 'dbclass.inc.php';
require_once 'pdodb.class.php';

class config{
  public static $db_servers = array(
    'master' => array( 
    'host'   => 'localhost',
    'driver' => 'mysql',
                
    "lab_user" => 'ontouser',
    "lab_pass" => 'm#m#h@h',
    "lab_db"   => 'projectmanagement',

    "dataanalysis_user" => 'ontouser',
    "dataanalysis_pass" => 'm#m#h@h',
    "dataanalysis_db"   => 'projectmanagement',

    "formsgenerator_user" => 'ontouser',
    "formsgenerator_pass" => 'm#m#h@h',
    "formsgenerator_db"   => 'formsgenerator') 
  );
  public static $display_error = true;
  public static $root_path = ROOT_PATH;
  public static $start_page = 'pm/login.php';

}
?>
