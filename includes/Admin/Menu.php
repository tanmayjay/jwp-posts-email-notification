<?php

namespace JWP\JPEN\Admin;

/**
 * Class to handle admin menu
 */
class Menu {

    const menu_slug      = 'posts-email-notification';
    const smtp_menu_slug = 'posts-email-notifications-settings';

    /**
     * class constructor
     */
    function __construct() {
        new Notifications( self::menu_slug );
        new Settings( self::smtp_menu_slug );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Adds a top level menu in admin panel
     *
     * @return void
     */
    public function admin_menu() {

        add_menu_page( 
            __( 'Posts Email Notifications', JPEN_DOMAIN ), 
            __( 'Email Notification', JPEN_DOMAIN ), 
            'manage_options', 
            self::menu_slug, 
            [ $this, 'general_page' ], 
            'dashicons-email-alt2' 
        );

        add_submenu_page( 
            self::menu_slug, 
            __( 'Posts Email Notification', JPEN_DOMAIN ), 
            __( 'Manage Notification', JPEN_DOMAIN ), 
            'manage_options', 
            self::menu_slug, 
            [ $this, 'general_page' ]
        );

        add_submenu_page( 
            self::menu_slug, 
            __( 'SMTP Settings', JPEN_DOMAIN ), 
            __( 'SMTP Settings', JPEN_DOMAIN ), 
            'manage_options', 
            self::smtp_menu_slug, 
            [ $this, 'settings_page' ]
        );
    }

    /**
     * Renders the default oage
     *
     * @return void
     */
    public function general_page() {
        $this->settings_form( self::menu_slug );
    }    
    
    /**
     * Renders the settings
     *
     * @return void
     */
    public function settings_page() {
        $this->settings_form( self::smtp_menu_slug );
    }

    /**
     * Renders default settings form
     *
     * @param string $slug
     * 
     * @return void
     */
    protected function settings_form( $slug ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_GET['settings-updated'] ) ) {
           
            add_settings_error( 
                'jpen_messages', 
                'jpen_message', 
                __( 'Settings Saved', JPEN_DOMAIN ), 
                'updated' 
            );
        }
        
        settings_errors( 'jpen_messages' );
        ?>
        <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
        <?php
        settings_fields( $slug );

        do_settings_sections( $slug );

        submit_button( __( 'Save Settings', JPEN_DOMAIN ) );
        ?>
        </form>
        </div>
        <?php
    }
}