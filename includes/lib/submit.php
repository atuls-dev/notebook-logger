<?php
global $wpdb,$err,$msg;

       
       if ($_POST['notebook_type'] == 'add')
        {

            $err = "";
            $msg = "";

            
            if ($err == '')
            {
                
                $table_name = $wpdb->prefix . "notebook_logger";
                //$time = date('Y-m-d H:i:s' ,strtotime($_POST['time']));

                //$timezone_offset_minutes = $_POST['timeZone'];
                // Convert minutes to seconds
                //$timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
                //$datetime = new DateTime( $_POST['time'], new DateTimeZone($_POST['timeZone']) );
                //$time_iso =  $datetime->format(DateTime::ATOM);
                
                $datetime = new DateTime( $_POST['time'], new DateTimeZone($_POST['timeZone']) );
                $datetime->setTimeZone(  new DateTimeZone('UTC') );
                $time =  $datetime->format( 'Y-m-d H:i:s' );
                $time_iso =  $datetime->format( DATE_ISO8601 );
                $time_iso = str_replace("+0000","",$time_iso);

                $date  = date('Y-m-d',strtotime($_POST['time']));
                $etype = (isset($_POST['etype']))?$_POST['etype']:'smoking';
                $results = $wpdb->insert(
                    $table_name,
                    array(
                        'user_id'       => sanitize_text_field($_POST['user_id']),
                        'triggers'      => sanitize_text_field($_POST['trigger']),
                        'emotion'       => sanitize_text_field($_POST['emotion']),
                        'etype'         => sanitize_text_field($etype),
                        'cope'          => sanitize_text_field($_POST['cope']),
                        'reason'        => sanitize_text_field($_POST['reason']),
                        'intensity'     => sanitize_text_field($_POST['intensity']),
                        'time'          => sanitize_text_field($time),
                        'time_iso'      => sanitize_text_field($time_iso),
                        'created_date'  => sanitize_text_field($date),
                        
                    ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                    )
                );

                if (!$results){
                    echo json_encode(array('status'=>400,'msg'=>'something went Wrong'));
                    die;
                }else{
                    echo json_encode(array('status'=>200,'msg'=>'Entry Added successfully'));
                    die;
                }
                    
            }
            
        }
        if ( $_POST['notebook_type'] == 'edit' and $_POST['id'] != '' ){
            $err = "";
            $msg = "";


            if ($err == '')
            {
                $table_name = $wpdb->prefix . "notebook_logger";
                //$time       = date('Y-m-d H:i:s',strtotime($_POST['time']));
                
                $datetime = new DateTime( $_POST['time'], new DateTimeZone($_POST['timeZone']) );
                $datetime->setTimeZone(  new DateTimeZone('UTC') );
                $time =  $datetime->format( 'Y-m-d H:i:s' );
                $time_iso =  $datetime->format( DATE_ISO8601 );
                $time_iso = str_replace("+0000","",$time_iso);

                $date  = date('Y-m-d',strtotime($_POST['time']));
                $etype = (isset($_POST['etype']))?$_POST['etype']:'smoking';
                $results = $wpdb->update(
                    $table_name,
                    array(
                        'triggers'      => sanitize_text_field($_POST['trigger']),
                        'emotion'       => sanitize_text_field($_POST['emotion']),
                        'reason'        => sanitize_text_field($_POST['reason']),
                        'etype'         => sanitize_text_field($etype),
                        'cope'          => sanitize_text_field($_POST['cope']),
                        'intensity'     => sanitize_text_field($_POST['intensity']),
                        'time'          => sanitize_text_field($time),
                        'time_iso'      => sanitize_text_field($time_iso),
                        'created_date'  => sanitize_text_field($date),
                    ),
                    array( 'id' => sanitize_text_field($_POST['id']) ),
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                    ),
                    array( '%d' )
                );

                if (!$results){
                    echo json_encode(array('status'=>400,'msg'=>'something went Wrong'));
                    die;
                }else{
                    echo json_encode(array('status'=>200,'msg'=>'Entry Updated successfully'));
                    die;
                }
            }

        }
          
      
/*echo $wpdb->last_query;
die;*/
