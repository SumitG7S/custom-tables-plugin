<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Data List Table Class
 */
class Custom_Data_List_Table extends WP_List_Table {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(array(
            'singular' => 'custom_data',
            'plural'   => 'custom_data',
            'ajax'     => false
        ));
    }

    /**
     * Method to prepare items for display.
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $this->get_custom_data();
    }

    /**
     * Method to define columns.
     */
    public function get_columns() {
        return array(
            'id'       => __('ID'),
            'name'     => __('Name'),
            'email'    => __('Email'),
            'phone'    => __('Phone'),
            'location' => __('Location'),
            'address'  => __('Address'),
            'status'   => __('Status')
        );
    }

    /**
     * Method to fetch custom data from the database.
     */
    public function get_custom_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_data';
        $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        return $data;
    }

    /**
     * Method to display each row.
     */
    public function display_row($item) {
        echo '<tr>';
        foreach ($this->get_columns() as $column_name => $column_display_name) {
            echo '<td>' . $item[$column_name] . '</td>';
        }
        echo '</tr>';
    }
}
