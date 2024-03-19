<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1>Add/Edit Data</h1>
    
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="name">Name</label></th>
                    <td><input type="text" name="name" id="name" value="<?php echo isset( $_POST['name'] ) ? esc_attr( $_POST['name'] ) : ''; ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">Email</label></th>
                    <td><input type="email" name="email" id="email" value="<?php echo isset( $_POST['email'] ) ? esc_attr( $_POST['email'] ) : ''; ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone">Phone</label></th>
                    <td><input type="text" name="phone" id="phone" value="<?php echo isset( $_POST['phone'] ) ? esc_attr( $_POST['phone'] ) : ''; ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="location">Location</label></th>
                    <td><input type="text" name="location" id="location" value="<?php echo isset( $_POST['location'] ) ? esc_attr( $_POST['location'] ) : ''; ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="address">Address</label></th>
                    <td><textarea name="address" id="address" rows="4" required><?php echo isset( $_POST['address'] ) ? esc_textarea( $_POST['address'] ) : ''; ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="status">Status</label></th>
                    <td>
                        <select name="status" id="status" required>
                            <option value="active" <?php selected( $_POST['status'], 'active' ); ?>>Active</option>
                            <option value="inactive" <?php selected( $_POST['status'], 'inactive' ); ?>>Inactive</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field( 'submit_data', 'submit_data_nonce' ); ?>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=custom-tables-admin' ) ); ?>" class="button button-secondary">Cancel</a>
        </p>
    </form>
</div>
