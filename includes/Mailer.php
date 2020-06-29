<?php

namespace JWP\JPEN;

/**
 * Mailer handler class
 */
class Mailer {

    const subject = 'New Blog Post';

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'phpmailer_init', [ $this, 'config_phpmailer' ] );
        $this->hook = get_option( 'jpen_notify_for', 'publish_post' );
        
        if ( 'transition_post_status' == $this->hook ) {
            add_action( $this->hook, [ $this, 'transition_post_status_email' ], 10, 3 );
        } else if ( 'publish_post' == $this->hook ) {
            add_action( $this->hook, [ $this, 'publish_post_email' ], 10, 2 );
        }
    }

    /**
     * Configures phpmailer to dend email
     *
     * @param object $phpmailer
     * 
     * @return void
     */
    public function config_phpmailer( $phpmailer ) {
        
        if ( ! is_object( $phpmailer ) ) {
            $phpmailer = (object) $phpmailer;
        }
    
        $phpmailer->Mailer     = 'smtp';
        $phpmailer->Host       = get_option( 'jpen_smtp_host', $phpmailer->Host );
        $phpmailer->SMTPAuth   = get_option( 'jpen_smtp_auth', $phpmailer->SMTPAuth );
        $phpmailer->Port       = get_option( 'jpen_smtp_port', $phpmailer->Port );
        $phpmailer->SMTPSecure = get_option( 'jpen_smtp_secure', $phpmailer->SMTPSecure );
        $phpmailer->From       = get_option( 'jpen_smtp_from', $phpmailer->From );
        $phpmailer->FromName   = get_option( 'jpen_smtp_name', $phpmailer->FromName );
        $phpmailer->Username   = get_option( 'jpen_smtp_username', $phpmailer->Username );
        $phpmailer->Password   = get_option( 'jpen_smtp_password', $phpmailer->Password );
    }

    /**
     * Sends mail when a post is created only
     *
     * @param string $new_status
     * @param string $old_status
     * @param object $post
     * 
     * @return void
     */
    public function transition_post_status_email( $new_status, $old_status, $post ) {
        
        if ( 'publish' === $new_status && $new_status !== $old_status ) {
            $blog    = get_option( 'blogname' );
            $author  = get_the_author_meta( 'display_name', $post->post_author );
            $message = "$author just published a new post on $blog. View here: " . get_permalink( $post );
            
            add_filter( 'get_recipients', [ $this, 'get_recipients' ] );
            $mail_to = apply_filters( 'get_recipients', $post );
            
            wp_mail( $mail_to, self::subject, $message );
        }
    }

    /**
     * Sends mail when a post is created or updated
     *
     * @param int $ID
     * @param object $post
     * 
     * @return void
     */
    public function publish_post_email( $ID, $post ) {
        
        $blog    = get_option( 'blogname' );
        $author  = get_the_author_meta( 'display_name', $post->post_author );
        $message = "$author just published a new post on $blog. View here: " . get_permalink( $ID );
        
        add_filter( 'get_recipients', [ $this, 'get_recipients' ] );
        $mail_to = apply_filters( 'get_recipients', $post );
        
        wp_mail( $mail_to, self::subject, $message );
    }

    /**
     * Retrives the required recipients' emails
     *
     * @param object $post
     * 
     * @return array
     */
    public function get_recipients( $post ) {
        $roles           = get_option( 'jpen_user_roles', [ 'administrator' ] );
        $args['role_in'] = $roles;
        $exclude_author  = get_option( 'jpen_exclude_post_creator', 0 );

        if ( 1 == $exclude_author ) {
            $args['exclude'] = $post->post_author;
        }

        $users = get_users( $args );
    
        foreach ( $users as $user ) {
            if( is_email( $user->user_email ) ) {
                $mail_to[] = $user->user_email;
            }
        }

        return array_unique( $mail_to );
    }
}