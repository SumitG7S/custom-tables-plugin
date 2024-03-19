<?php
/**
 * Plugin Name: Custom Tables Plugin
 * Description: Plugin for managing custom tables and exposing data via REST endpoints.
 * Version: 1.0.0
 * Author: Sumit Gupta
 * Text Domain: custom-tables-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load text domain
add_action('plugins_loaded', 'custom_tables_plugin_load_textdomain');

function custom_tables_plugin_load_textdomain() {
    load_plugin_textdomain('custom-tables-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'custom-tables-admin.php';
require_once plugin_dir_path(__FILE__) . 'rest_controller.php';
require_once plugin_dir_path(__FILE__) . 'form-template.php';
require_once plugin_dir_path(__FILE__) . 'custom-data-list-table.php';

// Instantiate the Custom Tables Admin class
new Custom_Tables_Admin();

// Register REST API endpoints
add_action('rest_api_init', 'register_custom_tables_rest_endpoints');

function register_custom_tables_rest_endpoints() {
    // Endpoint to get all custom data
    register_rest_route('custom-tables/v1', '/data', array(
        'methods' => 'GET',
        'callback' => 'get_custom_data',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ));

    // Add more endpoints for create, update, and delete as per your requirements
}

// Callback function to get all custom data
function get_custom_data($request) {
    // Logic to fetch data from custom table and return it
    return new WP_REST_Response('Get all custom data', 200);
}

// Custom REST Controller Class
class Custom_REST_Controller extends WP_REST_Controller {
    // Register the routes for the objects of the controller.
    public function register_routes() {
        $version = '1';
        $namespace = 'custom-tables/v' . $version;
        $base = 'data';

        register_rest_route($namespace, '/' . $base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
            ),
        ));

        register_rest_route($namespace, '/' . $base . '/(?P<id>\d+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array($this, 'update_item'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
            ),
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array($this, 'delete_item'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
            ),
        ));
    }

    // Retrieve data from the database
    public function get_items($request) {
        global $wpdb;
        
        // Fetch all data from custom table
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}custom_data");
        
        // Check if data exists
        if (empty($results)) {
            return new WP_Error('no_data', 'No data found.', array('status' => 404));
        }
        
        // Return data
        return rest_ensure_response($results);
    }

    // Create a data entry
    public function create_item($request) {
        $data = $request->get_params();

        // Example: Insert data into custom table
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'custom_data',
            array(
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'location' => $data['location'],
                'address'  => $data['address'],
                'status'   => $data['status'],
            )
        );

        return rest_ensure_response('Data inserted successfully.');
    }

    // Retrieve a single data entry
    public function get_item($request) {
        $id = $request['id'];
        
        global $wpdb;
        
        // Fetch single data from custom table
        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_data WHERE id = %d", $id));
        
        // Check if data exists
        if (empty($result)) {
            return new WP_Error('no_data', 'Data not found.', array('status' => 404));
        }
        
        // Return data
        return rest_ensure_response($result);
    }

    // Update a data entry
    public function update_item($request) {
        $id = $request['id'];
        $data = $request->get_params();

        // Example: Update data in custom table
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'custom_data',
            array(
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'location' => $data['location'],
                'address'  => $data['address'],
                'status'   => $data['status'],
            ),
            array('id' => $id)
        );

        return rest_ensure_response('Data updated successfully.');
    }

    // Delete a data entry
    public function delete_item($request) {
        $id = $request['id'];

        // Example: Delete data from custom table
        global $wpdb;
        $wpdb->delete(
            $wpdb->prefix . 'custom_data',
            array('id' => $id)
        );

        return rest_ensure_response('Data deleted successfully.');
    }

    // Check if a given request has access to get items
    public function get_items_permissions_check($request) {
        // Implement permission checks if needed
        return true;
    }

    // Check if a given request has access to create items
    public function create_item_permissions_check($request) {
        // Implement permission checks if needed
        return true;
    }

    // Check if a given request has access to get a specific item
    public function get_item_permissions_check($request) {
        // Implement permission checks if needed
        return true;
    }

    // Check if a given request has access to update a specific item
    public function update_item_permissions_check($request) {
        // Implement permission checks if needed
        return true;
    }

    // Check if a given request has access to delete a specific item
    public function delete_item_permissions_check($request) {
        // Implement permission checks if needed
        return true;
    }
}

// Register the custom REST controller
function register_custom_rest_controller() {
    $controller = new Custom_REST_Controller();
    $controller->register_routes();
}

add_action('rest_api_init', 'register_custom_rest_controller');
