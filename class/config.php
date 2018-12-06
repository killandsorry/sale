<?

$server  = isset($_SERVER['SERVER_NAME'])? $_SERVER['SERVER_NAME'] : 'localhost';
if($server == 'localhost'){
   define('VG_MYSQL_HOST', 'localhost');
   define('VG_MYSQL_USER', 'root');
   define('VG_MYSQL_PASSWORD', '');
   define('VG_MYSQL_DB', '1viec_test');
   define('DB_MASTER','1viec_test');
   define('DB_NOTIFICATION','1viec_test_notifi');
}else{
   define('VG_MYSQL_HOST', 'localhost');
   define('VG_MYSQL_USER', 'sql_1viec');
   define('VG_MYSQL_PASSWORD', 'dqPfTyx5Cet0VWiuSoVn2UgLr');
   define('VG_MYSQL_DB', 'db_1viec');
   define('DB_MASTER','db_1viec');
   define('DB_NOTIFICATION','db_1viec_notify');
}
//*/
/*
define('VG_MYSQL_HOST', 'localhost');
define('VG_MYSQL_USER', 'sql_1viec');
define('VG_MYSQL_PASSWORD', 'dqPfTyx5Cet0VWiuSoVn2UgLr');
define('VG_MYSQL_DB', 'db_1viec');
define('DB_MASTER','db_1viec');
define('DB_NOTIFICATION','db_1viec_notify');
//*/
?>