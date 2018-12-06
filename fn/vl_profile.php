<?
/**
 * generate hash action
 */
function generate_hash_action($key = '', $uid = 0, $action_id = 0){
	$keyhash	= 'kjkjdsf024kjd9(dnknks(83kkf';
	$hash		= '';
	if($key == '' || $uid == 0) return $hash;

	$hash		= md5($keyhash .'|'. $uid . '|' . $action_id . '|ac');
	return $hash;
}

/**
 * generate html profile add
 */
function generate_html_profile_add($array = array()){
	global $arrayProfile, $arrayLevel, $arraylanguage_level, $arrayScore, $array_marrie, $array_myson,$arrayCategory;
	$key  = isset($array['key'])? $array['key'] : '';

	$html	= '';
	if($key == '') return $html;
	if(!isset($arrayProfile[$key])) return $html;

	// month
	$month	= '<option value="-1">Tháng</option>';
	for($m = 1; $m < 13 ; $m++){
		$month .= '<option value="'. $m .'">'.$m.'</option>';
	}

	// year
	$year	= '<option value="-1">Năm</option>';
	for($y = 1960; $y < (date('Y')+50) ; $y++){
		$year .= '<option value="'. $y .'">'.$y.'</option>';
	}

	// level
	$level	= '';
	foreach($arrayLevel as $k => $lv){
		$level .= '<option value="'. $k .'">'.$lv.'</option>';
	}

	// level
	$language_level	= '';
	foreach($arraylanguage_level as $k => $lv){
		$language_level .= '<option value="'. $k .'">'.$lv.'</option>';
	}

	// score
	$score_lv	= '';
	foreach($arrayScore as $k => $lv){
		$score_lv .= '<option value="'. $k .'">'.$lv.'</option>';
	}

	// marrie
	$marrie	= '';
	foreach($array_marrie as $k => $lv){
		$marrie .= '<option value="'. $k .'">'.$lv.'</option>';
	}

	// myson
	$myson	= '';
	foreach($array_myson as $k => $lv){
		$myson .= '<option value="'. $k .'">'.$lv.'</option>';
	}

	// category
	$category	= '';
	foreach($arrayCategory as $k => $cat){
		$category .= '<option value="'. $cat['cat_id'] .'">'.$cat['cat_name'].'</option>';
	}


	$html .= '<ul class="addnew_row">';

	foreach($arrayProfile[$key]['field'] as $id => $vl){
		$control	= '';
		if($id == 'date'){
			$i = 0;
			foreach($vl as $k => $date){
				$i++;
				$first_name	= ($i == 1)? 'Từ' : 'Đến';
				$prefix		= ($i == 1)? 'from_month' : 'to_month';
				if(isset($date['source']) && $date['source'] == 'month_year'){
					$req		= isset($date['req'])? $date['req'] : 0;
					$class	= ($req == 1)? 'addControl' : '';
					$control .= '<div class="inline_row">';
						$control	.= '<span class="add_name">'. $first_name. ' tháng/năm' . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>';
						$control	.= '<p>
											<select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'. $prefix .'" id="'. $prefix .'">'. $month .'</select>
											<select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'. $k .'" id="'. $k .'">'. $year .'</select>
										</p>';
					$control .= '</div>';
					if($i == 1)$control .= ' -- ';
				}else{
					$req		= isset($date['req'])? $date['req'] : 0;
					$class	= ($req == 1)? 'addControl' : '';
					$control .= '<div class="inline_row">';
						$control	.= '<span class="add_name">'. $date['text'] . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>';
						$control	.= '<p><select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'.$k.'" id="'.$k.'">'. (isset($date['source'])? $$date['source'] : '') .'</select></p>';
					$control .= '</div>';
					if($i == 1)$control .= ' -- ';
				}
			}

		}else{
			$req	= isset($vl['req'])? $vl['req'] : 0;
			$class	= ($req == 1)? 'addControl ' : '';
			$type		= isset($vl['type'])? $vl['type'] : 'text';
			$control .= ($type != 'check')? ('<span class="add_name">'. $vl['text'] . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>') : '';
			$holder	= isset($vl['holder'])? 'placeholder="'.$vl['holder'].'"' : '';
			$class .= isset($vl['class'])? $vl['class'] : '';
			$attr	= isset($vl['attr'])? $vl['attr'] : '';

			switch ($vl['type']){
				case 'text':
					if(strpos($id, 'born') !== false) $control .= '<script>call_address();</script>';
					$control	.= '<p><input '. $attr . ' ' . $holder .' class="'.$class.'" data-check="'. $req .'" type="text" value="" id="'. $id .'" name="'. $id .'" /></p>';
					break;
				case 'textarea':
					$control	.= '<p><textarea  '. $attr . ' ' .  $holder .' spellcheck="false" class="'.$class.'" data-check="'. $req .'" name="'.$id.'" id="'.$id.'"></textarea></p>';
					break;
				case 'select':
					$control	.= '<p><select '. $attr . '  class="'.$class.'" data-check="'.$req.'" data-type="int" name="'.$id.'" id="'.$id.'">'.(isset($vl['source'])? $$vl['source'] : '').'</select></p>';
					break;
				case 'check':
					$control	.= '<p><input '. $attr . ' type="checkbox" class="'. $class .'" data-check="'.$req.'" name="'.$id.'" id="'.$id.'" value="1" /> <label for="'.$id.'">'.$vl['text'].'</label></p>';
					break;
			}
			if(isset($vl['note'])){
				$control .= '<p class="add_note">'. $vl['note'] .'</p>';
			}
		}
		$html	.= '<li class="add_row">'. $control .'</li>';
	}
	if(isset($arrayProfile[$key]['hidden'])) $html .= '<input type="hidden" name="'. $arrayProfile[$key]['hidden'] .'" id="'. $arrayProfile[$key]['hidden'] .'" />';
	$html .= '	<li><p class="submit_error no">Vui lòng nhập vào những ô có đánh dấu đỏ</p></li>
					<li class="add_row">
					<input class="add_save" type="button" value="Lưu"  onclick="return check_before_send(\''. $key .'\');" />
					<input type="hidden" name="key" value="'. $key .'" />
					<span class="add_cancel" onclick="profile_cancel(\''. $key .'\')">Hủy</span>
					</li>
				</ul>';
	return $html;

}

/**
 * function generate html row profile
 *
 */
function generate_html_profile_view($k = '',$data = ''){
	global $arrayProfile, $arrayLevel, $arraylanguage_level, $arrayScore, $array_marrie, $array_myson, $myuser, $arrayCategory;
	$html	= '';
	if($data == '' || $k == '') return $html;
	if(!isset($arrayProfile[$k])) return $html;

	$content	= json_decode(base64_url_decode($data), 1);
	if(!is_array($content)) return $html;

	$name	= $arrayProfile[$k]['name'];
	$icon	= 'flaticon-' . $arrayProfile[$k]['icon'];
	$html	.= '<div class="add_field_profile bgf">
					<h3 class="profile_item_title view_title"></span>'. $name .'<span onclick="addHtmlProfile({k:\''. $k .'\'})" class="add_button">+ Thêm '. $name .'</span></h3>';

	foreach($content[$k] as $id => $vl){
		$hash	= generate_hash_action($k, $myuser->u_id, $id);
		$html	.= '<div class="profile_subitem profile_edit"  data-hash="'.$hash.'" data-key="'.$k.'" data-id="'.$id.'" onclick="edit_profile(this)">';
		foreach($arrayProfile[$k]['field'] as $fk => $fvalue){
			if($fk == 'date'){
				$text = 'Khoảng thời gian';
				$class		= isset($fvalue['class'])? 'class="'. $fvalue['class'] .'"' : 'class="itl"';
				$html	.= '<p '. $class .' title="' . $text . '">';

				$i = 0;
				foreach($fvalue as $dk => $dvalue){
					$i++;
					$getMonth	= '';
					$vlMonth		= '';
					if($dvalue['source'] == 'month_year'){
						$getMonth	= 'to_month';
						if($i == 1) $getMonth = 'from_month';
						if(isset($vl[$getMonth]) && $vl[$getMonth] > 0) $vlMonth = (($vl[$getMonth] < 10)? '0'.$vl[$getMonth] : $vl[$getMonth]);
					}
					$para	= isset($vl[$dk])? $vl[$dk] : 0;
					if($para > 0){
						$html	.= (($i > 1)? ' - ' : '') . ( ($vlMonth != '')? $vlMonth .'/' : '' ) . $para;
					}
				}
				$html	.= '</p>';
			}else{
				$text 	= isset($fvalue['text'])? $fvalue['text'] : '';
				$ftype	= isset($fvalue['type'])? $fvalue['type'] : 'text';
				$class		= isset($fvalue['class'])? 'class="'. $fvalue['class'] .'"' : 'class="itl"';
				$fsource	= isset($fvalue['source'])? $fvalue['source'] : '';
				$para		= isset($vl[$fk])? htmlspecialbo($vl[$fk]) : '';
				if($ftype == 'select'){
					switch($fsource){
						case 'language_level':
							$para = isset($arraylanguage_level[$para])? $arraylanguage_level[$para] : '';
							break;
						case 'marrie':
							$para =  isset($array_marrie[$para])? $array_marrie[$para] : '';
							break;
						case 'level':
							$para =  isset($arrayLevel[$para])? $arrayLevel[$para] : '';
							break;
						case 'score_lv':
							$para =  isset($arrayScore[$para])? $arrayScore[$para] : '';
							break;
						case 'myson':
							$para =  isset($array_myson[$para])? $array_myson[$para] : '';
							break;
						case 'category':
							$para =  isset($arrayCategory[$para])? $arrayCategory[$para]['cat_name'] : '';
							break;
					}

				}else if($ftype == 'check'){
					$para =  ($para == 1)? $text : '';
				}
				if($ftype == 'hidden') $para = '';
				if($para != ''){
					$html	.= '<p '. $class .' title="'. $text .'">'.nl2br($para).'</p>';
				}
			}
		}
		$html .= '<p class="profile_action">
						<span data-hash="'.$hash.'" data-key="'.$k.'" data-id="'.$id.'" onclick="edit_profile(this)"><i class="icon12 iconcl flaticon-pencils6"></i>Sửa</span>
						<span data-hash="'.$hash.'" data-key="'.$k.'" data-id="'.$id.'" onclick="delete_profile(this)"><i class="icon12 iconcl tran45 flaticon-add30"></i>Xóa</span></p>';
		$html	.= '</div>';
	}
	$html	.= '</div>';
	return $html;
}

/**
 * view profile
 */
function html_profile_view($k = '',$data = ''){
	global $arrayProfile, $arrayLevel, $arraylanguage_level, $arrayScore, $array_marrie, $array_myson, $myuser, $arrayCategory;
	$html	= '';
	if($data == '' || $k == '') return $html;
	if(!isset($arrayProfile[$k])) return $html;

	$content	= json_decode(base64_url_decode($data), 1);
	if(!is_array($content)) return $html;

	$name	= $arrayProfile[$k]['name'];
	$icon	= 'flaticon-' . $arrayProfile[$k]['icon'];
	$html	.= '<div class=" bgf ov">
					<h3 class="pro_view_title"><span class="'. $icon .' icon30"></span>'. $name .'</h3>';

	$class_small	= '';
	if($k == 'language'){
		$class_small = 'small_div';
	}
	foreach($content[$k] as $id => $vl){
		$hash	= generate_hash_action($k, $myuser->u_id, $id);
		$html	.= '<div class="profile_subitem '. $class_small .'" >';
		foreach($arrayProfile[$k]['field'] as $fk => $fvalue){
			if($fk == 'date'){
				$text = 'Khoảng thời gian';
				$class		= isset($fvalue['class'])? 'class="'. $fvalue['class'] .'"' : 'class="itl"';
				$html	.= '<p '. $class .' title="' . $text . '">';

				$i = 0;
				foreach($fvalue as $dk => $dvalue){
					$i++;
					$getMonth	= '';
					$vlMonth		= '';
					if($dvalue['source'] == 'month_year'){
						$getMonth	= 'to_month';
						if($i == 1) $getMonth = 'from_month';
						if(isset($vl[$getMonth]) && $vl[$getMonth] > 0) $vlMonth = (($vl[$getMonth] < 10)? '0'.$vl[$getMonth] : $vl[$getMonth]);
					}
					$para	= isset($vl[$dk])? $vl[$dk] : 0;
					if($para > 0){
						$html	.= (($i > 1)? ' - ' : '') . ( ($vlMonth != '')? $vlMonth .'/' : '' ) . $para;
					}
				}
				$html	.= '</p>';
			}else{
				$text 	= isset($fvalue['text'])? $fvalue['text'] : '';
				$ftype	= isset($fvalue['type'])? $fvalue['type'] : 'text';
				$class		= isset($fvalue['class'])? 'class="'. $fvalue['class'] .'"' : 'class="itl"';
				$fsource	= isset($fvalue['source'])? $fvalue['source'] : '';
				$para		= isset($vl[$fk])? htmlspecialbo($vl[$fk]) : '';
				if($ftype == 'select'){
					switch($fsource){
						case 'language_level':
							$para = isset($arraylanguage_level[$para])? $arraylanguage_level[$para] : '';
							break;
						case 'marrie':
							$para =  isset($array_marrie[$para])? $array_marrie[$para] : '';
							break;
						case 'level':
							$para =  isset($arrayLevel[$para])? $arrayLevel[$para] : '';
							break;
						case 'score_lv':
							$para =  isset($arrayScore[$para])? $arrayScore[$para] : '';
							break;
						case 'myson':
							$para =  isset($array_myson[$para])? $array_myson[$para] : '';
							break;
						case 'category':
							$para =  isset($arrayCategory[$para])? $arrayCategory[$para]['cat_name'] : '';
							break;
					}

				}else if($ftype == 'check'){
					$para =  ($para == 1)? $text : '';
				}
				if($ftype == 'hidden') $para = '';
				if($para != ''){
					$html	.= '<p '. $class .' title="'. $text .'">'.nl2br($para).'</p>';
				}
			}
		}
		$html	.= '</div>';
	}
	$html	.= '</div>';
	return $html;
}

/**
 * generate html profile edit
 */
function generate_html_profile_edit($key= '', $default_Data = array(), $index = 0){
	global $arrayProfile, $arrayLevel, $arraylanguage_level, $arrayScore, $array_marrie, $array_myson, $myuser,$arrayCategory;

	$html	= '';
	if($key == '') return $html;
	if(!isset($arrayProfile[$key])) return $html;

	$html .= '<ul class="addnew_row">';

	foreach($arrayProfile[$key]['field'] as $id => $vl){
		$control	= '';
		if($id == 'date'){
			$i = 0;
			foreach($vl as $k => $date){
				$i++;
				$first_name	= ($i == 1)? 'Từ' : 'Đến';
				$prefix		= ($i == 1)? 'from_month' : 'to_month';
				$current_year	= isset($default_Data[$k])? $default_Data[$k] : 0;
				$current_month	= isset($default_Data[$prefix])? $default_Data[$prefix] : 0;
				// year
				$year	= '<option value="-1">Năm</option>';
				for($y = 1960; $y < (date('Y')+50) ; $y++){
					$sl = ($y == $current_year)? 'selected="selected"' : '';
					$year .= '<option '. $sl .' value="'. $y .'">'.$y.'</option>';
				}

				// month
				$month	= '<option value="-1">Tháng</option>';
				for($m = 1; $m < 13 ; $m++){
					$sl = ($m == $current_month)? 'selected="selected"' : '';
					$month .= '<option '. $sl .' value="'. $m .'">'.$m.'</option>';
				}
				if(isset($date['source']) && $date['source'] == 'month_year'){
					$req		= isset($date['req'])? $date['req'] : 0;
					$class	= ($req == 1)? 'addControl' : '';

					$control .= '<div class="inline_row">';
						$control	.= '<span class="add_name">'. $first_name. ' tháng/năm' . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>';
						$control	.= '<p>
											<select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'. $prefix .'" id="'. $prefix .'">'. $month .'</select>
											<select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'. $k .'" id="'. $k .'">'. $year .'</select>
										</p>';
					$control .= '</div>';
					if($i == 1)$control .= ' -- ';
				}else{

					$req		= isset($date['req'])? $date['req'] : 0;
					$class	= ($req == 1)? 'addControl' : '';
					$control .= '<div class="inline_row">';
						$control	.= '<span class="add_name">'. $date['text'] . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>';
						$control	.= '<p><select class="'. $class .'" data-check="'. $req .'" data-type="int" name="'.$k.'" id="'.$k.'">'. (isset($date['source'])? $$date['source'] : '') .'</select></p>';
					$control .= '</div>';
					if($i == 1)$control .= ' -- ';
				}
			}

		}else{
			$req	= isset($vl['req'])? $vl['req'] : 0;
			$class	= ($req == 1)? 'addControl ' : '';
			$type		= isset($vl['type'])? $vl['type'] : 'text';
			$control .= ($type != 'check')? ('<span class="add_name">'. $vl['text'] . ( ($req == 1)? ' <b>*</b>' : '' ) .'</span>') : '';
			$curren_value	= isset($default_Data[$id])? $default_Data[$id] : '';
			$holder	= isset($vl['holder'])? 'placeholder="'.$vl['holder'].'"' : '';
			$class .= isset($vl['class'])? $vl['class'] : '';
			$attr	= isset($vl['attr'])? $vl['attr'] : '';
			switch ($vl['type']){
				/*
				case 'hidden':
					$control	.= '<input class="classhidden" type="hidden" value="'. $curren_value .'" name="'.$id.'" />';
					break;
					*/
				case 'text':
					if(strpos($id, 'born') !== false) $control .= '<script>call_address();</script>';
					$control	.= '<p><input '. $attr .' ' .  $holder .' class="'.$class.'" data-check="'. $req .'" type="text" value="'.$curren_value.'" id="'. $id .'" name="'. $id .'" /></p>';
					break;
				case 'textarea':
					$control	.= '<p><textarea '. $attr .' ' . $holder .' spellcheck="false" class="'.$class.'" data-check="'. $req .'" name="'.$id.'" id="'.$id.'">'.$curren_value.'</textarea></p>';
					break;
				case 'select':
					// level
					$level	= '';
					foreach($arrayLevel as $k => $lv){
						$sl	= ($curren_value == $k)? 'selected="selected"' : '';
						$level .= '<option '. $sl .' value="'. $k .'">'.$lv.'</option>';
					}

					// level
					$language_level	= '';
					foreach($arraylanguage_level as $k => $lv){
						$sl	= ($curren_value == $k)? 'selected="selected"' : '';
						$language_level .= '<option '. $sl .' value="'. $k .'">'.$lv.'</option>';
					}

					// score
					$score_lv	= '';
					foreach($arrayScore as $k => $lv){
						$sl	= ($curren_value == $k)? 'selected="selected"' : '';
						$score_lv .= '<option '. $sl .' value="'. $k .'">'.$lv.'</option>';
					}

					// marrie
					$marrie	= '';
					foreach($array_marrie as $k => $lv){
						$sl	= ($curren_value == $k)? 'selected="selected"' : '';
						$marrie .= '<option '. $sl .' value="'. $k .'">'.$lv.'</option>';
					}

					// myson
					$myson	= '';
					foreach($array_myson as $k => $lv){
						$sl	= ($curren_value == $k)? 'selected="selected"' : '';
						$myson .= '<option '. $sl .' value="'. $k .'">'.$lv.'</option>';
					}

					// year
					$year	= '<option value="-1">Năm</option>';
					for($y = 1960; $y < (date('Y')+50) ; $y++){
						$sl = ($y == $curren_value)? 'selected="selected"' : '';
						$year .= '<option '. $sl .' value="'. $y .'">'.$y.'</option>';
					}

					// category
					$category	= '';
					foreach($arrayCategory as $k => $cat){
						$sl = ($k == $curren_value)? 'selected="selected"' : '';
						$category .= '<option '. $sl .' value="'. $cat['cat_id'] .'">'.$cat['cat_name'].'</option>';
					}

					$control	.= '<p><select class="'.$class.'" data-check="'.$req.'" data-type="int" name="'.$id.'" id="'.$id.'">'.(isset($vl['source'])? $$vl['source'] : '').'</select></p>';
					break;
				case 'check':
					$sl = ($curren_value == 1)? 'checked="checked"' : '';
					$control	.= '<p><input '. $attr .' ' .$sl.' type="checkbox" class="'. $class .'" data-check="'.$req.'" name="'.$id.'" id="'.$id.'" value="1" /> <label for="'.$id.'">'.$vl['text'].'</label></p>';
					break;
			}
			if(isset($vl['note'])){
				$control .= '<p class="add_note">'. $vl['note'] .'</p>';
			}
		}
		$html	.= '<li class="add_row">'. $control .'</li>';
	}

	// index là id của bản ghi trong giá trị key
	$hash	= generate_hash_action($key, $myuser->u_id, $index);
	if(isset($arrayProfile[$key]['hidden'])){
		$id_hidden	= $arrayProfile[$key]['hidden'];
		$default_vl	= isset($default_Data[$id_hidden])? $default_Data[$id_hidden] : 0;
		$html .= '<input type="hidden" name="'. $arrayProfile[$key]['hidden'] .'" value="'. $default_vl .'" id="'. $arrayProfile[$key]['hidden'] .'" />';
	}
	$html .= '	<li><p class="submit_error no">Vui lòng nhập vào những ô có đánh dấu đỏ</p></li>
					<li class="add_row">
					<input class="add_save" type="button" value="Lưu"  onclick="return check_before_send(\''. $key .'\');" />
					<input type="hidden" name="key" value="'. $key .'" />
					<input type="hidden" name="action_'. $key .'" id="action_'. $key .'" value="edit" />
					<input type="hidden" name="hash" id="hash" value="'.$hash.'" />
					<input type="hidden" name="id" id="id" value="'.$index.'" />
					<span class="add_cancel" onclick="profile_cancel(\''. $key .'\')">Hủy</span>
					</li>
				</ul>';
	return $html;

}