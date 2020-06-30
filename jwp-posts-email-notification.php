<?php

/**
 * Plugin Name:       JWP Posts Email Notification
 * Plugin URI:        https://github.com/tanmayjay/wordpress/tree/master/3-Plugin-API/posts-email-notification
 * Description:       A plugin to notify users about each new post
 * Version:           1.2.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Tanmay Kirtania
 * Author URI:        https://linkedin.com/in/tanmay-kirtania
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       jwp-pen
 * 
 * 
 * Copyright (c) 2020 Tanmay Kirtania (jktanmay@gmail.com). All rights reserved.
 * 
 * This program is a free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see the License URI.
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class JWP_Posts_Email_Notification {

    const version = '1.2.0';

    //Private class constructor
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    //private class cloner
    private function __clone() {}

    /**
     * Initializes a singleton instance
     * 
     * @return \JWP_Posts_Email_Notification
     */
    public static function get_instance() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Defines the required constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'JPEN_VERSION', self::version );
        define( 'JPEN_FILE', __FILE__ );
        define( 'JPEN_PATH', __DIR__ );
        define( 'JPEN_URL', plugins_url( '', JPEN_FILE ) );
        define( 'JPEN_DOMAIN', 'jwp-pen' );
    }

    /**
     * Updates info on plugin activation
     *
     * @return void
     */
    public function activate() {
        $activator = new JWP\JPEN\Activator();
        $activator->run();
        
        $cron = new JWP\JPEN\Cron();
        $cron->schedule();
    }

    /**
     * Initializes the plugin
     *
     * @return void
     */
    public function init_plugin() {
        new JWP\JPEN\Mailer();
        
        if ( is_admin() ) {
            new JWP\JPEN\Admin();
        }
    }
}

/**
 * Initializes the main plugin
 *
 * @return \JWP_Posts_Email_Notification
 */
function jwp_posts_email_notification() {
    return JWP_Posts_Email_Notification::get_instance();
}

//kick of the plugin
jwp_posts_email_notification();