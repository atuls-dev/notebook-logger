<?php 
/**
 * Plugin Name: Notebook Logger
 * Plugin URI: https://github.com/atuls-dev
 * Description: This Plugin is used to allows users to enter logs (like a diary).
 * Version: 1.3.0
 * Author: Atul
 * Author URI: https://github.com/atuls-dev
 * Text Domain: notebooklogger
 * */
defined('ABSPATH') or die();
define('NOTEBOOKLOGGER', plugin_dir_url(__FILE__));
define('NOTEBOOKLOGGER_PATH', plugin_dir_path(__FILE__));
define('NOTEBOOKLOGGER_TEXTDOMAIN', 'notebooklogger');
define('NL_ASSETS_URL', NOTEBOOKLOGGER . 'includes/assets/');
define('NL_INCLUDE_PATH', NOTEBOOKLOGGER_PATH . 'includes/');
require NL_INCLUDE_PATH . 'helper.php';
global $nl_logger;
class Notebook_logger {

    public function __construct() {
        add_action('init', array($this, 'init_notebook_logger'));
        register_activation_hook(__FILE__, array(__CLASS__, 'notebook_logger_activated'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'notebook_logger_deactivated'));
        add_action('init', array($this, 'nl_process_post'));
        add_action('admin_menu', array($this, 'nl_add_menu_pages'));
        add_action('wp_footer', array($this, 'nl_frontend_button'));
        add_action('wp_enqueue_scripts', array($this, 'nl_assets'));

        add_action("wp_ajax_nloptions", array($this, "nloptions_function"));
        add_action("wp_ajax_nopriv_nloptions", array($this, "nloptions_function"));

        add_action("wp_ajax_nlRemoveOption", array($this, "nlRemoveOption_function"));
        add_action("wp_ajax_nopriv_nlRemoveOption", array($this, "nlRemoveOption_function"));

        add_action("wp_ajax_nl_set_user_current_timezone", array($this, "set_user_current_timezone"));
        add_action("wp_ajax_nopriv_nl_set_user_current_timezone", array($this, "set_user_current_timezone"));

        add_action("wp_ajax_nlnewoptions", array($this, "nlnewoptions_function"));
        add_action("wp_ajax_nopriv_nlnewoptions", array($this, "nlnewoptions_function"));
        add_action("wp_ajax_nlsubmission", array($this, "nlsubmission_function"));
        add_action("wp_ajax_nopriv_nlsubmission", array($this, "nlsubmission_function"));
        add_action("wp_ajax_delete_log", array($this, "delete_log_function"));
        add_action("wp_ajax_nopriv_delete_log", array($this, "delete_log_function"));
        add_action("wp_ajax_filter_admin_stats", array($this, "filter_admin_stats_function"));
        add_action("wp_ajax_nopriv_filter_admin_stats", array($this, "filter_admin_stats_function"));
        add_shortcode("Notebooklogger", array($this, "nl_shortcode_function"));
        add_shortcode("All_Notebook_Static_admin", array($this, "notebook_admin_shortcode"));
        add_shortcode("Notebook_Static", array($this, "notebook_shortcode"));
        add_shortcode("Notebook_Charts", array($this, "notebook_chart_shortcode"));
        add_filter('body_class', array($this, "nl_logger_body_class"));
        if($this->is_buddyboss_active() && get_option("nl_buddypress_tab")){
            add_action( 'bp_setup_nav', array($this,'profile_tab_nllogger') );
        }
        add_action( 'admin_enqueue_scripts', array( $this, 'nl_admin_assets' ) );
        add_action('wp_head',array($this, 'systemTimezone'));
    }

    public function notebook_logger_activated() {
        require_once( NL_INCLUDE_PATH . "lib/activation.php");
    }

    public function notebook_logger_deactivated() {
        require_once( NL_INCLUDE_PATH . "lib/deactivation.php");
    }

    public function nl_add_menu_pages() {
        add_menu_page('NoteBook Logger', 'NoteBook Logger', 'manage_options', 'nl_main_page', array($this, 'nl_main_page_page_fn'), 'dashicons-book', 12);
        add_submenu_page('nl_main_page', 'NoteBook Entries', 'NoteBook Entries', 'manage_options', 'nl_entries_page', array($this, 'nl_entries_page_page_fn'));
        add_submenu_page('nl_main_page', 'Triggers & Emotions', 'NoteBook Options', 'manage_options', 'nl_settings_page', array($this, 'nl_settings_page_page_fn'));
        add_submenu_page('nl_main_page', 'API Endpoints', 'API Endpoints', 'manage_options', 'nl_api_page', array($this, 'nl_api_page_page_fn'));
    }
    public function systemTimezone()
    {
       ?>
        <script type="text/javascript">
            function setCookie(cname, cvalue, exdays) {
              var d = new Date();
              d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
              var expires = "expires="+d.toUTCString();
              document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }
            setCookie('system_timezone',Intl.DateTimeFormat().resolvedOptions().timeZone,1);

        </script>
       <?php
    }
    public function nl_main_page_page_fn() {
        ?>
        <style type="text/css">
            .nl-esi-shadow .sec-title {
                border: 1px solid #ebebeb;
                background: #fff;
                color: #d54e21;
                padding: 2px 4px;
            }
            .nl-esi-shadow{
                border:1px solid #ebebeb; padding:5px 20px; background:#fff; margin-bottom:40px;
                -webkit-box-shadow: 4px 4px 10px 0px rgba(50, 50, 50, 0.1);
                -moz-box-shadow:    4px 4px 10px 0px rgba(50, 50, 50, 0.1);
                box-shadow:         4px 4px 10px 0px rgba(50, 50, 50, 0.1);
            }
        </style>
        <div class="wrap">
            <h1>Notebook Logger</h1>
            <form method="post" action="options.php" class="nl-esi-shadow">
                <?php
                settings_fields("nl-options");
                do_settings_sections("nl-plugin-options");
                submit_button();
                ?>
            </form>
            <fieldset class="nl-esi-shadow">
                <legend><h4 class="sec-title">Using Shortcode</h4></legend>
                <p><input onclick="this.select();" readonly="readonly" type="text" value="[Notebooklogger]" class="large-text" /></p>
            </fieldset>
            <fieldset class="nl-esi-shadow" style="margin-bottom:0px;">
                <legend><h4 class="sec-title">Using PHP Template Tag</h4></legend>
                <p><strong>Simple Use</strong></p>
                <p>You can add<code> btn-nl-logger </code> class to any button to open logger popup</p>
                <p>If you are familiar with PHP code, then you can use PHP Template Tag</p>
                <pre><code>&lt;?php echo do_shortcode('[Notebooklogger]'); ?&gt;</code></pre>
            </fieldset>

            <fieldset class="nl-esi-shadow" style="margin-bottom:0px;">
                <legend><h4 class="sec-title">Notebook Stats Shortcode With Limit user specific</h4></legend>
                <p><strong>Simple Use</strong></p>
                <p>You can use<code>[Notebook_Static type="triggers" limit='5']</code> For listing Triggers Listing </p>

                <p>You can use<code>[Notebook_Static type="emotion" limit='5']</code> For listing Craving Thought Listing </p>

                <p>You can use<code>[Notebook_Static type="intensity" limit='5']</code> For listing Intensity Listing </p>

                <p>You can use<code>limit='5'</code> For set limit of any type listing</p>


                <p>If you are familiar with PHP code, then you can use PHP Template Tag</p>
                <pre><code>&lt;?php echo do_shortcode('[Notebook_Static type="triggers" limit='5']'); ?&gt;</code></pre>
            </fieldset>

            <fieldset class="nl-esi-shadow" style="margin-bottom:0px;">
                <legend><h4 class="sec-title">Notebook Admin Stats Shortcode With Limit</h4></legend>
                <p><strong>Simple Use</strong></p>
                <p>You can use<code>[All_Notebook_Static_admin type="triggers" limit='5']</code> For listing Triggers Listing </p>

                <p>You can use<code>[All_Notebook_Static_admin type="emotion" limit='5']</code> For listing Craving Thought Listing </p>

                <p>You can use<code>[All_Notebook_Static_admin type="intensity" limit='5']</code> For listing Intensity Listing </p>

                <p>You can use<code>limit='5'</code> For set limit of any type listing</p>


                <p>If you are familiar with PHP code, then you can use PHP Template Tag</p>
                <pre><code>&lt;?php echo do_shortcode('[All_Notebook_Static_admin type="triggers" limit='5']'); ?&gt;</code></pre>
            </fieldset>

        </div>
        <?php
    }
    public function nl_entries_page_page_fn()
    {
        $NlEntriesTable = new Nl_entries_List_Table();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>User Entries</h2>

                <?php $NlEntriesTable->views(); ?>
                <?php $NlEntriesTable->prepare_items(); ?>
                <form id="events-filter" method="get">
                <?php $NlEntriesTable->search_box('Search', 'search'); ?>

                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php $NlEntriesTable->display(); ?>
                </form>

            </div>
        <?php
    }
    public function nl_settings_page_page_fn() {
        require_once( NL_INCLUDE_PATH . "lib/logger.php");
    }
    public function nl_api_page_page_fn() {
        require_once( NL_INCLUDE_PATH . "api/documentation.php");
    }

    public function init_notebook_logger() {
        if ( ! $this->is_memberpress_active() ) {
            delete_option("logger_mepr");
            delete_option("logger_mepr_memberships");
        }
        if ( ! $this->is_buddyboss_active() ) {
            delete_option("nl_buddypress_tabs");
        }
        load_theme_textdomain(NOTEBOOKLOGGER_TEXTDOMAIN, false, basename(dirname(__FILE__)) . '/languages');
        //require NL_INCLUDE_PATH . 'widget.php';
        require NL_INCLUDE_PATH . 'settings.php';
    }

    public function nl_process_post() {
        require_once( NL_INCLUDE_PATH . "lib/process.php");
    }

    public function nl_frontend_button() {

            if($this->check_logger_membership()){
                return;
            }
            require NL_INCLUDE_PATH . 'frontend/frontend.php';

    }

    public function nloptions_function() {
        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
        $sql = sprintf("SELECT * FROM %s WHERE (`user_id` = %s || `user_id` IS NULL)", $notebook_logger_option_db_table, get_current_user_id());


        $get_options = $wpdb->get_results($sql, ARRAY_A);
        $returnData = [];
        $triggers = array();
        $utriggers = array();
        $emotions = array();
        $uemotions = array();
        $copes = array();
        $ucopes = array();
        foreach ($get_options as $key => $option) {


           // echo "<pre>"; print_r($option); echo "</pre>";
            
            if ($option['user_id'] != NULL) {
                if( $option['type'] == 'trigger' ){
                    $utriggers[] = $option;
                } else if( $option['type'] == 'emotion' ){
                    $uemotions[] = $option;
                } else if( $option['type'] == 'cope' ){
                    $ucopes[] =  $option;
                }
                $returnData['myData'] = array('trigger' => $utriggers, 'emotion' => $uemotions,'cope'=>$ucopes);
            } else {
                if( $option['type'] == 'trigger' ){
                    $triggers[] = $option;
                } else if( $option['type'] == 'emotion' ){
                    $emotions[] = $option;
                } else if( $option['type'] == 'cope' ){
                    $copes[] =  $option;
                }
                $returnData['otherData'] = array('trigger' => $triggers, 'emotion' => $emotions,'cope'=>$copes);
            }
        }
        extract($_POST);
        if (isset($type) && !empty($type)) {
            $triggers = $value;
            $filterData = [];
            foreach ($returnData['myData'][$type] as $key => $trigger) {

                if (strpos($trigger, $triggers) !== false) {
                    $filterData['myData'][$type][] = $trigger;
                }
            }
            foreach ($returnData['otherData'][$type] as $key => $trigger) {
                if (strpos($trigger, $triggers) !== false) {
                    $filterData['otherData'][$type][] = $trigger;
                }
            }

            echo json_encode($filterData);
            die;


        }

        echo json_encode($returnData);
        die;
    }

    public function delete_log_function() {
        global $wpdb, $err, $msg;
        $table = $wpdb->prefix . "notebook_logger";
        $results = $wpdb->delete($table, array('id' => $_POST['log_id']));
        if (!$results) {
            echo json_encode(array('status' => 200, 'msg' => 'something went Wrong'));
            die;
        } else {
            echo json_encode(array('status' => 200, 'msg' => 'Notebook deleted successfully'));
            die;
        }
    }

    public function nlRemoveOption_function() {
        global $wpdb, $err, $msg;
        $table = $wpdb->prefix . "notebook_logger_options";
        $results = $wpdb->delete($table, array('id' => $_POST['option_id']));
        if (!$results) {
            echo json_encode(array('status' => 'error', 'msg' => 'something went Wrong'));
            die;
        } else {
            echo json_encode(array('status' => 'success', 'msg' => 'Notebook deleted successfully'));
            die;
        }
    }

    public function is_memberpress_active()
    {
        if(function_exists('is_plugin_active')){
         if ( is_plugin_active( 'memberpress/memberpress.php' ) )
         {
            return true;
         } else {
            return false;
         }
        }else{
            return false;
        }
    }
    public function is_buddyboss_active()
    {
        if(function_exists('is_plugin_active')){
         if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) )
         {
            return true;
         } else {
            return false;
         }
        }else{
            return false;
        }
    }
    public function check_logger_membership()
    {

        $enabled_mepr_rule = get_option("logger_mepr")?true:false;
        if($enabled_mepr_rule){
            $memberships = get_option("logger_mepr_memberships");
            if(!$memberships){
                $memberships = [];
            }
            $membership = implode(',',$memberships);
            if(!current_user_can("mepr-active","membership:$membership")):
                return true;
            else:
                return false;
            endif;
        }else{
            return false;
        }

    }

    public function nlnewoptions_function() {
        global $wpdb,$err,$msg;
        $table_name = $wpdb->prefix . 'notebook_logger_options';

        if ( isset($_POST['type']) ){

            switch ( $_POST['type'] ) {
              case 'triggers':
                $type = 'trigger';
                break;
              case 'emotion':
                $type = 'emotion';
                break;
              case 'cope':
                $type = 'cope';
                break;
            } 

            $wpdb->insert(
                $table_name,
                array(
                    'type' => $type,
                    'value' => sanitize_text_field($_POST['value']),
                    'user_id' => get_current_user_id()
                ),
                array(
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $lastid = $wpdb->insert_id;
            if( $lastid ) {
                echo json_encode( array('status' => 'success', 'id' => $lastid) );
                die;
            }
        }
        echo json_encode(array('status' => 'error', 'msg' => 'something went Wrong'));
        die;
    }

    // public function nlnewoptions_function() {
    //     require_once( NL_INCLUDE_PATH . "lib/user_options.php");
    // }

    public function nlsubmission_function() {
        require_once( NL_INCLUDE_PATH . "lib/submit.php");
    }

    public function nl_assets() {
            wp_register_style('notebook_ui_css', NOTEBOOKLOGGER . 'includes/assets/css/jquery-ui.min.css', array(), time(), 'All');
            wp_enqueue_style('notebook_ui_css');
            wp_register_style('notebook_custom_css', NOTEBOOKLOGGER . 'includes/assets/css/style.css', array(), time(), 'All');
            wp_enqueue_style('notebook_custom_css');
            wp_register_style('notebook_datetime_ios_css', NOTEBOOKLOGGER . 'includes/assets/css/mobiscroll.javascript.min.css', array(), time(), 'All');
            wp_enqueue_style('notebook_datetime_ios_css');
            wp_register_style('swal2_css', NOTEBOOKLOGGER . 'includes/assets/css/sweetalert2.min.css', array(), time(), 'All');
            wp_enqueue_style('swal2_css');
            wp_register_script('nl_bpopup', NOTEBOOKLOGGER . 'includes/assets/js/jquery.bpopup.min.js', array('jquery'), time(), true);
            wp_enqueue_script('nl_bpopup');
            wp_register_script('nl_jquery_ui', NOTEBOOKLOGGER . 'includes/assets/js/jquery-ui.min.js', array('jquery'), time(), true);
            wp_enqueue_script('nl_jquery_ui');
            wp_register_script('notebook_datetime_ios_js', NOTEBOOKLOGGER . 'includes/assets/js/mobiscroll.javascript.min.js', array('jquery'), time(), true);
            wp_enqueue_script('notebook_datetime_ios_js');
            wp_register_script('swal2_js', NOTEBOOKLOGGER . 'includes/assets/js/sweetalert2.min.js', array('jquery'), time(), true);
            wp_enqueue_script('swal2_js');
            wp_register_script('qs_moment', NOTEBOOKLOGGER . 'includes/assets/js/moment.min.js', array('jquery'), time(), false);
            wp_enqueue_script('qs_moment');
            wp_register_script('qs_moment_timezone', NOTEBOOKLOGGER . 'includes/assets/js/moment-timezone-with-data.min.js', array('jquery'), time(), false);
            wp_enqueue_script('qs_moment_timezone');
            wp_register_script('notebook_common_js', NOTEBOOKLOGGER . 'includes/assets/js/common.js', array(), time(), true);
            wp_enqueue_script('notebook_common_js');
            wp_localize_script('notebook_common_js', 'nl_ajax', array('ajaxurl' => admin_url('admin-ajax.php'), 'login_url' => wp_login_url(),'assets'=>NL_ASSETS_URL,'chart_data'=>$this->chart_data()));
            // wp_enqueue_script('notebook_custom_onload_js', NOTEBOOKLOGGER . 'includes/assets/js/onload.js', array('qs_moment_timezone'), time(), false);
            // wp_localize_script('notebook_custom_onload_js', 'nl_ajax', array('ajaxurl' => admin_url('admin-ajax.php'), 'login_url' => wp_login_url(),'assets'=>NL_ASSETS_URL,'chart_data'=>$this->chart_data()));
           
    } 

    public function nl_admin_assets(){
        wp_enqueue_script('notebook_custom_backend_js', NOTEBOOKLOGGER . 'includes/assets/js/backend.js', array('jquery'), time(), true);
        wp_localize_script('notebook_custom_backend_js', 'nl_ajax', array('ajaxurl' => admin_url('admin-ajax.php') ) );
        wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }

    public function chart_data()
    {    global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';
        $sql = sprintf("SELECT `created_date`, count(*) as daily FROM %s WHERE `user_id` = %s GROUP BY created_date ORDER BY `created_date` ASC", $notebook_logger_option_db_table, get_current_user_id());
        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        $data    = [];
        foreach ($Alllogs as $key => $value) {
            $data['lables'][] = $value['created_date'];
            $data['data'][]   = $value['daily'];
        }
        return json_encode($data);
    }
    public function notebook_admin_shortcode($atts = []) {
        if(!current_user_can('administrator')){
            return;
        }
        if($this->check_logger_membership()){
            return;
        }
        $atts = array_change_key_case((array) $atts, CASE_LOWER);
        $wporg_atts = shortcode_atts(
                array(
                    'type' => 'triggers',
                    'limit' => 5,
                ), $atts, $tag
        );

        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';

        $sql = sprintf("SELECT %s as title , round((count(%s) / (SELECT count(*) FROM %s ) * 100 ),0) as percentage , TIME_FORMAT(SEC_TO_TIME(avg(hour(time) * 3600 + (minute(time) * 60) + second(time))),'%%H%%i') as AvgTime FROM %s where %s !='' GROUP BY %s ORDER BY `percentage` DESC limit %s ", $wporg_atts['type'], $wporg_atts['type'], $notebook_logger_option_db_table, $notebook_logger_option_db_table,$wporg_atts['type'], $wporg_atts['type'], $wporg_atts['limit']);
        $filter_type = $wporg_atts['type'];
        $filter_limit = $wporg_atts['limit'];
        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        $wid_html = "<div class='nl-widget-wrap shortcodes-listing'>";
        $wid_html .= "<form method='POST' onsubmit='filter_admin_stat(this); return false'>
                        <div class='search-wrapper'>
                            <div class='search-wrapper-icon'><svg xmlns='http://www.w3.org/2000/svg' id='Capa_1' enable-background='new 0 0 515.558 515.558' height='512px' viewBox='0 0 515.558 515.558' width='512px' class=''><g transform='matrix(0.987429 0 0 0.987429 3.24048 3.24048)'><path d='m378.344 332.78c25.37-34.645 40.545-77.2 40.545-123.333 0-115.484-93.961-209.445-209.445-209.445s-209.444 93.961-209.444 209.445 93.961 209.445 209.445 209.445c46.133 0 88.692-15.177 123.337-40.547l137.212 137.212 45.564-45.564c0-.001-137.214-137.213-137.214-137.213zm-168.899 21.667c-79.958 0-145-65.042-145-145s65.042-145 145-145 145 65.042 145 145-65.043 145-145 145z' data-original='#000000' class='active-path' data-old_color='#000000' fill='#41556B'></path></g> </svg></div>
                            <input type='text' name='email' class='search-input nl_admin_stats_filter' placeholder='Filter By email'>
                        </div>
                        <div class='filter_h_input'></div>
                        <input type='hidden' name='action' value='filter_admin_stats'>
                        <input type='hidden' name='type' value='$filter_type'>
                        <input type='hidden' name='limit' value='$filter_limit'>
                    </form>";
        $wid_html .="<div class='nl-widget-wrap--inner'>";
        foreach ($Alllogs as $key => $log) {
            $time = $log['AvgTime'];

            if (($time >= "0600") && ($time <= "1200")) {
                $timing = "Usually in Morning";
            } elseif (($time >= "1201") && ($time <= "1600")) {
                $timing = "Usually in Afternoon";
            } elseif (($time >= "1601") && ($time <= "2100")) {
                $timing = "Usually in Evening";
            } elseif (($time >= "2101") && ($time <= "2400")) {
                $timing = "Usually in Night";
            } else {
                $timing = "Usually in Late Night";
            }
            $wid_html .= "<div class='nl-summary-wrap-left'>
            <h3 class='cap_text'>" . $log['title'] . "</h3>
            <p>" . $timing . "</p>
          </div><div class='nl-summary-wrap-right'>
                <span class='nl_intensity_widget' style='width:" . $log['percentage'] . "%'></span>
                <span class='nl_text_widget'>" . $log['percentage'] . "%</span>

          </div>";
        }
        $wid_html .= "</div></div>";
        return $wid_html;
    }
    public function filter_admin_stats_function()
    {
        extract($_POST);
        if(!isset($filter)){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message['status']  = '400';
                $message['message'] = 'Invalid email address';
                echo json_encode($message);
                die;
            }
            $user = get_user_by( 'email', $email );
            $message = [];
            if(!$user){
                $message['status']  = '400';
                $message['message'] = 'User not Found';
                echo json_encode($message);
                die;
            }
        }
        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';
        if($filter){
            $sql = sprintf("SELECT %s as title , round((count(%s) / (SELECT count(*) FROM %s ) * 100 ),0) as percentage , TIME_FORMAT(SEC_TO_TIME(avg(hour(time) * 3600 + (minute(time) * 60) + second(time))),'%%H%%i') as AvgTime FROM %s where %s !='' GROUP BY %s ORDER BY `percentage` DESC limit %s ", $type, $type, $notebook_logger_option_db_table, $notebook_logger_option_db_table,$type, $type, $limit);
        }else{
            $sql = sprintf("SELECT %s as title , round((count(%s) / (SELECT count(*) FROM %s ) * 100 ),0) as percentage , TIME_FORMAT(SEC_TO_TIME(avg(hour(time) * 3600 + (minute(time) * 60) + second(time))),'%%H%%i') as AvgTime FROM %s WHERE user_id = %s AND %s !='' GROUP BY %s ORDER BY `percentage` DESC limit %s ", $type, $type, $notebook_logger_option_db_table, $notebook_logger_option_db_table,$user->ID, $type,$type, $limit);
        }

        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        $admin_user_stats = ['email'=>$email,'count'=>count($Alllogs),'filter'=>isset($filter)?true:false];
         foreach ($Alllogs as $key => $log) {
            $time = $log['AvgTime'];

            if (($time >= "0600") && ($time <= "1200")) {
                $timing = "Usually in Morning";
            } elseif (($time >= "1201") && ($time <= "1600")) {
                $timing = "Usually in Afternoon";
            } elseif (($time >= "1601") && ($time <= "2100")) {
                $timing = "Usually in Evening";
            } elseif (($time >= "2101") && ($time <= "2400")) {
                $timing = "Usually in Night";
            } else {
                $timing = "Usually in Late Night";
            }
            $admin_user_stats['stats'][]     = array('timing'=>$timing,'title'=>$log['title'],'percent'=>$log['percentage'].'%');

        }
            $message['status']  = '200';
            $message['message'] = 'success';
            $message['data']    = $admin_user_stats;
            echo json_encode($message);
            die;
    }
    public function get_chart_elements($type)
    {
       global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';
        $sql = sprintf("SELECT * FROM %s WHERE created_on >= DATE_ADD(CURDATE(),INTERVAL -6 DAY)  AND etype = '%s' AND user_id = '%s'", $notebook_logger_option_db_table,  $type ,get_current_user_id());

        $sql = $wpdb->get_results($sql, ARRAY_A);

        $chartData = [];
        if(!empty($sql)){
            $chartdata['cigarettes'] = count($sql);
            foreach ($sql as $key => $value){
                foreach ($value as $key2 => $value2){
                    $index = $key2.'-'.$value2;
                    if (array_key_exists($key2, $chartdata) && array_key_exists($value2, $chartdata[$key2])){
                        $chartdata[$key2][$value2]++;
                    } else {
                        $chartdata[$key2][$value2] = 1;
                    }
                }
            }
        }
        return $chartdata;
    }
    public function get_chart_weeks($weekday , $type)
    {
       global $wpdb, $err, $msg;
       $current =  date("l");
       if($current == ucfirst($weekday)){
            $day = date('Y-m-d');
       }else{
            $day = date('Y-m-d',strtotime("last $weekday"));
       }

        $chart_data = $this->get_chart_elements($type);

        $average = [];
        if($chart_data){
            if(array_key_exists($day, $chart_data['created_date'])){
                $average['total'] = $chart_data['created_date'][$day];
                $average['percent'] = ($chart_data['created_date'][$day]/$chart_data['cigarettes']);
            }
        }
        return $average;
    }
    public function notebook_chart_shortcode($atts = [])
    {
        if($this->check_logger_membership()){
            return;
        }
        $filepath = NL_INCLUDE_PATH . 'frontend/chart.php';
            ob_start();
            require $filepath;
            return ob_get_clean();

    }
    public function notebook_shortcode($atts = []) {
        if($this->check_logger_membership()){
            return;
        }
        $atts = array_change_key_case((array) $atts, CASE_LOWER);
        $wporg_atts = shortcode_atts(
                array(
                    'type' => 'triggers',
                    'limit' => 5,
                ), $atts, $tag
        );

        global $wpdb, $err, $msg;
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';

        $sql = sprintf("SELECT %s as title , round((count(%s) / (SELECT count(*) FROM %s ) * 100 ),0) as percentage , TIME_FORMAT(SEC_TO_TIME(avg(hour(time) * 3600 + (minute(time) * 60) + second(time))),'%%H%%i') as AvgTime FROM %s WHERE user_id = %s AND %s !='' GROUP BY %s ORDER BY `percentage` DESC limit %s ", $wporg_atts['type'], $wporg_atts['type'], $notebook_logger_option_db_table, $notebook_logger_option_db_table,get_current_user_id(), $wporg_atts['type'],$wporg_atts['type'], $wporg_atts['limit']);
        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        $wid_html = "<div class='nl-widget-wrap shortcodes-listing'><div class='nl-widget-wrap--inner'>";
        foreach ($Alllogs as $key => $log) {
            $time = $log['AvgTime'];

            if (($time >= "0600") && ($time <= "1200")) {
                $timing = "Usually in Morning";
            } elseif (($time >= "1201") && ($time <= "1600")) {
                $timing = "Usually in Afternoon";
            } elseif (($time >= "1601") && ($time <= "2100")) {
                $timing = "Usually in Evening";
            } elseif (($time >= "2101") && ($time <= "2400")) {
                $timing = "Usually in Night";
            } else {
                $timing = "Usually in Late Night";
            }
            $wid_html .= "<div class='nl-summary-wrap-left'>
            <h3 class='cap_text'>" . $log['title'] . "</h3>
            <p>" . $timing . "</p>
          </div><div class='nl-summary-wrap-right'>
                <span class='nl_intensity_widget' style='width:" . $log['percentage'] . "%'></span>
                <span class='nl_text_widget'>" . $log['percentage'] . "%</span>

          </div>";
        }
        $wid_html .= "</div></div>";
        return $wid_html;
    }

    public function nl_shortcode_function() {
        if( !empty($_COOKIE['system_timezone']) ) {
            $userCurrentTimezone =  $_COOKIE['system_timezone'];

            date_default_timezone_set($userCurrentTimezone);
        }else{
            $userCurrentTimezone = date_default_timezone_get();
        }

        global $wpdb, $err, $msg;
        if($this->check_logger_membership()){
            return;
        }
        $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';

        $sql = sprintf('SELECT log.*, ( select value FROM %1$s opt WHERE log.triggers = opt.id ) as triggerVal, ( select value FROM %1$s optE WHERE log.emotion = optE.id ) as emotionVal, ( select value FROM %1$s opC WHERE log.cope = opC.id ) as copeVal FROM %2$s log WHERE user_id = %3$s ORDER BY time DESC', $notebook_logger_option_db_table, $notebook_logger_db_table , get_current_user_id());
    
       // $sql = sprintf("SELECT * FROM %s WHERE `user_id` = %s ORDER BY `time` DESC", $notebook_logger_db_table, get_current_user_id());

        $Alllogs = $wpdb->get_results($sql, ARRAY_A);

        //echo "<pre>"; print_r($Alllogs); echo "</pre>"; exit;        

        $sorted_log = [];
        $list = '<div class="nl_log_wrapper"><div class="nl_log_list">';
        foreach ($Alllogs as $key => $log) {

            $dTime = new DateTime( $log['time'], new DateTimeZone('UTC') );
            $dTime->setTimeZone( new DateTimeZone($userCurrentTimezone) );
            $log_time = $dTime->format('Y-m-d');

            $sorted_log[$log_time][] = $log;
        }
        foreach ($sorted_log as $key => $logs) {
            if ($key == date('Y-m-d')) {
                $head = 'Today';
            } else if ($key == date('Y-m-d', strtotime("-1 days"))) {
                $head = 'Yesterday';
            } else {
                $head = $key;
            }
            $list .= '<h3>' . $head . '</h3>';
            foreach ($logs as $nlkey => $nllog) {

                if( !empty($nllog['time_iso']) ) {
                    $dTime = new DateTime( $nllog['time_iso'], new DateTimeZone('UTC') );
                    $dTime->setTimeZone( new DateTimeZone($userCurrentTimezone) );
                    $logTime = $dTime->format('M d, h:i a');
                }else{
                    $logTime = date('M d, h:i a', strtotime($nllog['time']));
                }

                $log_data = json_encode(['id' => $nllog['id'],
                    'triggers'  => $nllog['triggers'],
                    'triggerVal'  => $nllog['triggerVal'],
                    'emotion'   => $nllog['emotion'],
                    'emotionVal'   => $nllog['emotionVal'],
                    'reason'    => stripslashes($nllog['reason']),
                    'intensity' => $nllog['intensity'],
                    'etype'     => $nllog['etype'],
                    'cope'      => $nllog['cope'],
                    'copeVal'      => $nllog['copeVal'],
                    'time'      => $logTime,
                ], JSON_HEX_APOS);
                $listtype = ($nllog['etype'] == 'craving')?'Craving on':'Smoking on';
                $list .= "<div class='nl_log_block nl-block-" . $nllog['id'] . "' data-src='" . $log_data . "'>";
                $list .= "<h4>" .$listtype." "  . $logTime . "</h4>";
                $list .= "<span class='nl-edit-log'><svg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 426.667 426.667' style='enable-background:new 0 0 426.667 426.667; width:21px;' xml:space='preserve'><g><g><circle cx='42.667' cy='213.333' r='42.667'/></g></g><g><g><circle cx='213.333' cy='213.333' r='42.667'/></g></g><g><g><circle cx='384' cy='213.333' r='42.667'/></g></g></svg><ul class='nl-log-menu'><li><a href='javascript:void(0);' class='edit_log_nl' data-id='" . $nllog['id'] . "' data-src='" . $log_data . "' >Edit</a><li></li><a href='javascript:void(0);' class='delete_log_nl' data-id='" . $nllog['id'] . "' >Delete</a></li></ul></span>";
                $list .= "<span class='nl-view-log'><a href='javascript:void(0);' class='view_log_nl' data-id='" . $nllog['id'] . "' data-src='" . $log_data . "'><svg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 384.97 384.97' style='enable-background:new 0 0 384.97 384.97; width:30px;' xml:space='preserve'><g><g id='Chevron_Right_Circle'><path d='M192.485,0C86.173,0,0,86.173,0,192.485c0,106.3,86.173,192.485,192.485,192.485c106.3,0,192.485-86.185,192.485-192.485C384.97,86.173,298.785,0,192.485,0z M192.485,360.909c-92.874,0-168.424-75.55-168.424-168.424S99.611,23.688,192.485,23.688s168.424,75.923,168.424,168.797S285.359,360.909,192.485,360.909z'/><path d='M166.114,99.503c-4.704-4.74-12.319-4.74-17.011,0c-4.704,4.752-4.704,12.439,0,17.191l74.528,75.61l-74.54,75.61c-4.704,4.74-4.704,12.439,0,17.191c4.704,4.74,12.319,4.74,17.011,0l83.009-84.2c4.572-4.632,4.584-12.56,0-17.191L166.114,99.503z'/></g></svg></a></span>";
                $list .= '<ul class="smoknote-list">';
                if (strlen(stripslashes($nllog['reason'])) > 15)
                {
                    $reason =  substr(stripslashes($nllog['reason']), 0, 15)."<a href='javascript:void(0);' class='view_log_nl' data-id='" . $nllog['id'] . "' data-src='" . $log_data . "'> ...</a>";
                }
                else
                {
                    $reason = stripslashes($nllog['reason']);
                }
                $list .= '<li class="smoknote-list-item">
                        <div class="smoknote-left">
                            <div class="smoknote-icon">
                                <img src="'.NL_ASSETS_URL.'img/notebook-trigger-icon.png" class="nl_png_icons">
                            </div>
                            <div class="smoknote-text">
                                <span class="smoknote-heading">Triggers</span>
                                <span class="smoknote-subheading cap_text">' . $nllog['triggerVal'] . '</span>
                            </div>
                            </div>
                            <div class="smoknote-right">
                                 <div class="smoknote-icon">
                                   <img src="'.NL_ASSETS_URL.'img/notebook-emotion-icon.png" class="nl_png_icons">
                                </div>
                                <div class="smoknote-text">
                                    <span class="smoknote-heading">Craving Thought</span>
                                    <span class="smoknote-subheading cap_text">' . $nllog['emotionVal'] . '</span>
                                </div>
                            </div>
                        </li>';
                    $smoke_list = '<li class="smoknote-list-item">
                        <div class="smoknote-left">
                            <div class="smoknote-icon">
                                <img src="'.NL_ASSETS_URL.'img/notebook-reason-icon.png" class="nl_png_icons">
                            </div>
                            <div class="smoknote-text">
                                <span class="smoknote-heading">Reason</span>
                                <span class="smoknote-subheading cap_text">' . $reason . '</span>
                            </div>
                            </div>
                            <div class="smoknote-right">
                                 <div class="smoknote-icon">
                                    <img src="'.NL_ASSETS_URL.'img/notebook-intensity-icon.png" class="nl_png_icons">
                                </div>
                                <div class="smoknote-text">
                                    <span class="smoknote-heading">Craving Intensity</span>
                                    <span class="smoknote-subheading cap_text">' . $nllog['intensity'] . '</span>
                                </div>
                            </div>
                        </li>';
                        $crav_list = '<li class="smoknote-list-item">
                        <div class="smoknote-left">
                            <div class="smoknote-icon">
                                <img src="'.NL_ASSETS_URL.'img/notebook-coping-icon.png" class="nl_png_icons">
                            </div>
                            <div class="smoknote-text">
                                <span class="smoknote-heading">How did you cope?</span>
                                <span class="smoknote-subheading cap_text">' . $nllog['copeVal'] . '</span>
                            </div>
                            </div>
                            <div class="smoknote-right">
                                 <div class="smoknote-icon">
                                    <img src="'.NL_ASSETS_URL.'img/notebook-coping-icon.png" class="nl_png_icons">
                                </div>
                                <div class="smoknote-text">
                                    <span class="smoknote-heading">N/A</span>
                                    <span class="smoknote-subheading">N/A</span>
                                </div>
                            </div>
                        </li>';
                $list .= ($nllog['etype'] == 'craving')?$crav_list:$smoke_list;
                $list .= '</ul">';
                $list .= "</div>";
            }
        }
        $list .= '</div><div class="nl-single-log" style="display:none;">';
        $list .= '<ul class="smoknote-list">';
        $list .= '<a href="javascript:void(0);" class="back-nl-list">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>
                </a>';
        $list .= "<h4 class='nl-single-date'></h4>";
        $list .= '<li class="smoknote-list-item">
                <div class="smoknote-left">
                    <div class="smoknote-icon">
                        <img src="'.NL_ASSETS_URL.'img/notebook-trigger-icon.png" class="nl_png_icons">
                    </div>
                    <div class="smoknote-text">
                        <span class="smoknote-heading">Triggers</span>
                        <span class="smoknote-subheading nl-single-trigger cap_text"></span>
                    </div>
                    </div>
                    <div class="smoknote-right">
                         <div class="smoknote-icon">
                            <img src="'.NL_ASSETS_URL.'img/notebook-emotion-icon.png" class="nl_png_icons">
                        </div>
                        <div class="smoknote-text">
                            <span class="smoknote-heading">Craving Thought</span>
                            <span class="smoknote-subheading nl-single-emotion cap_text"></span>
                        </div>
                    </div>
                </li>
                <li class="smoknote-list-item smoke_view">
                <div class="smoknote-left">
                    <div class="smoknote-icon">
                        <img src="'.NL_ASSETS_URL.'img/notebook-reason-icon.png" class="nl_png_icons">
                    </div>
                    <div class="smoknote-text">
                        <span class="smoknote-heading">Reason</span>
                        <span class="smoknote-subheading nl-single-reason cap_text"></span>
                    </div>
                    </div>
                    <div class="smoknote-right">
                         <div class="smoknote-icon">
                            <img src="'.NL_ASSETS_URL.'img/notebook-intensity-icon.png" class="nl_png_icons">
                        </div>
                        <div class="smoknote-text">
                            <span class="smoknote-heading">Carving Intensity</span>
                            <span class="smoknote-subheading nl-single-intensity cap_text"></span>
                        </div>
                    </div>
                </li>
                <li class="smoknote-list-item crav_view" style="display:none;">
                <div class="smoknote-left">
                    <div class="smoknote-icon">
                        <img src="'.NL_ASSETS_URL.'img/notebook-coping-icon.png" class="nl_png_icons">
                    </div>
                    <div class="smoknote-text">
                        <span class="smoknote-heading">How did you cope?</span>
                        <span class="smoknote-subheading nl-single-cope cap_text"></span>
                    </div>
                    </div>
                    <div class="smoknote-right">
                         <div class="smoknote-icon">
                            <img src="'.NL_ASSETS_URL.'img/notebook-coping-icon.png" class="nl_png_icons">
                        </div>
                        <div class="smoknote-text">
                            <span class="smoknote-heading">N/A</span>
                            <span class="smoknote-subheading">N/A</span>
                        </div>
                    </div>
                </li>';
        $list .= '</ul">';
        $list .= '</div></div>';


        return $list;
    }

    public function nl_logger_body_class($classes) {
        $classes[] = is_user_logged_in() ? 'logged_in' : 'need_login';
        return $classes;
    }
    public function profile_tab_nllogger() {
      global $bp;
      if($this->check_logger_membership()){
            return;
        }
      bp_core_new_nav_item( array(
            'name' => 'Smoking Notebook',
            'slug' => 'nl-logger',
            'screen_function' => array($this, 'nl_logger_screen'),
            //'position' => 40,
            'parent_url'      => bp_loggedin_user_domain() . '/nl-logger/',
            'parent_slug'     => $bp->profile->slug,
            'default_subnav_slug' => 'nl-logger'
      ) );

    }

    public function nl_logger_screen() {
        add_action( 'bp_template_title', array($this,'nl_logger_title') );
        add_action( 'bp_template_content', array($this,'nl_logger_content') );
        bp_core_load_template( 'buddypress/members/single/plugins' );
    }
    public function nl_logger_title() {
        echo 'NL Logger';
    }

    public function nl_logger_content() {
        echo do_shortcode('[Notebooklogger]');
    }

    public function plural($amount)
    {
        return ($amount == 1)?'':'s';
    }


}

$nl_logger = new Notebook_logger();

