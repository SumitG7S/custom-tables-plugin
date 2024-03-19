<?php
// custom-data-list-table.php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// Custom Data List Table Class
class Custom_Data_List_Table extends WP_List_Table {
    // Constructor
    public function __construct() {
        parent::__construct(array(
            'singular' => 'Custom Data',
            'plural' => 'Custom Data',
            'ajax' => false
        ));
    }

    // Define columns
    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'location' => 'Location',
            'address' => 'Address',
            'status' => 'Status'
        );
    }

    // Prepare items
    public function prepare_items() {
        // Fetch data from custom table or any data source
        $data = $this->get_data();

        // Define column headers
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();

        // Process bulk actions if any
        $this->process_bulk_action();

        // Set pagination
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($data);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));

        // Slice data based on pagination
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        // Set the data for the table
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    // Define the default column rendering
    public function column_default($item, $column_name) {
        return isset($item[$column_name]) ? $item[$column_name] : '';
    }

    // Define the name column rendering
    public function column_name($item) {
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&customer=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&customer=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
        );

        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
    }

    // Get data from custom table
    private function get_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_data';
        $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        return $data;
    }

    // Process bulk actions
    protected function process_bulk_action() {
        // Handle bulk actions if any
    }
}
