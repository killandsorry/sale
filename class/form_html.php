<?
/**
 * Class generate form html
 */

class generate_form_html{

	var $arrayField					= array();
	var $arrayFieldDefaultValue	= array();
	var $arrayFieldCheck				= array();
	var $tableData						= '';
	var $error							= '';

	/**
	 * add Field
	 * $field: ten field
	 * $typeData: kieu du lieu ( str, int, email, select)
	 * $checkempty: co check rong hay khong (1 co, 0 khong)
	 * $error: bao loi khi khong nhap du lieu
	 * $default: gia tri mac dinh cua bien
	 * $getfield: lay gia tri o dau, (1 bien gloal, 0 bien t form)
	 */
 	function addField($field='', $typeData='str', $checkempty=1, $error = '', $default='', $getfield = 0){
		$this->arrayField[]	= $field;
		$this->arrayFieldDefaultValue[$field]	= $default;
		$this->arrayFieldCheck[$field]['type'] = $typeData;
		$this->arrayFieldCheck[$field]['check'] = $checkempty;
		$this->arrayFieldCheck[$field]['error'] = $error;
		$this->arrayFieldCheck[$field]['get'] 	= $getfield;
 	}

	/**
	 * add table database
	 * $table: ten table save data
	 */
 	function addTable($table=''){
 		$this->tableData	= $table;
 	}

	/**
	 * create open form
	 * $method: phuong thuc for (post, get)
	 * $action: url action
	 * $encrypt
	 */
	function create_open_form($method = 'post', $action = '', $encrypt = "multipart/form-data"){
		$html	= '';
		$html	.= '<form method="'. $method .'" action="'. $action .'" enctype ="'. $encrypt .'">';
		return $html;
	}

	/**
	 * create open table
	 */
	function create_open_table(){
		$html	= '';
		$html	.= '<table cellpadding="0" cellspacing="0" border="0" class="formcontrol" width="100%">';
		return $html;
	}

	/**
	 * create close table
	 */
	function create_close_table(){
		$html	= '';
		$html	.= '</table>';
		return $html;
	}

	/**
	 * add row error notifi
	 */
 	function create_error_form($error){
 		$html	= '';
		$html	.= '<tr><td class="fem_name"></td>';
		$html	.= '<td><span class="formerror" id="formerror">'. $error .'</span></td></tr>';
		return $html;
 	}

 	/**
	 * add row sucess notifi
	 */
 	function create_success_form($success){
 		$html	= '';
		$html	.= '<tr><td class="fem_name"></td>';
		$html	.= '<td><span class="formsuccess" id="formsuccess">'. $success .'</span></td></tr>';
		return $html;
 	}

 	/**
	 * add button submit
	 * $text: ten o button (vidu submit)
	 */
 	function create_submit_form($text= '', $name = 'action', $value = 'action', $element = '', $style=""){
 		$html	= '';
 		$name	= (trim($name) == '')? 'action' : trim($name);
 		$obj	= ($element != '')? "{elm:'". $element ."'}" : "";
		$html	.= '<tr><td class="fem_name"></td>';
		$html	.= '<td>
						<input type="submit" '. $style .' value="'. $text .'" name="sb" class="btn_sb" onclick="return checkDataSubmit('. $obj .');" />
						<input type="hidden" value="'. $value .'" name="'. $name .'"  />
					</td></tr>';
		return $html;
 	}

	/**
	 * create open form
	 */
	function create_close_form(){
		$html	= '';
		$html	.= '</form>';
		return $html;
	}

	/**
	 * add input text
	 * $field: ten truong du lieu
	 * $typeData: kieu du lieu (str, int, email)
	 * $defaultData: du lieu mac dinh
	 * $checkempty: co check rong hay khoong (1 cos, 0: khong)
	 * $title: ten dau dong cua bien nay
	 * $holder: ten sugget
	 * $maxlen: do dai toi da dc hien thi
	 */
 	function add_text($field='', $typeData ='str', $defaultData ='', $checkempty = 1, $title='', $holder='', $maxlen=100, $width='', $height=''){
 		$style	= '';
 		$defaultData	= str_replace('"', '&quot', $defaultData);
 		$style	= 'style="width:'. (($width == '100%')? '100%' : $width . 'px') .';'. $height .':'. $height .'px;"';
 		$html	= '';
 		$html	.= '<tr>';
 		$html	.= '<td class="fem_name"><span class="formname">'. (($checkempty == 1)? '<b style="color:#f00;">*</b>' : '') . $title .': </span></td>';
 		$html	.= '<td>
		 				<input '. $style . ( ($checkempty == 1)? 'class="iput"' : "" ) .' data-type="'. $typeData .'" data-er="error_'. $field .'" type="text" value="'. $defaultData .'" name="'. $field .'" id="'. $field .'" '. ( ($maxlen > 0)? 'maxlength="'. $maxlen .'"' : '' ) .' placeholder="'. $holder .'" />
		 				<span id="error_'. $field .'" class="formrowerror"></span>
		 			</td>';
 		$html	.= '</tr>';
		return $html;
 	}

 	/**
	 * add input password text
	 * $field: ten truong du lieu
	 * $typeData: kieu du lieu (str, int, email)
	 * $defaultData: du lieu mac dinh
	 * $checkempty: co check rong hay khoong (1 cos, 0: khong)
	 * $title: ten dau dong cua bien nay
	 * $holder: ten sugget
	 * $maxlen: do dai toi da dc hien thi
	 */
 	function add_password($field='', $typeData ='str', $defaultData ='', $checkempty = 1, $title='', $holder='', $maxlen=100, $width='', $height=''){
 		$style	= '';
 		$style	= 'style="width:'. (($width == '100%')? '100%' : $width . 'px') .';'. $height .':'. $height .'px;"';
 		$html	= '';
 		$html	.= '<tr>';
 		$html	.= '<td class="fem_name"><span class="formname">'. (($checkempty == 1)? '<b style="color:#f00;">*</b>' : '') . $title .': </span></td>';
 		$html	.= '<td>
		 				<input autocomplete="off" '. $style . ( ($checkempty == 1)? 'class="iput"' : "" ) .' data-type="'. $typeData .'" data-er="error_'. $field .'" type="password" value="'. $defaultData .'" name="'. $field .'" id="'. $field .'" '. ( ($maxlen > 0)? 'maxlength="'. $maxlen .'"' : '' ) .' placeholder="'. $holder .'" />
		 				<span id="error_'. $field .'" class="formrowerror"></span>
		 			</td>';
 		$html	.= '</tr>';
		return $html;
 	}

 	/**
	 * add input text
	 * $field: ten truong du lieu
	 * $typeData: kieu du lieu (str, int, email)
	 * $defaultData: du lieu mac dinh
	 * $checkempty: co check rong hay khoong (1 cos, 0: khong)
	 * $title: ten dau dong cua bien nay
	 * $holder: ten sugget
	 * $maxlen: do dai toi da dc hien thi
	 */
 	function add_textarea($field='', $typeData ='str', $defaultData ='', $checkempty = 1, $title='', $holder='', $maxlen=100, $width='500', $height='100', $html_intro = ''){
 		$style	= '';
 		$defaultData	= str_replace('"', '&quot', $defaultData);
 		$style	= 'style="width:'. (($width == '100%')? '100%' : $width . 'px') .';'. $height .':'. $height .'px;"';
 		$html	= '';
 		$html	.= '<tr>';
 		$html	.= '<td class="fem_name"><span class="formname">'. (($checkempty == 1)? '<b style="color:#f00;">*</b>' : '') . $title .': </span></td>';
 		$html	.= '<td>
 						<textarea '. $style . ( ($checkempty == 1)? 'class="iput"' : "" ) .' data-type="'. $typeData .'" data-er="error_'. $field .'" spellcheck="false" type="text" name="'. $field .'" id="'. $field .'" '. ( ($maxlen > 0)? 'maxlength="'. $maxlen .'"' : '' ) .' placeholder="'. $holder .'">'. $defaultData .'</textarea>
 						'. $html_intro .'
		 				<span id="error_'. $field .'" class="formrowerror"></span>
		 			</td>';
 		$html	.= '</tr>';
		return $html;
 	}

	/**
	 * add input select
	 * $field: ten truong du lieu
	 * $typeData: kieu du lieu (str, int, email)
	 * $defaultData: du lieu mac dinh
	 * $checkempty: co check rong hay khoong (1 cos, 0: khong)
	 * $title: ten dau dong cua bien nay
	 * $holder: ten sugget
	 * $maxlen: do dai toi da dc hien thi
	 */
 	function add_select($field='', $typeData ='str', $defaultData ='', $arrayData=array(), $checkempty = 1, $title='', $holder='', $maxlen='', $width='500', $height='100'){
 		$style	= '';
 		$style	= 'style="width:'. $width .'px;'. $height .':'. $height .'px;"';
 		$html	= '';
 		$html	.= '<tr>';
 		$html	.= '<td class="fem_name"><span class="formname">'. (($checkempty == 1)? '<b style="color:#f00;">*</b>' : '') . $title .': </span></td>';
 		$html	.= '<td>
 						<select '. $style . ( ($checkempty == 1)? 'class="iput"' : "" ) .' data-type="'. $typeData .'" data-er="error_'. $field .'" name="'. $field .'" id="'. $field .'">
							<option value="-1">-- Chọn '. $title .' --</option>';
							foreach($arrayData as $k => $value){
								$html	.= '<option '. ( ($k==$defaultData)? 'selected' : '' ) .' value="'. $k .'">'. $value .'</option>';
							}
		$html	.= 	 '</select>
		 				<span id="error_'. $field .'" class="formrowerror"></span>
		 			</td>';
 		$html	.= '</tr>';
		return $html;
 	}

	/**
	 * Function add security
	 */
 	function add_seciruty($title='Mã bảo mật', $width = 200){
 		$html	= '';
 		$html	.= '<tr>';
			$html	.= '<td class="fem_name" style="vertical-align: bottom;"><span class="formname"><b style="color:#f00;">*</b>'. $title .': </span></td>';
			$html	.= '<td>';
				$html	.= '<div class="security">
								<p class="code_img"><img id="security" src="/captcha.php" alt="Mã bảo mật" /></p>
								<p class="code_text">
									<input style="width:'. $width .'px;" type="text" value="" name="security" placeholder="Nhập mã bảo mật" />
									<span title="Load lại mã" class="code_reload ic" onclick="document.getElementById(\'security\').src=\'/captcha.php?\'+Math.random();"></span>
								</p>
							</div>';
			$html	.= '</td>';
 		$html	.= '</tr>';
 		return $html;
 	}

	/**
	 * create avarialble
	 */
 	function create_avariable_form(){
 		if(!empty($this->arrayField)){
 			foreach($this->arrayField as $k => $avariable){
 				$temp_avariable	= $avariable;
 				global $$temp_avariable;
 				if(isset($avariable['get'])){
 					$$temp_avariable = $$temp_avariable;
				}else{
 					$$temp_avariable	= isset($_POST[$avariable])? $_POST[$avariable] : $this->arrayFieldDefaultValue[$avariable];
				}
 			}
 		}
 	}

 	/**
 	 * function check data form
 	 */
 	function check(){
 		$arrayCheck	= $this->arrayFieldCheck;
 		if(!empty($arrayCheck)){
			foreach($arrayCheck as $field => $array){
				if($array['check'] == 1){
					global $$field;
					if($array['get']){
						$$field	= $$field;
					}else{
						$$field	= isset($_POST[$field]) ? trim($_POST[$field]) : '';
					}
					switch($array['type']){
						case 'str':
							if($$field	== ''){
								$this->error	.= $array['error'] . '<br>';
							}
							break;
						case 'int':
							if($$field	<= 0){
								$this->error	.= $array['error'] . '<br>';
							}
							break;
						case 'select':
							if($$field	== -1){
								$this->error	.= $array['error'] . '<br>';
							}
							break;
						case 'phone':
							if($$field	== ''){
								$this->error	.= $array['error'] . '<br>';
							}else{
								if(strlen($$field) < 10 && strlen($$field) > 11){
									$this->error	.= 'Bạn nhập chưa đúng số điện thoại <br>';
								}
								if(substr($$field, 0, 2) == '09'){
									if(strlen($$field) != 10){
										$this->error	.= 'Bạn nhập chưa đúng số điện thoại <br>';
									}
								}
								if(substr($$field, 0, 6) == '016' || substr($$field, 0, 6) == '012'){
									if(strlen($$field) != 11){
										$this->error	.= 'Bạn nhập chưa đúng số điện thoại <br>';
									}
								}
							}
							break;
						case 'email':
							$result = ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$", $$field, $trashed);
							if(!$result){
								$this->error	.= $array['error'] . "<br />";
							}
					}
				}
			}
		}
		return $this->error;
 	}

	/**
	 * function generate_sql_insert())
	 */
 	function generate_sql_insert(){
 		$query			= '';
 		$arr_sql			= array();
 		$arr_sqlvalue	= '';
 		$arrayField		= $this->arrayFieldCheck;
 		if(!empty($arrayField)){
 			foreach($arrayField as $field => $array){
				$arr_sql[]	= $field;
				global $$field;
				if($array['get']){
					$$field	= $$field;
				}else{
					$$field	= isset($_POST[$field]) ? trim($_POST[$field]) : '';
				}

				switch($array['type']){
					case 'int':
					case 'select':
						$arr_sqlvalue[]	= intval($$field);
						break;
					case 'str':
					case 'email':
						$arr_sqlvalue[]	= "'" . replaceMQ(htmlspecialbo($$field)) . "'";
						break;
				}
 			}
 		}

 		if(!empty($arr_sql) && !empty($arr_sqlvalue)){
			$query = "INSERT IGNORE INTO " . $this->tableData . "(". implode(',', $arr_sql) .")
						 VALUES (". implode(',', $arr_sqlvalue) .")";
 		}
 		return $query;
 	}

	/**
	 * function generate_sql_update())
	 * field: id
	 * $value: id value where field = value
	 */
 	function generate_sql_update($idfield, $value){
 		$idfield			= trim($idfield);
 		$value			= intval($value);
 		if($idfield == '') return '';
 		if($value <= 0) return '';

 		$query			= '';
 		$arr_sqlvalue	= '';
 		$arrayField		= $this->arrayFieldCheck;
 		if(!empty($arrayField)){
 			foreach($arrayField as $field => $array){
				global $$field;
				if($array['get']){
					$$field	= $$field;
				}else{
					$$field	= isset($_POST[$field]) ? trim($_POST[$field]) : '';
				}

				switch($array['type']){
					case 'int':
					case 'select':
						$arr_sqlvalue[]	= $field . "=" . intval($$field);
						break;
					case 'str':
					case 'email':
						$arr_sqlvalue[]	= $field . "='" . replaceMQ(htmlspecialbo($$field)) . "'";
						break;
				}
 			}
 		}

 		if(!empty($arr_sqlvalue)){
			$query = "UPDATE " . $this->tableData . " SET ". implode(',', $arr_sqlvalue) ."
						 WHERE ". $idfield ."=" . $value;
 		}
 		return $query;
 	}
}// end classes