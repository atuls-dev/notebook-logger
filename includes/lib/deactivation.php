<?php


    global $wpdb;
    if(get_option("delete_logger")){
	    $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
		$notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
	    $wpdb->query( "DROP TABLE IF EXISTS $notebook_logger_db_table" );
	    $wpdb->query( "DROP TABLE IF EXISTS $notebook_logger_option_db_table" );
	    delete_option("enable_logger");
	    delete_option("delete_logger");
	    delete_option("logger_mepr");
	    delete_option("logger_carving_form");
        delete_option("logger_mepr_memberships");
        delete_option("nl_buddypress_tab");
	}
