<?php
//require  NL_INCLUDE_PATH . 'lib/widget.php';
//require  NL_INCLUDE_PATH . 'api/endpoint.php';
$include_files = array(
	NL_INCLUDE_PATH . 'lib/widget.php',
	NL_INCLUDE_PATH . 'api/endpoint.php',
	NL_INCLUDE_PATH . 'admin/entry.php',
);
foreach( $include_files  as $include_file ) {
	require_once($include_file);
}


