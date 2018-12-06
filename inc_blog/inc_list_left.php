<?
$page_size	= 25;
$page_prefix		= "";
$normal_class		= "page";
$selected_class	= "page_current";
$previous			= "<";
$next					= ">";
$first				= "<<";
$last					= ">>";
$break_type			= 1;//"1 => << < 1 2 [3] 4 5 > >>", "2 => < 1 2 [3] 4 5 >", "3 => 1 2 [3] 4 5", "4 => < >"

$url				= '';
$currentUrl		= explode(',', $_SERVER['REQUEST_URI']);
if(isset($currentUrl[0])) $url	= $currentUrl[0];

$db_count		= new db_count("SELECT COUNT(*) AS count FROM blog
                               WHERE blo_active = 1 AND blo_cat_id = ". intval($cat_id), __FILE__ . " Line: " . __LINE__);
$total_record	= $db_count->total;
unset($db_count);

if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
$limit	= " LIMIT " . ($page - 1) * $page_size . "," . $page_size;

$db_blog = new db_query("SELECT * FROM blog
                         WHERE blo_active = 1 AND blo_cat_id = ". intval($cat_id) ." ORDER BY blo_id DESC " . $limit);
$i = 1;
while($row  = mysqli_fetch_assoc($db_blog->result)){
   $class   = 'item';
   
   if($i == 1) $class .= ' item_first';
   $title   = $row['blo_title'];
   $teaser  = $row['blo_teaser'];
   $link    = createLinkBlog($row);
   
   $cat_info   = isset($arrayCategory[$row['blo_cat_id']])? $arrayCategory[$row['blo_cat_id']] : array();
   $linkCat = createLinkBlogCategory($cat_info);
   
   $cat_name   = isset($cat_info['bc_name'])? $cat_info['bc_name'] : '';
   
   
   if($i == 1){
      ?>
      <div class="<?=$class?>">
         <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
         <div class="img">
            <a href="<?=$link?>"><img src="/upload/blog/small_<?=$row['blo_image']?>" alt="<?=$title?>" /></a>
         </div>
         <div class="teaser">
            <?=$row['blo_teaser']?>
            <p class="incat">: <?=date('d/m/Y - H:i', $row['blo_date'])?></p>
         </div>
      </div>
      <div style="width: 100%; margin: 10px 0; overflow: hidden;text-align: center;clear: both;">
         <?
         echo ads_show(10);
         ?>
      </div>
      <?
   }else{
      
      $class_center  = '';
      if($i % 3 == 0) $class_center = 'item_center';
      ?>
      <div class="<?=$class . ' ' . $class_center?>">
         <p class="bname"><a href="<?=$link?>" title="<?=$title?>"><?=$title?></a></p>
         <div class="img">
            <a href="<?=$link?>"><img src="/upload/blog/small_<?=$row['blo_image']?>" /></a>
         </div>
         <p class="incat">: <?=date('d/m/Y - H:i', $row['blo_date'])?></p>
      </div>
      <?
   }
   $i++;
}
unset($db_blog);

if($page <= $num_of_page && $num_of_page > 1){
	echo '<div class="pagelist">' . generatePageBar($page_prefix, $page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous, $next, $first, $last, $break_type, 1) . '</div>';
}
?>
