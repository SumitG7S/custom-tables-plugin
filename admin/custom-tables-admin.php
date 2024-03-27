<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Tables Admin Class
 */
class Custom_Tables_Admin {
    /**
     * Constructor.
     */
    public function __construct() {
        // Hooks
        register_activation_hook(__FILE__, array($this, 'create_custom_tables'));
        add_action('admin_menu', array($this, 'add_custom_tables_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_post_custom_data_submit', array($this, 'handle_custom_data_submit'));
    }

    /**
     * Method to create custom tables on plugin activation.
     */
    public function create_custom_tables() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            location varchar(100) NOT NULL,
            address text NOT NULL,
            status varchar(20) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Method to add menu for custom tables admin page.
     */
    public function add_custom_tables_menu() {
        add_menu_page(
            'Custom Tables',
            'Custom Tables',
            'manage_options',
            'custom-tables-admin',
            array($this, 'custom_tables_admin_page'),
            'dashicons-admin-generic',
            30
        );
    }

    /**
     * Method to display custom tables admin page.
     */
    public function custom_tables_admin_page() {
        ?>
        <div class="wrap">
            <h1>Custom Tables Administration</h1>
            <!-- Add WP-List Table and forms here -->
            <?php
            $custom_data_list_table = new Custom_Data_List_Table();
            $custom_data_list_table->prepare_items();
            $custom_data_list_table->display();
            ?>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('custom_data_submit', 'custom_data_nonce'); ?>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br>
                <label for="phone">Phone:</label><br>
                <input type="text" id="phone" name="phone" required><br>
                <label for="location">Location:</label><br>
                <input type="text" id="location" name="location" required><br>
                <label for="address">Address:</label><br>
                <textarea id="address" name="address" required></textarea><br>
                <label for="status">Status:</label><br>
                <input type="text" id="status" name="status" required><br><br>
                <input type="hidden" name="action" value="custom_data_submit">
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
        <?php
    }

   
     
    public function handle_custom_data_submit() {
        if (!isset($_POST['custom_data_nonce']) || !wp_verify_nonce($_POST['custom_data_nonce'], 'custom_data_submit')) {
            wp_die('Unauthorized access');
        }

        if (isset($_POST['submit'])) {
            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $location = sanitize_text_field($_POST['location']);
            $address = sanitize_textarea_field($_POST['address']);
            $status = sanitize_text_field($_POST['status']);

            global $wpdb;
            $table_name = $wpdb->prefix . 'custom_data';
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'location' => $location,
                    'address' =>  $address,
                    'status' => $status,
                )
            );

            wp_redirect(admin_url('admin.php?page=custom-tables-admin'));
            exit;
        }
    }
}

// Instantiate the class
new Custom_Tables_Admin();
