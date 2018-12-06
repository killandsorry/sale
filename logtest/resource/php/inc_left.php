<div class="left_title">
	<?=("Menu")?>
</div>
<?
$isAdmin = isset($_SESSION["isAdmin"]) ? intval($_SESSION["isAdmin"]) : 0;
$user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;

$db_menu = new db_query("SELECT *
								 FROM modules
								 ORDER BY mod_order ASC");
?>
<ul id="test-list">
	<?
	$i=0;
	$id_tab = 1;
	while($row=mysqli_fetch_assoc($db_menu->result)){
		if(file_exists("modules/" . $row["mod_path"] . "/inc_security.php")===true){
			$i++;

			?>
			<li id="listItem_<?=$i?>">
				<table cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td width="6" align="center"><img src="resource/images/4.gif" border="0" /></td>
						<td class="t"><span style="cursor:pointer" onclick="showhidden(<?=$i?>);"><?=$row["mod_name"]?></span></td>
						<td width="10"><img src="resource/images/show.png" style="cursor:pointer" id="image_<?=$i?>" onclick="showhidden(<?=$i?>);" title="<?=("Show list menu")?>" /></td>
						<td width="10"><img src="resource/js/sortable/arrow.png" title="<?=("Move")?>" class="handle" /></td>
					</tr>
					<tbody id="showmneu_<?=$i?>" bgcolor="#FFFFFF" style="display:none">
						<?

						//$id_tab = $row["mod_id"];
						$title_tab = $row["mod_name"];
						$arraySub = explode("|",$row["mod_listname"]);
						$arrayUrl = explode("|",$row["mod_listfile"]);
						?>
						<?

						foreach($arraySub as $key=>$value){

							$url = isset($arrayUrl[$key]) ? $arrayUrl[$key] : '#';
							//$id_tab = $i + 1;
						?>
						<tr>
							<td width="6" align="center"><img src="resource/images/4.gif" border="0" /></td>
							<td colspan="3" class="m" id="<?=$id_tab?>">
								<span class="id_tab" style="display: none;"><?=$id_tab?></span><a onclick="return false" target="_blank" href="modules/<?=$row["mod_path"]?>/<?=$url?>"><?=$value?></a>
								<span style="padding: 0; display: none;" class="title_tab"><?=$title_tab?></span>
							</td>

						</tr>
						<?
						$id_tab++;
						}
						?>
					</tbody>
				</table>
			</li>
			<?
		}
	}
	?>
</ul>
<script language="javascript">
	function showhidden(divid){
		var object = document.getElementById("showmneu_"+divid);
		var objectimg = document.getElementById("image_"+divid);
		if(object.style.display == 'none'){
			object.style.display = '';
			objectimg.src = 'resource/images/hidden.png';
		}else{
			object.style.display = 'none';
			objectimg.src = 'resource/images/show.png';
		}
	}
</script>