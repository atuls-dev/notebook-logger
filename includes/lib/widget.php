<?php

class Nl_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
                'nl_widget', // Base ID
                'Notebook Logger', // Name
                array('description' => __('Add Notebook Triggers, Emotions to your Sidebar.')) // Args
        );
    }

    public function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;
        echo $this->nl_logger_type($instance);
        echo $after_widget;
    }

    public function update($new_instance, $old_instance) {

        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['nl_type'] = strip_tags($new_instance['nl_type']);
        $instance['nl_limit'] = strip_tags($new_instance['nl_limit']);
        return $instance;
    }

    public function form($instance) {

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Top Triggers');
        }
        // Widget admin form
        $instance['nl_type'] = isset($instance['nl_type']) ? $instance['nl_type'] : 'triggers';
        $instance['nl_limit'] = isset($instance['nl_limit']) ? $instance['nl_limit'] : '10';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nl_type'); ?>"><?php _e('Logger Type:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('nl_type'); ?>" name="<?php echo $this->get_field_name('nl_type'); ?>">
                <option <?php selected($instance['nl_type'], 'triggers'); ?> value="triggers">Triggers</option>
                <option <?php selected($instance['nl_type'], 'emotion'); ?> value="emotion">Emotion</option>
                <option <?php selected($instance['nl_type'], 'intensity'); ?> value="intensity">Carving Intensity</option>
                <option <?php selected($instance['nl_type'], 'cope'); ?> value="intensity">Cope</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nl_limit'); ?>"><?php _e('Limit:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('nl_limit'); ?>" name="<?php echo $this->get_field_name('nl_limit'); ?>" type="text" value="<?php echo esc_attr($instance['nl_limit']); ?>" />
        </p>
        <?php
    }

    public function nl_logger_type($instance) {
        global $wpdb, $err, $msg;
        if(Notebook_logger::check_logger_membership()){
                return;
        }
        $notebook_logger_option_db_table = $wpdb->prefix . 'notebook_logger';

        $sql = sprintf("SELECT %s as title , round((count(%s) / (SELECT count(*) FROM %s ) * 100 ),0) as percentage , TIME_FORMAT(SEC_TO_TIME(avg(hour(time) * 3600 + (minute(time) * 60) + second(time))),'%%H%%i') as AvgTime FROM %s GROUP BY %s ORDER BY `percentage` DESC limit %s ", $instance['nl_type'], $instance['nl_type'], $notebook_logger_option_db_table, $notebook_logger_option_db_table, $instance['nl_type'], $instance['nl_limit']);

        $Alllogs = $wpdb->get_results($sql, ARRAY_A);
        $wid_html = "<div class='nl-widget-wrap'><div class='nl-widget-wrap--inner'>";
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

}

if (version_compare(PHP_VERSION, '5.6.0') >= 0) {
    add_action('widgets_init', function () {
        register_widget("Nl_Widget");
    });
} else {
    add_action('widgets_init', create_function('', 'register_widget( "Nl_Widget" );'));
}
?>