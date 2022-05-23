<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}
/**
 * Create a new table class that will extend the WP_List_Table
 */
class Nl_entries_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
      function __construct()
    {
        global $status, $page;

         parent::__construct(array(
            'singular'  => 'nl-entry',
            'plural'    => 'nl-entries',
            'ajax'      => true 
        ));
    }

    /**
        * [REQUIRED] this is a default column renderer
        *
        * @param $item - row (key, value array)
        * @param $column_name - string (key)
        * @return HTML
        */
    function column_default($item, $column_name)
    {
    	switch ( $column_name ) {
			
				  	
            case 'time':
            	
                $item[ $column_name ];
                   
            case 'triggers':
            	$item[ $column_name ];
            case 'ID':
            case 'type':
                return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
            case 'etype':
                return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
            case 'emotion':
                return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
            case 'reason':
                return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
            case 'cope':
                return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
			case 'intensity':
				return $item[ $column_name ] ? $item[ $column_name ] : 'N/A';
			default:
				return print_r( $item, true ); 
		}
    }

    

    /**
        * [OPTIONAL] this is example, how to render column with actions,
        * when you hover row "Edit | Delete" links showed
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
   function column_ID($item) {
     
      $base_url   = admin_url( 'admin.php?page=nl_entries_page&amp;id=' . $item['id'] );
      $spam_nonce = esc_html( '_wpnonce=' . wp_create_nonce( 'spam-activity_' . $item['id'] ) );
      $delete = $base_url . "&amp;action=delete&amp;$spam_nonce";
      $actions = array(
                'delete'    => sprintf('<a href="%s">Delete</a>',$delete),
            );
      $cus_order = "<a href='mailto:".$item['user_email']."' class='row-title'><strong>".$item['user_email']."</strong></a> ";
      return sprintf('%1$s %2$s', $cus_order, $this->row_actions($actions) );
    }


    /**
        * [REQUIRED] this is how checkbox column renders
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
    public function ajax_user_can()
    {
        return current_user_can('edit_posts');
    }
    /**
        * [REQUIRED] This method return columns to display in table
        * you can skip columns that you do not want to show
        * like content, or description
        *
        * @return array
        */
    function get_columns()
    {
        $columns = array(
            //Render a checkbox instead of text
            'cb' => '<input type="checkbox" />',
            'ID' => __('User email', NOTEBOOKLOGGER_TEXTDOMAIN),
            'time' => __('Date', NOTEBOOKLOGGER_TEXTDOMAIN),
            'etype' => __('Type', NOTEBOOKLOGGER_TEXTDOMAIN),
            'triggers' => __('Trigger', NOTEBOOKLOGGER_TEXTDOMAIN),
            'emotion'=>__('Emotion', NOTEBOOKLOGGER_TEXTDOMAIN),
            'cope' => __('Cope', NOTEBOOKLOGGER_TEXTDOMAIN),
            'reason'=>__('Reason', NOTEBOOKLOGGER_TEXTDOMAIN),
            'intensity'=>__('Intensity', NOTEBOOKLOGGER_TEXTDOMAIN),
            
        );
        return $columns;
    }

    /**
        * [OPTIONAL] This method return columns that may be used to sort table
        * all strings in array - is column names
        * notice that true on name column means that its default sort
        *
        * @return array
        */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'ID' => array('ID', true),
            'time' => array('time', true),
            'triggers' => array('triggers', true),
            'etype' => array('etype', true),
            'emotion' => array('emotion', true),
            'cope' => array('cope', true),
            'reason' => array('reason', true),
            'intensity' => array('intensity', true),
        );
        return $sortable_columns;
    }

    /**
        * [OPTIONAL] Return array of bult actions if has any
        *
        * @return array
        */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }
    /**
        * [OPTIONAL] This method processes bulk actions
        * it can be outside of class
        * it can not use wp_redirect coz there is output already
        * in this example we are processing delete action
        * message about successful deletion will be shown on page in next part
        */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'notebook_logger'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
        if(! empty( $_GET['_wp_http_referer'] ) ) {
            wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
            exit;
        } 
    }

    /**
        * [REQUIRED] This is the most important method
        *
        * It will get rows from database and prepare them to be showed in table
        */
   	public static function get_nl_entries( $per_page = 20, $page_number = 1 ) {

		global $wpdb;
        $notebook_logger_db_table = $wpdb->prefix . 'notebook_logger';
		$sql = "SELECT  * FROM $notebook_logger_db_table INNER JOIN {$wpdb->prefix}users ON ( {$wpdb->prefix}notebook_logger.user_id = {$wpdb->prefix}users.id )";
        $where  = self::get_items_query_where();
        $sql .= $where;
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

        $sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . $page_number;
        
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'notebook_logger'; // do not forget about tables prefix
        $this->_column_headers = $this->get_column_info();
        $per_page = 20; // constant, how much records will be shown per page
        $customvar = ( isset($_REQUEST['order_satus']) ? $_REQUEST['order_satus'] : 'pending');
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        //$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        $this->items = self::get_nl_entries( $per_page, $paged );

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
    /**
     * Display a message on screen when no items are found (e.g. no search matches).
     *
     * @since BuddyPress 1.6.0
     */
    function no_items() {
        _e( 'No Logger found.', NOTEBOOKLOGGER_TEXTDOMAIN );
    }
    /**
     * Get prepared WHERE clause for items query
     *
     * @global wpdb $wpdb
     *
     * @return string Prepared WHERE clause for items query.
     */
    protected function get_items_query_where() {
        global $wpdb;

        $where_conditions = array();
        $where_values     = array();
        if ( ! empty( $_REQUEST['triggers'] ) ) {
            $where_conditions[] = 'triggers = "'.esc_attr($_REQUEST['triggers']).'"'; 
            $where_values[]     = esc_attr($_REQUEST['triggers']) ;
        }
        if ( ! empty( $_REQUEST['etype'] ) ) {
            $where_conditions[] = 'etype = "'.esc_attr($_REQUEST['etype']).'"'; 
            $where_values[]     = esc_attr($_REQUEST['etype']) ;
        }
        if ( ! empty( $_REQUEST['cope'] ) ) {
            $where_conditions[] = 'cope = "'.esc_attr($_REQUEST['cope']).'"'; 
            $where_values[]     = esc_attr($_REQUEST['cope']) ;
        }
        if ( ! empty( $_REQUEST['emotion'] ) ) {
            $where_conditions[] = 'emotion = "'.esc_attr($_REQUEST['emotion']).'"'; 
            $where_values[]     = esc_attr($_REQUEST['emotion']) ;
        }

        if ( ! empty( $_REQUEST['intensity'] ) ) {
            $where_conditions[] = 'intensity = "'.esc_attr($_REQUEST['intensity']).'"'; 
        }

        if ( ! empty( $_REQUEST['user_email'] ) ) {
            $where_conditions[] = 'user_email = "'.esc_attr($_REQUEST['user_email']).'"'; 
        }

        if ( ! empty( $_REQUEST['nlDateFrom'] ) && ! empty( $_REQUEST['nlDateFrom'] ) ) {
            $where_conditions[] = 'created_date BETWEEN "'.esc_attr($_REQUEST['nlDateFrom']).'" AND "'.esc_attr($_REQUEST['nlDateTo']).'"'; 
        }

        if ( ! empty( $_REQUEST['s'] ) ) {
            $where_conditions[] = 'triggers LIKE "%'.esc_attr($_REQUEST['s']).'%"';
            $where_conditions[] = 'etype LIKE "%'.esc_attr($_REQUEST['s']).'%"';
            $where_conditions[] = 'cope LIKE "%'.esc_attr($_REQUEST['s']).'%"'; 
            $where_conditions[] = 'emotion LIKE "%'.esc_attr($_REQUEST['s']).'%"'; 
            $where_conditions[] = 'intensity LIKE "%'.esc_attr($_REQUEST['s']).'%"'; 
            $where_conditions[] = 'user_email LIKE "%'.esc_attr($_REQUEST['s']).'%"';
            return 'WHERE 1 = 1 AND ' . implode( ' OR ', $where_conditions );  
        }
        
        if ( ! empty( $where_conditions ) ) {
             return 'WHERE 1 = 1 AND ' . implode( ' AND ', $where_conditions );
        } else {
             return '';
        }
    }
    
     function extra_tablenav( $which )
    {
        if ( 'top' === $which ) {
            echo '<div class="alignleft actions">';
               // $this->level_dropdown();
                $this->source_dropdown('etype');
                $this->source_dropdown('triggers');
                $this->source_dropdown('emotion');
                $this->source_dropdown('intensity');
                $this->source_dropdown('cope');
                $this->source_text('user_email');
                $this->source_date();
                submit_button( __( 'Filter', NOTEBOOKLOGGER_TEXTDOMAIN ), '', 'filter-action', false );
            echo '</div>';
        }
         
    }
    /**
     * Display source Date
     *
     * @global wpdb $wpdb
     */
    protected function source_text($targets) {
        $email = ( isset( $_REQUEST[$targets] ) && $_REQUEST[$targets] ) ? $_REQUEST[$targets] : '';
        ?>
        <input type="text" name="<?php echo $targets; ?>" placeholder="User Email" value="<?php echo esc_attr( $email ); ?>" />
        <?php

    }
    protected function source_date() {
        $from = ( isset( $_REQUEST['nlDateFrom'] ) && $_REQUEST['nlDateFrom'] ) ? $_REQUEST['nlDateFrom'] : '';
        $to = ( isset( $_REQUEST['nlDateTo'] ) && $_REQUEST['nlDateTo'] ) ? $_REQUEST['nlDateTo'] : '';
       ?>
        <input type="text" name="nlDateFrom" placeholder="Date From" value="<?php echo esc_attr( $from ); ?>" />
        <input type="text" name="nlDateTo" placeholder="Date To" value="<?php echo esc_attr( $to ); ?>" />
 
        <script>
        jQuery( function($) {
            var from = $('input[name="nlDateFrom"]'),
                to = $('input[name="nlDateTo"]');
 
            $( 'input[name="nlDateFrom"], input[name="nlDateTo"]' ).datepicker( {dateFormat : "yy-mm-dd"} );
            
                from.on( 'change', function() {
                to.datepicker( 'option', 'minDate', from.val() );
            });
 
            to.on( 'change', function() {
                from.datepicker( 'option', 'maxDate', to.val() );
            });
 
        });
        </script>
        <?php
    }
    /**
     * Display source dropdown
     *
     * @global wpdb $wpdb
     */
    protected function source_dropdown($targets) {
        global $wpdb;
        $list = array('user_email'  => 'Emails',
                      'triggers'    => 'Triggers',
                      'emotion'     => 'Emotions',
                      'etype'       => 'Types',
                      'cope'        => 'Copes',
                      'intensity'   => 'Intensity');
        $sources_db = $wpdb->get_col( "
            SELECT DISTINCT $targets
            FROM {$wpdb->prefix}notebook_logger INNER JOIN {$wpdb->prefix}users ON ( {$wpdb->prefix}notebook_logger.user_id = {$wpdb->prefix}users.id )
            WHERE $targets != ''
            ORDER BY $targets ASC
        " );

        if ( ! empty( $sources_db ) ) {

            $sources = array();

            foreach ( $sources_db as $source )  {

                $source = maybe_unserialize( $source );

                if ( is_array( $source ) ) {
                    $sources = array_merge( $sources, $source );
                } else {
                    $sources[] = $source;
                }

            }

            $sources = array_unique( $sources );

            sort( $sources );

            $selected_source = isset( $_REQUEST[$targets] ) ? esc_attr( $_REQUEST[$targets] ) : '';
            ?>
                <label for="filter-by-<?php echo $targets; ?>" class="screen-reader-text"><?php _e( 'Filter by '.$list[$targets], NOTEBOOKLOGGER_TEXTDOMAIN ); ?></label>
                <select name="<?php echo $targets; ?>" id="filter-by-<?php echo $targets; ?>">
                    <option<?php selected( $selected_source, '' ); ?> value=""><?php _e( 'All '.$list[$targets], NOTEBOOKLOGGER_TEXTDOMAIN ); ?></option>
                    <?php foreach ( $sources as $s ) {
                        printf( '<option%1$s value="%2$s">%3$s</option>',
                            selected( $selected_source, $s, false ),
                            esc_attr( $s ),
                            esc_html( $s )
                        );
                    } ?>
                </select>
            <?php
        }
    }
}