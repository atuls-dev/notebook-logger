<?php class Nl_Logger_Controller extends WP_REST_Controller
{
    public function register_routes()
    {
        $version = '1';
        $namespace = 'nl-logger/v' . $version;
        $base = 'nl-logs';
        register_rest_route($namespace, '/' . $base . '/', [array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array(
                $this,
                'get_logs'
            ) ,
            'permission_callback' => array(
                $this,
                'get_items_permissions_check'
            )
        ) , array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array(
                $this,
                'create_logs'
            ) ,
            'permission_callback' => array(
                $this,
                'create_logs_permissions_check'
            ) ,
            'args' => [],
        ) , ]);
        register_rest_route($namespace, '/' . $base . '/option', [
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array(
                    $this,
                    'create_option'
                ) ,
                'permission_callback' => array(
                    $this,
                    'get_items_permissions_check'
                )
            )
        ]);
        register_rest_route($namespace, '/' . $base . '/options', [array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array(
                $this,
                'get_options'
            ) ,
            'permission_callback' => array(
                $this,
                'get_items_permissions_check'
            )
        )]);
        register_rest_route($namespace, '/' . $base . '/formdata', [array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array(
                $this,
                'get_items'
            ) ,
            'permission_callback' => array(
                $this,
                'get_items_permissions_check'
            )
        )]);
        register_rest_route($namespace, '/' . $base . '/limited/(?P<offset>[\d]+)/(?P<limit>[\d]+)', [array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array(
                $this,
                'get_limited_logs'
            ) ,
            'permission_callback' => array(
                $this,
                'get_items_permissions_check'
            )
        )]);
        register_rest_route($namespace, '/' . $base . '/memberships', [array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array(
                $this,
                'get_memberships'
            ) ,
            'permission_callback' => array(
                 $this,
                'get_items_permissions_check'
            )
        )]);
        register_rest_route($namespace, '/' . $base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array(
                    $this,
                    'get_log'
                ) ,
                'permission_callback' => array(
                    $this,
                    'get_items_permissions_check'
                )
            ) ,
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array(
                    $this,
                    'update_item'
                ) ,
                'permission_callback' => array(
                    $this,
                    'create_logs_permissions_check'
                ) ,
            ) ,
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array(
                    $this,
                    'delete_item'
                ) ,
                'permission_callback' => array(
                    $this,
                    'create_logs_permissions_check'
                ) ,
                'args' => array(
                    'force' => array(
                        'default' => false,
                    ) ,
                ) ,
            ) ,
        ));
    }
    public function get_items_permissions_check($request)
    {

        return is_user_logged_in();
    }
    public function create_logs_permissions_check($request)
    {
        return is_user_logged_in();
    }
    public function get_memberships($request)
    {
        $membership = get_option("logger_mepr_memberships");
        $enabled_mepr_rule = get_option("logger_mepr")?true:false;
        $args = array(
                    'post_type' => 'memberpressproduct',
                    'posts_per_page' => -1,
                    'post__in' => $membership
        );
        if($enabled_mepr_rule && !empty($membership)){
            $membershipData = [];
            $query = get_posts( $args );
            if(!empty($query)){
                foreach ($query as $key => $value) {
                    $membershipData[] = array('id'=>$value->ID , 'title'=>$value->post_title);
                }
                $response = new WP_REST_Response($membershipData);
                $response->set_status(200);
                return $response;
            }else{
                return new WP_Error('empty_logger', 'No memberships selected yet ', array(
                    'status' => 404
                ));
            }
        }else{
           return new WP_Error('empty_logger', 'membership module is disabled', array(
                'status' => 404
            ));
        }

    }
    public function get_items($request)
    {
        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
        $user_id = $request['user_id'];
        $sql = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL)", $notebook_logger_option_db_table, get_current_user_id());
        $get_options = $wpdb->get_results($sql, ARRAY_A);
        $returnData = [];
        foreach ($get_options as $key => $option)
        {
            if ($option['user_id'] != NULL)
            {
                $triggers = (!empty($option['triggers'])) ? array_map('trim', explode(',', $option['triggers'])) : '';
                $emotions = (!empty($option['emotion'])) ? array_map('trim', explode(',', $option['emotion'])) : '';
                $cope     = (!empty($option['cope'])) ? array_map('trim', explode(',', $option['cope'])) : '';
                $returnData['triggers']['your-triggers'] = $triggers;
                $returnData['emotions']['your-emotions'] = $emotions;
                $returnData['copes']['your-copes'] = $cope;
            }
            else
            {
                $triggers = (!empty($option['triggers'])) ? array_map('trim', explode(',', $option['triggers'])) : '';
                $emotions = (!empty($option['emotion'])) ? array_map('trim', explode(',', $option['emotion'])) : '';
                $cope     = (!empty($option['cope'])) ? array_map('trim', explode(',', $option['cope'])) : '';
                $returnData['triggers']['other-triggers'] = $triggers;
                $returnData['emotions']['other-emotions'] = $emotions;
                $returnData['copes']['other-copes'] = $cope;
            }
        }
        $allData = $returnData;
        if (empty($allData))
        {
            return new WP_Error('empty_logger', 'there is no logger data for this user ', array(
                'status' => 404
            ));
        }
        $response = new WP_REST_Response($allData);
        $response->set_status(200);
        return $response;
    }

    public function get_options($request)
    {
        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
       
        $sql = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL)", $notebook_logger_option_db_table, get_current_user_id());
        $get_options = $wpdb->get_results($sql, ARRAY_A);

        //echo "<pre>"; print_r($get_options); echo "</pre>"; exit;
        
        $returnData = [];

        $triggers = $utriggers = $emotions = $uemotions = $copes = $ucopes = array();
        foreach ($get_options as $key => $option)
        {
            if ($option['user_id'] != NULL)
            {
                if( $option['type'] == 'trigger' ){
                    $utriggers[] = $option;
                } else if( $option['type'] == 'emotion' ){
                    $uemotions[] = $option;
                } else if( $option['type'] == 'cope' ){
                    $ucopes[] =  $option;
                }

                $returnData['triggers']['your-triggers'] = $utriggers;
                $returnData['emotions']['your-emotions'] = $uemotions;
                $returnData['copes']['your-copes'] = $ucopes;
            }
            else
            {
                if( $option['type'] == 'trigger' ){
                    $triggers[] = $option;
                } else if( $option['type'] == 'emotion' ){
                    $emotions[] = $option;
                } else if( $option['type'] == 'cope' ){
                    $copes[] =  $option;
                }

                $returnData['triggers']['other-triggers'] = $triggers;
                $returnData['emotions']['other-emotions'] = $emotions;
                $returnData['copes']['other-copes'] = $copes;
            }
        }
        $allData = $returnData;
        if (empty($allData))
        {
            return new WP_Error('empty_logger', 'there is no logger data for this user ', array(
                'status' => 404
            ));
        }
        $response = new WP_REST_Response($allData);
        $response->set_status(200);
        return $response;
    }


    public function add_option( $type, $option ) 
    {
        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
        
        $user_id = get_current_user_id();
        
        if( array_key_exists( 'id', $option ) && !empty( $option['id']) ) {
              
            $sql = sprintf( "SELECT * FROM %s WHERE type='%s' AND id = '%s' ", $notebook_logger_option_db_table, $type, $option['id'] );
            $option_row = $wpdb->get_row($sql);
            if( !empty($option_row)  ) {
                return $option_row->id;
            }else{
                $data = array(
                    'user_id' => sanitize_text_field($user_id) ,
                    'type'    => sanitize_text_field($type),
                    'value'   => sanitize_text_field($option['value'])
                );

                $format = array(
                    '%s',
                    '%s',
                    '%s'
                );

                $results = $wpdb->insert($notebook_logger_option_db_table, $data, $format);
                if ($results) {
                    return $wpdb->insert_id;
                }
            }
        }else if( array_key_exists( 'tempID',$option ) && !empty($option['tempID']) ) {   
          
            $sql = sprintf( "SELECT * FROM %s WHERE type='%s' AND user_id = '%s' AND temp_id = '%s'", $notebook_logger_option_db_table, $type, $user_id, $option['tempID'] );
            $option_row = $wpdb->get_row($sql);
            if( !empty($option_row)  ) {
                return $option_row->id;
            }else{
                $data = array(
                    'user_id' => sanitize_text_field($user_id) ,
                    'type'    => sanitize_text_field($type),
                    'value'   => sanitize_text_field($option['value']),
                    'temp_id' => sanitize_text_field($option['tempID'])
                );

                $format = array(
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                );

                $results = $wpdb->insert($notebook_logger_option_db_table, $data, $format);
                if ($results) {
                    return $wpdb->insert_id;
                }
            }
        }else{

            $sql = sprintf( "SELECT * FROM %s WHERE type='%s' AND ( user_id = '%s' OR user_id IS NULL ) AND value = '%s'", $notebook_logger_option_db_table, $type, $user_id, $option['value'] );
            $option_row = $wpdb->get_row($sql);
             if( !empty($option_row)  ) {
                return $option_row->id;
            }else{
                $data = array(
                        'user_id' => sanitize_text_field($user_id) ,
                        'type'    => sanitize_text_field($type),
                        'value'   => sanitize_text_field($option['value'])
                );

                $format = array(
                    '%s',
                    '%s',
                    '%s'
                );

                $results = $wpdb->insert($notebook_logger_option_db_table, $data, $format);
                if ($results) {
                    return $wpdb->insert_id;
                }
            }

        }

    }
    
    public function add_data($item)
    {
        global $wpdb, $err, $msg;
        /*$item = $request->get_params();*/
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
        $sql = sprintf("SELECT * FROM %s WHERE user_id = '%s'", $notebook_logger_option_db_table, get_current_user_id());
        $setting_info = $wpdb->get_row($sql);
        $sqltrig = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL) AND triggers LIKE  '%s'", $notebook_logger_option_db_table, get_current_user_id(),'%' . $item['trigger'] . '%');
        $sqlemot = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL) AND emotion LIKE '%s'", $notebook_logger_option_db_table, get_current_user_id(),'%' . $item['emotion'] . '%');
        $sqlecop = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL) AND cope LIKE '%s'", $notebook_logger_option_db_table, get_current_user_id(),'%' . $item['cope'] . '%');
        $setting_trig = $wpdb->get_row($sqltrig);
        $setting_emot = $wpdb->get_row($sqlemot);
        $setting_cope = $wpdb->get_row($sqlecop);
        $trigger = '';
        $emotions = '';
        $copes = '';
        if (!empty($setting_info))
        {
            $id = $setting_info->id;
            $trigger = $setting_info->triggers;
            $emotions = $setting_info->emotion;
            $copes = $setting_info->cope;
            if(empty($setting_trig)){
                if (!empty($trigger))
                {
                    $ntrig = (isset($item['trigger'])) ? $trigger . ',' . $item['trigger'] : $trigger;
                }
                else
                {
                    $ntrig = (isset($item['trigger'])) ? $item['trigger'] : '';
                }
            }else{
                $ntrig = $trigger;
            }
            if(empty($setting_emot)){
                if (!empty($emotions))
                {
                    $nemot = (isset($item['emotion'])) ? $emotions . ',' . $item['emotion'] : $emotions;
                }
                else
                {
                    $nemot = (isset($item['emotion'])) ? $item['emotion'] : '';
                }
            }else{
                $nemot = $emotions;
            }
            if(empty($setting_cope)){
                if (!empty($copes))
                {
                    $ncope = (isset($item['cope'])) ? $copes . ',' . $item['emotion'] : $copes;
                }
                else
                {
                    $ncope = (isset($item['cope'])) ? $item['cope'] : '';
                }
            }else{
                $ncope = $copes;
            }
            $result3 = $wpdb->update($notebook_logger_option_db_table, array(
                'triggers' => sanitize_text_field($ntrig) ,
                'emotion' => sanitize_text_field($nemot) ,
                'cope' => sanitize_text_field($ncope) ,
            ) , array(
                'id' => sanitize_text_field($id)
            ) , array(
                '%s',
                '%s',
                '%s',
            ) , array(
                '%d'
            ));
        }
        else
        {
            if(empty($setting_trig)){
                $trigg = (isset($item['trigger']) ) ? $item['trigger'] : '';
            }
            if(empty($setting_emot)){
                $emot = (isset($item['emotion'])) ? $item['emotion'] : '';
            }
            if(empty($setting_cope)){
                $ecope = (isset($item['cope'])) ? $item['cope'] : '';
            }

            $results = $wpdb->insert($notebook_logger_option_db_table, array(
                'triggers' => sanitize_text_field($trigg) ,
                'emotion' => sanitize_text_field($emot) ,
                'cope' => sanitize_text_field($ecope) ,
                'user_id' => get_current_user_id() ,
            ) , array(
                '%s',
                '%s',
                '%s',
            ));
        }

    }
    public function get_logs($request)
    {
        global $wpdb, $err, $msg;
        $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';

        //$sql = sprintf("SELECT * FROM %s WHERE `user_id` = %s ORDER BY `time` DESC", $notebook_logger_db_table, get_current_user_id());

        $sql = sprintf('SELECT log.*, ( select value FROM %1$s opt WHERE log.triggers = opt.id ) as trigger_value, ( select value FROM %1$s optE WHERE log.emotion = optE.id ) as emotion_value, ( select value FROM %1$s opC WHERE log.cope = opC.id ) as cope_value FROM %2$s log WHERE user_id = %3$s ORDER BY time DESC', $notebook_logger_option_db_table, $notebook_logger_db_table , get_current_user_id());


        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        /*$sorted_log = [];
        $list = '<div class="nl_log_wrapper"><div class="nl_log_list">';
        foreach ($Alllogs as $key => $log)
        {
            $sorted_log[$log['created_date']][] = [ 'id' => $log['id'],
                                                    'triggers' => $log['triggers'],
                                                    'emotion' => $log['emotion'],
                                                    'reason' => stripslashes($log['reason']) ,
                                                    'intensity' => $log['intensity'],
                                                    'time' => $log['time'],
                                                    'etype' => $log['etype'],
                                                    'cope' => $log['cope'],
                                                    'created_date' => $log['created_date'],
                                                    'updated_on' => $log['updated_on'],
                                                    'created_on' => $log['created_on'], ];
        }*/
        if (empty($Alllogs))
        {
            return new WP_Error('empty_logger', 'there is no logger data for this user ', array(
                'status' => 404
            ));
        }
        $response = new WP_REST_Response($Alllogs);
        $response->set_status(200);
        return $response;
    }

    public function get_limited_logs($request)
    {
        global $wpdb, $err, $msg;
        $item = $request->get_params();
        $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';

        //$sql = sprintf("SELECT * FROM %s WHERE `user_id` = %s ORDER BY `time` DESC LIMIT %s OFFSET %s", $$notebook_logger_db_table, get_current_user_id(), $item['limit'], $item['offset']);

        $sql = sprintf('SELECT log.*, ( select value FROM %1$s opt WHERE log.triggers = opt.id ) as trigger_value, ( select value FROM %1$s optE WHERE log.emotion = optE.id ) as emotion_value, ( select value FROM %1$s opC WHERE log.cope = opC.id ) as cope_value FROM %2$s log WHERE user_id = %3$s ORDER BY time DESC LIMIT %4$s OFFSET %5$s', $notebook_logger_option_db_table, $notebook_logger_db_table , get_current_user_id(), $item['limit'], $item['offset']);


        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        /*$sorted_log = [];
        $list = '<div class="nl_log_wrapper"><div class="nl_log_list">';
        foreach ($Alllogs as $key => $log)
        {
            $sorted_log[$log['created_date']][] = [ 'id' => $log['id'],
                                                    'triggers' => $log['triggers'],
                                                    'emotion' => $log['emotion'],
                                                    'reason' => stripslashes($log['reason']) ,
                                                    'intensity' => $log['intensity'],
                                                    'time' => $log['time'],
                                                    'etype' => $log['etype'],
                                                    'cope' => $log['cope'],
                                                    'created_date' => $log['created_date'],
                                                    'updated_on' => $log['updated_on'],
                                                    'created_on' => $log['created_on'], ];
        }*/
        if (empty($Alllogs))
        {
            return new WP_Error('empty_logger', 'there is no logger data for this user ', array(
                'status' => 404
            ));
        }
        $response = new WP_REST_Response($Alllogs);
        $response->set_status(200);
        return $response;
    }
    public function get_log($request)
    {
        global $wpdb, $err, $msg;
        $item = $request->get_params();
        $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';

        //$sql = sprintf("SELECT * FROM %s WHERE `id` = %s ", $notebook_logger_db_table, $item['id']);

        $sql = sprintf('SELECT log.*, ( select value FROM %1$s opt WHERE log.triggers = opt.id ) as trigger_value, ( select value FROM %1$s optE WHERE log.emotion = optE.id ) as emotion_value, ( select value FROM %1$s opC WHERE log.cope = opC.id ) as cope_value FROM %2$s log WHERE `id` = %3$s', $notebook_logger_option_db_table, $notebook_logger_db_table , $item['id'] );

        $Alllogs = $wpdb->get_row($sql, ARRAY_A);
        if (empty($Alllogs))
        {
            return new WP_Error('empty_logger', 'there is no logger data for this user ', array(
                'status' => 404
            ));
        }
        $response = new WP_REST_Response($Alllogs);
        $response->set_status(200);
        return $response;
    }
    public function create_logs($request)
    {

        global $wpdb, $err, $msg;
        

        $item = $request->get_params();

        //echo "<pre>"; print_r($item); echo "</pre>"; exit;
        
        $table_name = $wpdb->prefix . "notebook_logger";
        
        $trigger_id = $emotion_id = $cope_id = '';
        if( $item['trigger'] ) {
            $trigger_id =  $this->add_option( 'trigger', $item['trigger'] );
        }
        
        if( $item['emotion'] ) {
            $emotion_id =  $this->add_option( 'emotion', $item['emotion'] );
        }

        if( $item['cope'] ) {
            $cope_id =  $this->add_option( 'cope', $item['cope'] );
        }

        //$time = date('Y-m-d H:i:s', strtotime($item['time']));

        $time_iso = $item['time_iso'];
        $datetime = new DateTime( $item['time_iso'] );
        //$datetime->setTimeZone(  new DateTimeZone('UTC') );
        $time =  $datetime->format( 'Y-m-d H:i:s' );
        //$time_iso =  $datetime->format( DATE_ISO8601 );
        //$time_iso = str_replace("+0000","",$time_iso);

        //$time_iso = date( DATE_ISO8601, strtotime($item['time']));
        $date = date('Y-m-d', strtotime($item['time_iso']));
        $user_id = get_current_user_id();
        $data = array(
            'user_id' => sanitize_text_field($user_id) ,
            'triggers' => $trigger_id,
            'emotion' => $emotion_id ,
            'etype' => sanitize_text_field($item['etype']) ,
            'cope' => $cope_id ,
            'reason' => sanitize_text_field($item['reason']) ,
            'intensity' => sanitize_text_field($item['intensity']) ,
            'time' => sanitize_text_field($time) ,
            'time_iso' => sanitize_text_field($time_iso) ,
            'created_date' => sanitize_text_field($date)
        );
        $format = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        );

        $results = $wpdb->insert($table_name, $data, $format);
        if ($results)
        {
            //$this->add_data($item);
            $success = array(
                'message' => 'Successfully Added Logger',
                'log' => $data
            );
            $response = new WP_REST_Response($success);
            $response->set_status(200);
            return $response;
        }
        return new WP_Error('cant-create', __('You are not authorize to create log', NOTEBOOKLOGGER_TEXTDOMAIN) , array(
            'status' => 500
        ));
    }

    public function update_item($request)
    {
        global $wpdb, $err, $msg;
        $item = $request->get_params();

        $table_name = $wpdb->prefix . "notebook_logger";
        //$time = date('Y-m-d H:i:s', strtotime($item['time']));

        $trigger_id = $emotion_id = $cope_id = '';
        if( $item['trigger'] ) {
            $trigger_id =  $this->add_option( 'trigger', $item['trigger'] );
        }
        
        if( $item['emotion'] ) {
            $emotion_id =  $this->add_option( 'emotion', $item['emotion'] );
        }

        if( $item['cope'] ) {
            $cope_id =  $this->add_option( 'cope', $item['cope'] );
        }

        $time_iso = $item['time_iso'];
        $datetime = new DateTime( $item['time_iso'] );
        //$datetime->setTimeZone(  new DateTimeZone('UTC') );
        $time =  $datetime->format( 'Y-m-d H:i:s' );
        //$time_iso =  $datetime->format( DATE_ISO8601 );
        //$time_iso = str_replace("+0000","",$time_iso);

        //$time_iso = date( DATE_ISO8601, strtotime($item['time']));
        
        $date = date('Y-m-d', strtotime($item['time_iso']));
       
        $results = $wpdb->update($table_name, array(
            'triggers' =>  $trigger_id,
            'emotion' => $emotion_id ,
            'reason' => sanitize_text_field($item['reason']) ,
            'etype' => sanitize_text_field($item['etype']) , 
            'cope' => $cope_id ,
            'intensity' => sanitize_text_field($item['intensity']) ,
            'time' => sanitize_text_field($time) ,
            'time_iso' => sanitize_text_field($time_iso) ,
            'created_date' => sanitize_text_field($date) ,
        ) , array(
            'id' => sanitize_text_field($item['id'])
        ) , array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ) , array(
            '%d'
        ));

        $sql = sprintf( "SELECT * FROM %s WHERE id = '%s' ", $table_name, $item['id'] );
        $data = $wpdb->get_row($sql);

        //$this->add_data($item);
        $success = array(
            'message' => 'Successfully Updated Logger',
            'log' => $data
        );
        $response = new WP_REST_Response($success);
        $response->set_status(200);
        return $response;
        return new WP_REST_Response(true, 200);
        
        return new WP_Error('cant-update', __('You are not authorize to update log', NOTEBOOKLOGGER_TEXTDOMAIN) , array(
            'status' => 500
        ));
    }
    public function delete_item($request)
    {
        global $wpdb, $err, $msg;
        $item = $request;
        $table = $wpdb->prefix . "notebook_logger";
        $results = $wpdb->delete($table, array(
            'id' => $item['id']
        ));
        if ($results)
        {
            return new WP_REST_Response(array(
                'message' => 'logger deleted successfully'
            ) , 200);
        }
        return new WP_Error('cant-delete', __('You are not authorize to delete item', NOTEBOOKLOGGER_TEXTDOMAIN) , array(
            'status' => 500
        ));
    }

    public function create_option($request)
    {
        global $wpdb, $err, $msg;
        $item = $request->get_params();
        $table_name = $wpdb->prefix . "notebook_logger_options";

        if( empty($item['type']) && empty($item['value']) ) {
            return new WP_Error('cant-create', __('Please enter required parameters', NOTEBOOKLOGGER_TEXTDOMAIN) , array(
                'status' => 200
            ));
        }

        $user_id = get_current_user_id();
        $data = array(
                'user_id' => sanitize_text_field($user_id) ,
                'type'    => sanitize_text_field($item['type']),
                'value'   => sanitize_text_field($item['value'])
        );

        $format = array(
            '%s',
            '%s',
            '%s'
        );

        $results = $wpdb->insert($table_name, $data, $format);
        if ($results)
        {
            $data['id'] = $wpdb->insert_id;
            $success = array(
                'message' => 'Successfully Added Option',
                'data' => $data
            );
            $response = new WP_REST_Response($success);
            $response->set_status(200);
            return $response;
        }
        return new WP_Error('cant-create', __('You are not authorize to add option', NOTEBOOKLOGGER_TEXTDOMAIN) , array(
            'status' => 500
        ));
    }
    
}
add_action('rest_api_init', function ()
{
    $nl_logger_controller = new Nl_Logger_Controller();
    $nl_logger_controller->register_routes();
}); ?>
