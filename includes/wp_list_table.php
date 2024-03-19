<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Custom WP_List_Table Class
class Custom_WP_List_Table extends WP_List_Table {
    // Constructor
    public function __construct() {
        parent::__construct( array(
            'singular' => 'data',
            'plural'   => 'data',
            'ajax'     => false,
        ) );
    }

    // Define columns
    public function get_columns() {
        return array(
            'cb'        => '<input type="checkbox" />',
            'name'      => 'Name',
            'email'     => 'Email',
            'phone'     => 'Phone',
            'location'  => 'Location',
            'address'   => 'Address',
            'status'    => 'Status',
        );
    }

    // Prepare items
    public function prepare_items() {
        $data = array(); // Fetch your data from custom tables or ORM

        $per_page = $this->get_items_per_page( 'data_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items = count( $data );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ) );

        $this->items = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
    }

    // Display rows
    public function display_rows() {
        foreach ( $this->items as $item ) {
            echo '<tr>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }
    }

    // Define default column values
    protected function column_default( $item, $column_name ) {
        return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
    }

    // Define column for checkbox
    protected function column_cb( $item ) {
        return '<input type="checkbox" name="bulk-delete[]" value="' . $item['id'] . '" />';
    }

    // Define column for name
    protected function column_name( $item ) {
        return $item['name'];
    }

    // Define column for email
    protected function column_email( $item ) {
        return $item['email'];
    }

    // Define column for phone
    protected function column_phone( $item ) {
        return $item['phone'];
    }

    // Define column for location
    protected function column_location( $item ) {
        return $item['location'];
    }

    // Define column for address
    protected function column_address( $item ) {
        return $item['address'];
    }

    // Define column for status
    protected function column_status( $item ) {
        return $item['status'];
    }
}

// Display the WP_List_Table
function display_custom_wp_list_table() {
    $wp_list_table = new Custom_WP_List_Table();
    $wp_list_table->prepare_items();
    $wp_list_table->display();
}
?>
<div class="wrap">
    <h1>Custom WP List Table</h1>
    <?php
    // Display the WP_List_Table
    display_custom_wp_list_table();
    ?>
</div>
