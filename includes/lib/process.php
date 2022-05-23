<?php

global $wpdb, $err, $msg;

if (isset($_POST['submit_logger']) && check_admin_referer('nl_insert_setting')) {
    if ($_POST['action'] == 'update') {

        $err = "";
        $msg = "";


        if ($err == '') {

            $table_name = $wpdb->prefix . "notebook_logger_options";

            foreach( $_POST['option']['trigger'] as $key  => $val ) {

                if( $key == 'new' ){
                   
                    foreach( $val as $sval ){
                        if( !empty($sval) ) {
                            $wpdb->insert(
                                $table_name,
                                array(
                                    'type' => 'trigger',
                                    'value' => sanitize_text_field($sval),
                                ),
                                array(
                                    '%s',
                                    '%s'
                                )
                            );
                        }
                    }

                }else{
                    if( !empty($val) ) {
                        $wpdb->update(
                            $table_name,
                            array(
                                'type' => 'trigger',
                                'value' => sanitize_text_field($val),
                            ), 
                            array('id' => sanitize_text_field($key)),
                            array(
                                '%s',
                                '%s'
                            ),
                            array('%d')
                            );
                    }
                    
                }   
            }

            foreach( $_POST['option']['emotion'] as $key  => $val ) {

                if( $key == 'new' ){
                    foreach( $val as $sval ){
                        if( !empty($sval) ) {

                            $wpdb->insert(
                                $table_name,
                                array(
                                    'type' => 'emotion',
                                    'value' => sanitize_text_field($sval),
                                ),
                                array(
                                    '%s',
                                    '%s'
                                )
                            );
                        }
                    }

                }else{
                    if( !empty($val) ) {
                        $wpdb->update(
                            $table_name,
                            array(
                                'type' => 'emotion',
                                'value' => sanitize_text_field($val),
                            ), 
                            array('id' => sanitize_text_field($key)),
                            array(
                                '%s',
                                '%s'
                            ),
                            array('%d')
                            );
                    }
                    
                }   
            }
            

            foreach( $_POST['option']['cope'] as $key  => $val ) {

                if( $key == 'new' ){
                   
                    foreach( $val as $sval ){
                        if( !empty($sval) ) {
                            $wpdb->insert(
                                $table_name,
                                array(
                                    'type' => 'cope',
                                    'value' => sanitize_text_field($sval),
                                ),
                                array(
                                    '%s',
                                    '%s'
                                )
                            );
                        }
                    }

                }else{
                    if( !empty($val) ) {
                        $wpdb->update(
                            $table_name,
                            array(
                                'type' => 'cope',
                                'value' => sanitize_text_field($val),
                            ), 
                            array('id' => sanitize_text_field($key)),
                            array(
                                '%s',
                                '%s'
                            ),
                            array('%d')
                            );
                    }
                    
                }   
            }

        }
    }

    if ($_POST['action'] == 'edit' and $_POST['id'] != '') {
        $err = "";
        $msg = "";


        if ($err == '') {
            $table_name = $wpdb->prefix . "notebook_logger_options";
            $result3 = $wpdb->update(
                    $table_name,
                    array(
                        'triggers' => sanitize_text_field($_POST['trigger']),
                        'emotion' => sanitize_text_field($_POST['emotion']),
                        'cope' => sanitize_text_field($_POST['cope']),
                    ),
                    array('id' => sanitize_text_field($_POST['id'])),
                    array(
                        '%s',
                        '%s',
                        '%s',
                    ),
                    array('%d')
            );

            if (false === $result3) {
                $err .= "Update fails !" . "<br />";
            } else {
                $msg = "Update successful !" . "<br />";
            }
        }
    }
}
/*echo $wpdb->last_query;
die;*/
