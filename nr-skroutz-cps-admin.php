<?php

class NR_Skroutz_Reject extends Nr_Skroutz {
    private $token;

    function __construct() {
        $this->token = get_option('api_token');

        //add menu
        add_action( 'admin_menu', array($this,'skroutz_reject_admin_menu') );
        add_action( 'admin_init', [$this,'register_skroutz_reject_settings'] );
        add_filter( 'manage_edit-shop_order_columns', [ $this, 'add_column_orders' ] );
        add_action( 'manage_shop_order_posts_custom_column', [ $this, 'nr_skroutz_add_column_orders_content' ] );

        // Admin post actions
        add_action( 'admin_post_reject_skroutz_order', [ $this, 'nr_skroutz_reject_order' ] );
    }

    public function skroutz_reject_admin_menu() {
        add_menu_page(
            __( 'Skroutz Reject', 'skroutz-reject'),
            __( 'Skroutz Reject', 'skroutz-reject'),
            'manage_options',
            'skroutz_reject_admin_menu',
            [$this, 'skroutz_reject_admin_page'],
            plugins_url( '/', __FILE__) . '/images/gt-icon.png'
        );
    }

    public function skroutz_reject_admin_page() {
        echo '<div class="wrap">';
        echo '<h1>'.__('Settings page for Skroutz Reject', 'skroutz-reject').'</h1><form method="post" action="options.php">';
        settings_fields( 'skroutz-reject-group' );
        do_settings_sections( 'skroutz-reject-group' );
        echo '<table class="form-table">';

        echo '<tr valign="top">';
        echo '<th scope="row">'.__('Skroutz CPS Token','skroutz-reject').'</th>';
        echo '<td><input type="text" name="api_token" value="'.get_option('api_token').'" /></td>';
        echo '</tr>';

        echo '</table>';
        submit_button();
        echo '</div>';
    }

    public function add_column_orders($columns) {
        /// Create tmp array
        $new_columns = array();
        // Loop over current columns
        foreach ( $columns as $column_name => $column_info ) {
            // Insert current column into tmp array
            $new_columns[ $column_name ] = $column_info;
            // Insert our column after order_status
            if ( 'order_status' === $column_name ) {
                $new_columns['skroutz_reject'] = __( 'Από', 'skroutz-reject' );
            }
        }
        // Return the array with new columns
        return $new_columns;
    }

    function nr_skroutz_add_column_orders_content( $column ) {
        require_once( ( __DIR__ ) . '/lib/skroutz.php' );
        global $post;
        if ( 'skroutz_reject' === $column ) {
            // Get order
            $order_id = $post->ID;
            $order = wc_get_order( $order_id );
            $meta = $order->get_meta('order_from');
            if ( empty($meta) ) {
                //Check if is skroutz order
                $skroutz_api = new Skroutz( get_option( 'api_token' ) );
                if ( $skroutz_api->is_skroutz_order($order_id) ) {
                    echo "Skroutz";
                    $order->update_meta_data("order_from", "skroutz");
                } else {
                    $order->update_meta_data("order_from", "other");
                }
                $order->save();
            } else if ( $meta == "skroutz" ) {
                echo "Skroutz";
            } else if ( $meta == "phone" ) {
                echo "Τηλεφωνική";
            }
        }
    }

    public function register_skroutz_reject_settings() {
        register_setting( 'skroutz-reject-group', 'api_token');
    }

    public function nr_skroutz_reject_order() {
        require_once( ( __DIR__ ) . '/lib/skroutz.php' );

        $skroutz = new Skroutz($this->token);

        // Load woocommerce order

        //$order = wc_get_order( (int) $_POST['order_id'] );
        $order_id = (int) $_POST['order_id'];
        echo $order_id;

        $reason = "";

        $skroutz->reject_order(57334, $reason);
        die("OK");
    }

}
