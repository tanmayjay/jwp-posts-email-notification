<?php

/**
 * Retrives all available user roles
 *
 * @return object
 */
function jpen_get_user_roles() {
    global $wp_roles;

    $roles = $wp_roles->get_names();

    return $roles;
}