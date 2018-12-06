<?php
/**
 * Created by Bùi Văn Chiến (skype: chien88edu).
 * Email: cbquyetchien973@gmail.com - Phone: 0989.197.xxx
 * Date: 11/22/2018
 * Time: 5:10 PM
 */
include '../class/vl_database.php';

$db_query = new db_query("SELECT * FROM blog LIMIT 1");
if($row     = $db_query->fetch()){
    echo $row['blo_title'];
}