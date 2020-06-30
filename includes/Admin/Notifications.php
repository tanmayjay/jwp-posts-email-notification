<?php

namespace JWP\JPEN\Admin;

/**
 * Notifications handler class
 */
class Notifications {

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
        register_setting( $this->page_slug, 'jpen_user_roles' );
        register_setting( $this->page_slug, 'jpen_notify_for' );
        register_setting( $this->page_slug, 'jpen_exclude_post_creator' );
    }

    /**
     * Adds settings sections
     *
     * @return void
     */
    public function sections() {
        
        add_settings_section( 
            'jpen_notifications', 
            __( 'Notifications Settings', JPEN_DOMAIN ), 
            [ $this, 'pen_section' ], 
            $this->page_slug 
        );
    }

    /**
     * Defines actions for notification section
     *
     * @return void
     */
    public function pen_section() {
        
        add_settings_field( 
            'jpen_user_roles', 
            __( 'Send Notifications To', JPEN_DOMAIN ), 
            [ $this, 'user_type_check' ], 
            $this->page_slug, 
            'jpen_notifications', 
            array(
                'label_for' => 'jpen_user_roles',
                'roles'     => jpen_get_user_roles(),
                'default'   => [ 'administrator' ],
            ) 
        );

        add_settings_field( 
            'jpen_notify_for', 
            __( 'Send Notifications For', JPEN_DOMAIN ), 
            [ $this, 'notify_for_select' ], 
            $this->page_slug, 
            'jpen_notifications', 
            array(
                'label_for' => 'jpen_notify_for',
                'options'   => array(
                    'publish_post'           => __( 'When a post is created or updated', JPEN_DOMAIN ),
                    'transition_post_status' => __( 'Only when a post is created', JPEN_DOMAIN ),
                ),
                'default'   => 'publish_post',
            ) 
        );

        add_settings_field( 
            'jpen_exclude_post_creator', 
            __( 'Exclude Post Creator', JPEN_DOMAIN ), 
            [ $this, 'exclude_post_creator_check' ], 
            $this->page_slug, 
            'jpen_notifications', 
            array(
                'label_for' => 'jpen_exclude_post_creator',
                'default'   => 0,
            ) 
        );
    }

    /**
     * Renders the checkbox for user type
     *
     * @return void
     */
    public function user_type_check( $args ) {
        $field_id = $args['label_for'];
        $roles    = $args['roles'];
        $values   = get_option( $field_id );

        if ( "" == $values ) {
            $values = [];
            $values = array_merge( $values, $args['default'] );
        }
        
        foreach( $roles as $role ) {
            $checked = '';
            
            if ( in_array( strtolower( $role ), $values ) ) {
                $checked = 'checked';
            }

            printf( "<input type='checkbox' name=%s id=%s value=%s %s />%s<br/>", $field_id . '[]', $field_id, strtolower( $role ), $checked, $role );
        }
    }

    /**
     * Renders the notification for select list
     *
     * @return void
     */
    public function notify_for_select( $args ) {
        $field_id = $args['label_for'];
        $options  = $args['options'];
        $value    = get_option( $field_id, $args['default'] );

        printf( '<select name=%s id=%s>', $field_id, $field_id );
        
        foreach ( $options as $key => $option ) {
            $selected = '';

            if ( $key == $value ) {
                $selected = 'selected';
            }

            printf( "<option value=%s %s>%s</option>", $key, $selected, $option );
        }

        printf( '</select>' );
    }

    /**
     * Renders the post creator excluding checkbox
     *
     * @return void
     */
    public function exclude_post_creator_check( $args ) {
        $field_id = $args['label_for'];
        $value    = get_option( $field_id, $args['default'] );

        $checked  = '';

        if ( 1 == intval( $value ) ) {
            $checked = 'checked';
        }

        printf( "<input type='checkbox' name=%s id=%s value=%d %s />%s", $field_id, $field_id, 1, $checked, __( 'Exclude', JPEN_DOMAIN ) );
    }
}