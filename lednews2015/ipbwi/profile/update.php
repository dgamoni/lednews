<!--dynamic-cached-content-->
<?php // Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	// get user info
	$userInfo = $GLOBALS['ipbwi']->member->info();
?>
<!-- You can start editing here. -->
	<h3>Board Settings</h3>
	<table class="form-table">
<?php
	if(get_option('ipbwi_sso_advanced_profile') != ''){
?>
		<tr>
			<th><label for="ipbwi_pp_about_me"><p>About Me</label></th>
			<td><div style="overflow:auto;"><p><?php echo $GLOBALS['ipbwi']->bbcode->printTextEditor($userInfo['pp_about_me'],'ipbwi_pp_about_me'); ?></p></div></td>
		</tr>
		<tr>
			<th><label for="ipbwi_signature">Signature</label></th>
			<td><div style="overflow:auto;"><p><?php echo $GLOBALS['ipbwi']->bbcode->printTextEditor($userInfo['signature'],'ipbwi_signature'); ?></p></div></td>
		</tr>
		<tr>
			<th><label for="ipbwi_bday">Birthday</label></th>
			<td>
				<select name="ipbwi_bday_month">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 12){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_month']) ? ' selected="selected"' : '').'>'.$GLOBALS['ipbwi']->getLibLang('month_'.$i).'</option>';
		$i++;
	}
?>
				</select>
				<select name="ipbwi_bday_day">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 31){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_day']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
?>
				</select>
				<select name="ipbwi_bday_year">
					<option value="">--</option>
<?php
	$i = $GLOBALS['ipbwi']->date(time(),'%Y');
	while($i >= 1910){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_year']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i--;
	}
?>
				</select>
			</td>
		</tr>
<?php
	}
	if(get_option('ipbwi_sso_custom_profile_fields') != ''){
		$fields = $GLOBALS['ipbwi']->member->listCustomFields();
		if(is_array($fields) && count($fields) > 0){
			foreach($fields as $field){
				echo '<tr>';
				// if current custom field is an text-input-field
				if($field['pf_type'] == 'input'){ echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><input name="ipbwi_field_'.$field['pf_id'].'" value="'.$GLOBALS['ipbwi']->member->customfieldValue($field['pf_id']).'" /></td>'; }
				// if current custom field is an text-area
				elseif($field['pf_type'] == 'textarea'){ echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><textarea name="ipbwi_field_'.$field['pf_id'].'" rows="5" cols="30">'.$GLOBALS['ipbwi']->member->customfieldValue($field['pf_id']).'</textarea></td>'; }
				// if current custom field is an drop-down-box
				elseif($field['pf_type'] == 'drop'){
					echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td>';
					echo '<td><select name="ipbwi_field_'.$field['pf_id'].'">';
					$fieldcontentvar = split("[\n|]",$field['pf_content']); // split contentlines
					for($x=0;$x<count($fieldcontentvar);$x++){ // load all contentlines
						$fieldcontentset = explode('=',$fieldcontentvar[$x]); // explode var and set
						if($GLOBALS['ipbwi']->member->customFieldValue($field['pf_id']) == $fieldcontentset[0]){
							$selected = ' selected="selected"'; }else{ $selected = '';
						}
						echo '<option value="'.$fieldcontentset[0].'"'.$selected.'>'.$fieldcontentset[1].'</option>';
					}
					echo '</select></td>';
				}
				echo '</tr>';
			}
		}
	}
?>
</table>
<!--/dynamic-cached-content-->