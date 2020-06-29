<?php

namespace JWP\JPEN\Admin;

/**
 * Settings handler class
 */
class Settings {

    /**
     * Class constructor
     */
    function __construct( $page_slug ) {
        $this->page_slug = $page_slug;
        add_action( 'admin_init', [ $this, 'settings_page_init' ] );
    }

    /**
     * Initializes the settings page
     *
     * @return void
     */
    public function settings_page_init() {
        $this->registration();
        $this->sections();
    }

    /**
     * Registers all settings
     *
     * @return void
     */
    public function registration() {
        register_setting( $this->page_slug, 'jpen_smtp_host', 'sanitize_text_field' );
        register_setting( $this->page_slug, 'jpen_smtp_port', 'sanitize_text_field' );
        register_setting( $this->page_slug, 'jpen_smtp_secure' );
        register_setting( $this->page_slug, 'jpen_smtp_auth' );
        register_setting( $this->page_slug, 'jpen_smtp_debug' );
        register_setting( $this->page_slug, 'jpen_smtp_from', 'sanitize_text_field' );
        register_setting( $this->page_slug, 'jpen_smtp_name', 'sanitize_text_field' );
        register_setting( $this->page_slug, 'jpen_smtp_username', 'sanitize_text_field' );
        register_setting( $this->page_slug, 'jpen_smtp_password' );
    }

    /**
     * Adds settings sections
     *
     * @return void
     */
    public function sections() {
        
        add_settings_section( 
            'jpen_server_info', 
            __( 'SMTP Server Info', JPEN_DOMAIN ), 
            [ $this, 'server_info_section' ], 
            $this->page_slug 
        );

        add_settings_section( 
            'jpen_sender_info', 
            __( 'SMTP Sender Info', JPEN_DOMAIN ), 
            [ $this, 'sender_info_section' ], 
            $this->page_slug 
        );

        add_settings_section( 
            'jpen_auth', 
            __( 'SMTP User Authentication', JPEN_DOMAIN ), 
            [ $this, 'auth_section' ], 
            $this->page_slug 
        );
    }

    /**
     * Defines actions for server info section
     *
     * @return void
     */
    public function server_info_section() {
        ?>
        <p>Information about SMTP server</p>
        <?php

        add_settings_field( 
            'jpen_smtp_host', 
            __( 'SMTP Host', JPEN_DOMAIN ), 
            [ $this, 'smtp_host_field' ], 
            $this->page_slug, 
            'jpen_server_info', 
            array(
                'label_for' => 'jpen_smtp_host',
                'default'   => 'smtp.gmail.com',
            ) 
        );

        add_settings_field( 
            'jpen_smtp_port', 
            __( 'SMTP Port', JPEN_DOMAIN ), 
            [ $this, 'smtp_port_field' ], 
            $this->page_slug, 
            'jpen_server_info', 
            array(
                'label_for' => 'jpen_smtp_port',
                'default'   => 25,
            ) 
        );

        add_settings_field( 
            'jpen_smtp_secure', 
            __( 'SMTP Secure', JPEN_DOMAIN ), 
            [ $this, 'smtp_secure_select' ], 
            $this->page_slug, 
            'jpen_server_info', 
            array(
                'label_for' => 'jpen_smtp_secure',
                'options'   => [ 'tls', 'ssl' ],
                'default'   => 'tls',
            ) 
        );

        add_settings_field( 
            'jpen_smtp_auth', 
            __( 'SMTP Auth', JPEN_DOMAIN ), 
            [ $this, 'smtp_auth_check' ], 
            $this->page_slug, 
            'jpen_server_info', 
            array(
                'label_for' => 'jpen_smtp_auth',
                'default'   => true,
            ) 
        );

        add_settings_field( 
            'jpen_smtp_debug', 
            __( 'SMTP Debug', JPEN_DOMAIN ), 
            [ $this, 'smtp_debug_check' ], 
            $this->page_slug, 
            'jpen_server_info', 
            array(
                'label_for' => 'jpen_smtp_debug',
                'default'   => 1,
            ) 
        );
    }

    /**
     * Defines actions for sender info section
     *
     * @return void
     */
    public function sender_info_section() {
        ?>
        <p>Information about sender header</p>
        <?php

        add_settings_field( 
            'jpen_smtp_from', 
            __( 'SMTP From', JPEN_DOMAIN ), 
            [ $this, 'smtp_from_field' ], 
            $this->page_slug, 
            'jpen_sender_info', 
            array(
                'label_for' => 'jpen_smtp_from',
                'default'   => 'example@email.com',
            ) 
        );

        add_settings_field( 
            'jpen_smtp_name', 
            __( 'SMTP Name', JPEN_DOMAIN ), 
            [ $this, 'smtp_name_field' ], 
            $this->page_slug, 
            'jpen_sender_info', 
            array(
                'label_for' => 'jpen_smtp_name',
                'default'   => "Sender Name",
            ) 
        );
    }

    /**
     * Defines actions for user auth section
     *
     * @return void
     */
    public function auth_section() {
        ?>
        <p>Information about SMTP user authentication</p>
        <?php

        add_settings_field( 
            'jpen_smtp_username', 
            __( 'SMTP Username', JPEN_DOMAIN ), 
            [ $this, 'smtp_username_field' ], 
            $this->page_slug, 
            'jpen_auth', 
            array(
                'label_for' => 'jpen_smtp_username',
                'default'   => 'example@email.com',
            ) 
        );

        add_settings_field( 
            'jpen_smtp_password', 
            __( 'SMTP Password', JPEN_DOMAIN ), 
            [ $this, 'smtp_password_field' ], 
            $this->page_slug, 
            'jpen_auth', 
            array(
                'label_for' => 'jpen_smtp_password',
            ) 
        );
    }

    /**
     * Renders text field for smtp host
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_host_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        printf( "<input type='text' name=%s id=%s value=%s />", $field_id, $field_id, $value );
    }

    /**
     * Renders select list for smtp port
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_port_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );
        
        printf( "<input type='number' name='%s' id='%s' value=%d />", $field_id, $field_id, intval( $value ) );
    }

    /**
     * Renders select list for smtp secure
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_secure_select( $args ) {
        $field_id = $args['label_for'];
        $options  = $args['options'];
        $value    = get_option( $field_id, $args['default'] );
        
        printf( "<select name='%s' id='%s'>", $field_id, $field_id );

        foreach ( $options as $option ) {
            $selected = '';

            if ( $value == $option ) {
                $selected = 'selected';
            }

            printf( "<option value=%s %s>%s</option>", $option, $selected, $option );
        }
        
        printf( "</select>" );
    }

    /**
     * Renders checkbox for smtp auth
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_auth_check( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, (bool) $args['default'] );

        $checked = '';

        if( $value == 1 ) {
            $checked = 'checked';
        }
        
        printf( "<input type='checkbox' name='%s' id='%s' value=%d %s>Enable", $field_id, $field_id, true, $checked );
    }

    /**
     * Renders checkbox for smtp debug
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_debug_check( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        $checked = '';

        if( $value == 1 ) {
            $checked = 'checked';
        }
        
        printf( "<input type='checkbox' name='%s' id='%s' value=%d %s>Enable", $field_id, $field_id, 1, $checked );
    }

    /**
     * Renders smtp from field
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_from_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        printf( "<input type='email' name=%s id=%s value=%s />", $field_id, $field_id, $value );
    }

    /**
     * Renders smtp name field
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_name_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        printf( "<input type='text' name=%s id=%s value=%s />", $field_id, $field_id, $value );
    }

    /**
     * Renders SMTP password field
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_username_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        printf( "<input type='email' name=%s id=%s value=%s />", $field_id, $field_id, $value );
    }

    /**
     * Renders SMTP password field
     *
     * @param array $args
     * 
     * @return void
     */
    public function smtp_password_field( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id );

        printf( "<input type='password' name=%s id=%s value=%s />", $field_id, $field_id, $value );
    }
}