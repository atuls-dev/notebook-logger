<?php

function enable_logger_input() {
    ?>
    <input name="enable_logger" type="checkbox" value="1" <?php echo get_option("enable_logger") ? 'checked' : ''; ?>>

    <?php
}

function delete_logger_data() {
    ?>
    <input name="delete_logger" type="checkbox" value="1" <?php echo get_option("delete_logger") ? 'checked' : ''; ?>>
    <?php
}
function logger_mepr_data() {
    ?>
    <input name="logger_mepr" type="checkbox" value="1" <?php echo get_option("logger_mepr") ? 'checked' : ''; ?>>
    <?php
}
function logger_carving_form() {
    ?>
    <input name="logger_carving_form" type="checkbox" value="1" <?php echo get_option("logger_carving_form") ? 'checked' : ''; ?>>
    <?php
}
function enable_view_all() {
    ?>
    <input name="enable_view_all" type="checkbox" value="1" <?php echo get_option("enable_view_all") ? 'checked' : ''; ?>>
    <?php
}
function view_all_page() {
    global $nl_logger;
    $args = array(
                    'post_type' => 'page',
                    'posts_per_page' => -1
        );
    $query = get_posts( $args );
    ?>
        <select name="view_all_page">
            <option value="">Select Page</option>
            <?php if($nl_logger->is_buddyboss_active()){ ?>
            <option value="profile_page" <?php echo ('profile_page' ==  $view_all) ? 'selected' : ''; ?>>Profile Page Tab</option>
    <?php
            }
    if (!empty($query) ):
        $view_all = get_option("view_all_page");

        foreach ($query as $key => $rest):

    ?>
        <option value="<?php echo $rest->ID; ?>" <?php echo ($rest->ID ==  $view_all) ? 'selected' : ''; ?>><?php echo $rest->post_title; ?></option>
    <?php
        endforeach;
    endif;
    ?>
    <?php
}
function view_all_custom_link() {
    ?>
    <input name="view_all_custom_link" type="text" value="<?php echo get_option("view_all_custom_link"); ?>">
    <?php
}

function nl_buddypress_tab() {
    ?>
    <input name="nl_buddypress_tab" type="checkbox" value="1" <?php echo get_option("nl_buddypress_tab") ? 'checked' : ''; ?>>
    <?php
}
function logger_mepr_memberships() {
    $args = array(
                    'post_type' => 'memberpressproduct',
                    'posts_per_page' => -1
        );
    $query = get_posts( $args );
    ?>
        <select name="logger_mepr_memberships[]" multiple>
            <option value="">Select membership</option>
    <?php
    if (!empty($query) ):
        $memberships = get_option("logger_mepr_memberships");
        if(!$memberships){
            $memberships = [];
        }
        foreach ($query as $key => $rest):

    ?>
        <option value="<?php echo $rest->ID; ?>" <?php echo in_array($rest->ID,$memberships)  ? 'selected' : ''; ?>><?php echo $rest->post_title; ?></option>
    <?php
        endforeach;
    endif;
    ?>
    </select>
    <?php
}
function display_nl_panel_fields() {
    $logger = new Notebook_logger();

        add_settings_section("nl-settings-group", "Notebook Logger Section", null, "nl-plugin-options");
        add_settings_field("enable_logger", "Enable Notebook Logger", "enable_logger_input", "nl-plugin-options", "nl-settings-group");
        add_settings_field("delete_logger", "Delete Notebook data on deactivation", "delete_logger_data", "nl-plugin-options", "nl-settings-group");
        add_settings_field("logger_carving_form", "Enable carving form", "logger_carving_form", "nl-plugin-options", "nl-settings-group");
        add_settings_field("enable_view_all", "Enable View all", "enable_view_all", "nl-plugin-options", "nl-settings-group");
        add_settings_field("view_all_page", "Select page", "view_all_page", "nl-plugin-options", "nl-settings-group");
        add_settings_field("view_all_custom_link", "View all custom link", "view_all_custom_link", "nl-plugin-options", "nl-settings-group");

        register_setting("nl-options", "enable_logger");
        register_setting("nl-options", "delete_logger");
        register_setting("nl-options", "logger_carving_form");
        register_setting("nl-options", "enable_view_all");
        register_setting("nl-options", "view_all_page");
        register_setting("nl-options", "view_all_custom_link");


    if($logger->is_memberpress_active())
    {
        add_settings_section("nl-mepr-settings-group", "Notebook Memberpress Section", null, "nl-plugin-options");
        add_settings_field("logger_mepr", "Enable Memberpress Rules", "logger_mepr_data", "nl-plugin-options", "nl-mepr-settings-group");
        add_settings_field("logger_mepr_memberships", "Select membership", "logger_mepr_memberships", "nl-plugin-options", "nl-mepr-settings-group");
        register_setting("nl-options", "logger_mepr");
        register_setting("nl-options", "logger_mepr_memberships");
    }
    if($logger->is_buddyboss_active()){
        add_settings_section("nl-bp-settings-group", "Notebook Buddypress Section", null, "nl-plugin-options");
        add_settings_field("nl_buddypress_tab", "Enable Buddypress profile tab membership", "nl_buddypress_tab", "nl-plugin-options", "nl-bp-settings-group");
        register_setting("nl-options", "nl_buddypress_tab");
    }



}

add_action("admin_init", "display_nl_panel_fields");

function global_logger_sticky() {
    global $wp_post_types;
	$posttypes = array_keys( $wp_post_types );
	// Remove _builtins or others
	$pt_remove = array("attachment","nav_menu_item","customize_changeset","revision","custom_css");

	foreach ( $posttypes as $posttype ):
	 if ( in_array($posttype, $pt_remove) ) continue;
	 $posttype_names[] = $posttype;
	endforeach;

    foreach ($posttype_names as $screen) {
        add_meta_box(
                'global-sticky',
                __('Logger Floating Button', NOTEBOOKLOGGER_TEXTDOMAIN),
                'global_stickybar_callback',
                $screen
        );
    }
}

add_action('add_meta_boxes', 'global_logger_sticky');

function global_stickybar_callback($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
    <div class="floating_meta_inputs ">
        <label for="nl_floating_btn">Enable:  </label>
        <input name="nl_floating_btn" type="checkbox" value="1" <?php echo get_post_meta($object->ID, "nl_floating_btn", true) ? 'checked' : ''; ?> style="margin-top: 2px;">
    </div>
    <?php
}

add_action("save_post", "save_floating_btn", 10, 3);

function save_floating_btn($post_id, $post, $update) {


    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = array('page', 'post');
    if (!in_array($post->post_type, $slug))
        return $post_id;

    $nl_floating_btn = "";
    if (isset($_POST["nl_floating_btn"])) {
        $nl_floating_btn = $_POST["nl_floating_btn"];
    }
    update_post_meta($post_id, "nl_floating_btn", $nl_floating_btn);
}
