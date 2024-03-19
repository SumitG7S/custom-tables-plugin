<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Custom Tables Public Class
class Custom_Tables_Public {
    // Constructor
    public function __construct() {
        // Add shortcode for displaying data
        add_shortcode( 'custom_data', array( $this, 'display_custom_data_shortcode' ) );

        // Register REST API endpoints
        add_action( 'rest_api_init', array( $this, 'register_rest_endpoints' ) );
    }

    // Method to display data via shortcode
    public function display_custom_data_shortcode() {
        // Implement logic to retrieve data from custom tables or ORM
        $data = $this->get_custom_data();

        // Display data
        ob_start();
        ?>
        <div class="custom-data">
            <?php if ( $data ) : ?>
                <ul>
                    <?php foreach ( $data as $item ) : ?>
                        <li><?php echo esc_html( $item->name ); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No data found.</p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    // Method to retrieve data from custom tables or ORM
    private function get_custom_data() {
        // Implement logic to retrieve data from custom tables or ORM
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_data';
        $data = $wpdb->get_results( "SELECT * FROM $table_name" );

        return $data;
    }

    // Method to register REST API endpoints
    public function register_rest_endpoints() {
        register_rest_route( 'custom-tables/v1', '/data', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_custom_data_rest' ),
        ) );
    }

    // Method to retrieve data via REST API
    public function get_custom_data_rest( $request ) {
        // Implement logic to retrieve data from custom tables or ORM
        $data = $this->get_custom_data();

        if ( $data ) {
            return rest_ensure_response( $data );
        } else {
            return new WP_Error( 'no_data', 'No data found', array( 'status' => 404 ) );
        }
    }
}

// Instantiate the class
new Custom_Tables_Public();
