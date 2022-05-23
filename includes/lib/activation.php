<?php
global $wpdb;

if ( ! function_exists( 'dbDelta' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}
$charset_collate = '';
if ( ! empty( $wpdb->charset ) ) {
	$charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";
}
if ( ! empty( $wpdb->collate ) ) {
	$charset_collate .= " COLLATE $wpdb->collate";
}
$notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
$notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger_options';
if($wpdb->get_var( "show tables like '$notebook_logger_db_table'" ) != $notebook_logger_db_table)
    {
		$notebook_logger_create_query = "CREATE TABLE " . $notebook_logger_db_table . " (
						`id` int(11) unsigned NOT NULL auto_increment,
						`user_id` int(11) unsigned NOT NULL,
						`etype` enum('smoking','craving') NOT NULL DEFAULT 'smoking',
					  	`triggers` varchar(255) NOT NULL,
					  	`emotion` varchar(255) NOT NULL,
					  	`cope` varchar(255) NOT NULL,
					  	`reason` text NOT NULL,
					  	`intensity` TINYINT NOT NULL,
					  	`time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
					  	`time_iso` VARCHAR(50) NULL,
					  	`created_date` date NOT NULL,
					  	`updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  	`created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  	PRIMARY KEY  (id)
						) " . $charset_collate . ";";
}
dbDelta( $notebook_logger_create_query );
if($wpdb->get_var( "show tables like '$notebook_logger_option_db_table'" ) != $notebook_logger_option_db_table)
    {
		$notebook_logger_create_option_query = "CREATE TABLE " . $notebook_logger_option_db_table . " (
						 `id` INT NOT NULL AUTO_INCREMENT ,
						 `user_id` INT NULL ,
						 `type` VARCHAR(255) NOT NULL ,
						 `value` VARCHAR(255) NOT NULL ,
						 `temp_id` VARCHAR(40) NULL,
						  PRIMARY KEY  (`id`)
						) " . $charset_collate . ";";
}
dbDelta( $notebook_logger_create_option_query );
$wpdb->query( "ALTER TABLE " . $notebook_logger_db_table . " MODIFY COLUMN id int(11) unsigned NOT NULL AUTO_INCREMENT" );
$wpdb->query( "ALTER TABLE " . $notebook_logger_option_db_table . " MODIFY COLUMN id int(11) unsigned NOT NULL AUTO_INCREMENT" );