<?
include ("inc_security.php");
checkAddEdit("edit");

$record_id		=	getValue("record_id");
$type				=	getValue("type","str","GET","",1);
$value			=	getValue("value");
$filed			=	"";
switch($type){
	case "job_active":
		$filed	=	"job_active";
		break;
}

$url				=	base64_decode(getValue("url","str","GET",base64_encode("listing.php")));
$ajax				=	getValue("ajax");


$vl_class->set_active_unactive_job(array('jid' => $record_id));

if($ajax!=1){
	redirect($url);
}else{
	?><img border="0" src="<?=$fs_imagepath?>check_<?=$value?>.gif"><?
}
?>