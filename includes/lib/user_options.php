<?php
global $wpdb,$err,$msg;
$notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
$sql = sprintf("SELECT * FROM %s WHERE user_id = '%s'", $notebook_logger_option_db_table,get_current_user_id());

$setting_info = $wpdb->get_row($sql);
$trigger = '';
$emotions = '';
if (!empty($setting_info))
{
	$id = $setting_info->id;
	$trigger = $setting_info->triggers;
	$emotions = $setting_info->emotion;
	$cope = $setting_info->cope;
	if(!empty($trigger)){
		$ntrig = (isset($_POST['type']) && $_POST['type'] == 'triggers')?$trigger.','.$_POST['value']:$trigger;
	}else{
		$ntrig = (isset($_POST['type']) && $_POST['type'] == 'triggers')?$_POST['value']:'';
	}
	if(!empty($emotions)){
		$nemot = (isset($_POST['type']) && $_POST['type'] == 'emotion')?$emotions.','.$_POST['value']:$emotions;
	}else{
		$nemot = (isset($_POST['type']) && $_POST['type'] == 'emotion')?$_POST['value']:'';
	}
	if(!empty($cope)){
		$ncope = (isset($_POST['type']) && $_POST['type'] == 'cope')?$cope.','.$_POST['value']:$cope;
	}else{
		$ncope = (isset($_POST['type']) && $_POST['type'] == 'cope')?$_POST['value']:'';
	}
	
	$result3 = $wpdb->update(
	    $notebook_logger_option_db_table,
	    array(
	        'triggers' => sanitize_text_field($ntrig),
	        'emotion' => sanitize_text_field($nemot),
	        'cope' => sanitize_text_field($ncope),
	    ),
	    array( 'id' => sanitize_text_field($id) ),
	    array(
	        '%s',
	        '%s',
	        '%s',
	    ),
	    array( '%d' )
	);

	
}else{
	$trigg = (isset($_POST['type']) && $_POST['type'] == 'triggers')?$_POST['value']:'';
	$emot  = (isset($_POST['type']) && $_POST['type'] == 'emotion')?$_POST['value']:'';
	$ecope  = (isset($_POST['type']) && $_POST['type'] == 'cope')?$_POST['value']:'';
	$results = $wpdb->insert(
	            $notebook_logger_option_db_table,
	            array(
	                'triggers' => sanitize_text_field($trigg),
	                'emotion' => sanitize_text_field($emot),
	                'cope' => sanitize_text_field($ecope),
	                'user_id' => get_current_user_id(),
	                
	            ),
	            array(
	                '%s',
	                '%s',
	                '%s',
	                '%s',
	               
	            )
	        );
}




if (false === $result3){
    $err .= "Update fails !". "<br />";
}
else
{
    $msg = "Update successful !". "<br />";
}
