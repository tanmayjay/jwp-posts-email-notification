<?php

namespace JWP\JPEN;

/**
 * Plugin activator class
 */
class Activator {

    /**
     * Runs the activator
     *
     * @return void
     */
    public function run() {
        $this->add_info();
    }

    /**
     * Adds activation info
     *
     * @return void
     */
    public function add_info() {
        $activated = get_option( 'jpen_installed' );

        if ( ! $activated ) {
            update_option( 'jpen_installed', time() );
        }

        update_option( 'jpen_version', JPEN_VERSION );
    }
}