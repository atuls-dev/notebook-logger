<?php
global $wpdb,$err,$msg;
$notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
$sql1 = sprintf("SELECT * FROM %s WHERE user_id IS NULL AND type = 'trigger' ", $notebook_logger_option_db_table);
$trigger_info = $wpdb->get_results($sql1, ARRAY_A);

$sql2 = sprintf("SELECT * FROM %s WHERE user_id IS NULL AND type = 'emotion' ", $notebook_logger_option_db_table);
$emotion_info = $wpdb->get_results($sql2, ARRAY_A);

$sql3 = sprintf("SELECT * FROM %s WHERE user_id IS NULL AND type = 'cope' ", $notebook_logger_option_db_table);
$cope_info = $wpdb->get_results($sql3, ARRAY_A);

/*$sql = sprintf("SELECT * FROM %s WHERE user_id IS NULL", $notebook_logger_option_db_table);

$setting_info = $wpdb->get_results($sql, ARRAY_A);*/

//echo "<pre>"; print_r($setting_info); echo "</pre>"; exit;

// $trigger = '';
// $emotions = '';
// if (!empty($setting_info))
// {
// 	$id = $setting_info->id;
// 	$trigger = $setting_info->triggers;
// 	$emotions = $setting_info->emotion;
// 	$cope = $setting_info->cope;
	
// }
?>
<style>
.nl-tbl-form {
    margin-bottom: 20px;
}

.nl-tbl-form th {
    width: 20%;
    text-align: left;
}

.nlbtnAdd {
    background: #2271b1;
    color: #fff;
    padding: 5px 10px;
    border: 0px;
}

.nlbtnRemove {
    background: ;
    background: transparent;
    border: 0;
    color: #2271b1;
    text-decoration: underline;
}
</style>

<div class="wrap">
<h2>Notebook Logger</h2>
<div class="content_wrapper">
<div class="left">
<form method="post" action="">
    <?php wp_nonce_field('nl_insert_setting'); ?>


    <table class="nl-tbl-form tblTrigger">
    	<?php  if( !empty($trigger_info) ) {
    		$i = 1;
    		foreach ( $trigger_info as $val ) { ?>
        <tr valign="top">
        	<?php if ($i == 1 ) { ?>
			<th scope="row">Triggers<em>*</em></th>
			<?php }else{ ?>
			<th></th>
			<?php } ?>

			<td>
				<input type="text"  class="regular-text" name="option[trigger][<?= $val['id'] ?>]"  value="<?=$val['value']?>" >
			</td>

			<td>
				<?php if ($i == 1 ) { ?>
					<button class="nlbtnAdd" data-type="trigger" type="button"> Add New</button>
				<?php }else{ ?>
					<button class="nlbtnRemove" data-id="<?= $val['id'] ?>" type="button">Remove</button>
				<?php } ?>
			</td>
        </tr>
        <?php $i++; 
    		} 
    	}else { ?>
    	<tr valign="top">
			<th scope="row">Triggers<em>*</em></th>
			<td>
				<input type="text"  class="regular-text" name="option[trigger][new][]"  value="" >
			</td>
			<td>
				<button class="nlbtnAdd" data-type="trigger" type="button"> Add New</button>
			</td>
        </tr>
    <?php } ?>
    </table>
    <table class="nl-tbl-form tblEmotion">
    	<?php  if( !empty($emotion_info) ) {
    		$i = 1;
    		foreach ( $emotion_info as $val ) { ?>
        <tr valign="top">
        	<?php if ($i == 1 ) { ?>
			<th scope="row">Emotions<em>*</em></th>
			<?php }else{ ?>
			<th></th>
			<?php } ?>

			<td>
				<input type="text"  class="regular-text" name="option[emotion][<?= $val['id'] ?>]"  value="<?=$val['value']?>" >
			</td>

			<td>
				<?php if ($i == 1 ) { ?>
					<button class="nlbtnAdd" data-type="emotion" type="button"> Add New</button>
				<?php }else{ ?>
					<button class="nlbtnRemove" data-id="<?= $val['id'] ?>" type="button">Remove</button>
				<?php } ?>
			</td>
        </tr>
        <?php $i++; 
    		} 
    	}else { ?>
        <tr valign="top">
			<th scope="row">Emotions<em>*</em></th>
			<td>
				<input type="text"  class="regular-text" name="option[emotion][new][]"  value="" >
			</td>
			<td>
				<button class="nlbtnAdd" data-type="emotion" type="button"> Add New</button>
			</td>
        </tr>
        <?php } ?>
    </table>
    <table class="nl-tbl-form tblCope">
    	<?php  if( !empty($cope_info) ) {
    		$i = 1;
    		foreach ( $cope_info as $val ) { ?>
        <tr valign="top">
        	<?php if ($i == 1 ) { ?>
			<th scope="row">Copes<em>*</em></th>
			<?php }else{ ?>
			<th></th>
			<?php } ?>

			<td>
				<input type="text"  class="regular-text" name="option[cope][<?= $val['id'] ?>]"  value="<?=$val['value']?>" >
			</td>

			<td>
				<?php if ($i == 1 ) { ?>
					<button class="nlbtnAdd" data-type="cope" type="button"> Add New</button>
				<?php }else{ ?>
					<button class="nlbtnRemove" data-id="<?= $val['id'] ?>" type="button">Remove</button>
				<?php } ?>
			</td>
        </tr>
        <?php $i++; 
    		} 
    	}else { ?>
        <tr valign="top">
			<th scope="row">Copes<em>*</em></th>
			<td>
				<input type="text"  class="regular-text" name="option[cope][new][]"  value="" >
			</td>
			<td>
				<button class="nlbtnAdd" data-type="cope" type="button"> Add New</button>
			</td>
        </tr>
    	<?php } ?>
    </table>

<!--     <table class="form-table">
        <tr valign="top">
			<th scope="row">Triggers<em>*</em></th>
			<td>
			 <textarea name="trigger" id="trigger" class="regular-text"><?php echo $trigger; ?></textarea><br /><i>Add comma(,) seprated options</i>
			</td>
			<td>
				<button class="btnAdd" data-type="trigger" type="button"> Add New</button>
			</td>
        </tr>

        <tr valign="top">
			<th scope="row">Emotions<em>*</em></th>
			<td>
				<textarea name="emotion" id="emotion" class="regular-text"><?php echo $emotions; ?></textarea><br /><i>Add comma(,) seprated options</i>
				
				
			</td>
        </tr>

        <tr valign="top">
			<th scope="row">Copes<em>*</em></th>
			<td>
				<textarea name="cope" id="cope" class="regular-text"><?php echo $cope; ?></textarea><br /><i>Add comma(,) seprated options</i>
				
				
			</td>
        </tr>

    </table> -->

	<input type="hidden" name="action" value="update" />
	<!-- <?php if (!empty($setting_info)) { ?>
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
	<?php } else {?>
		<input type="hidden" name="action" value="update" />
	<?php } ?> -->

    <p class="submit"><input id="submit_button" name="submit_logger" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
</form>
</div>
</div>
</div>
<!-- <script>
	
    jQuery(document).ready(function() {

    	function input_row(type) {
    		var html  = '<tr valign="top">'
				+ '<th></th>'
				+ '<td>'
				+   '<input type="text"  class="regular-text" name="option[' + type + '][new][]"  value="" >'
				+ '</td><td>'
				+ '<button class="nlbtnRemove" type="button">Remove</button>'
				+ '</td></tr>';
			return html;
    	}
      	jQuery(".nlbtnAdd").click(function(){ 
      		var type = jQuery(this).attr('data-type');
          	var row = input_row(type)
          	if( type == 'trigger'){
          		jQuery(".tblTrigger").append(row);
          	}else if( type == 'emotion' ){
          		jQuery(".tblEmotion").append(row);
          	}else if( type == 'cope' ){
          		jQuery(".tblCope").append(row);
          	}
      	});


		jQuery("body").on("click",".nlbtnRemove",function(){ 
		    jQuery(this).parents("tr").remove();
		});

    });
</script> -->