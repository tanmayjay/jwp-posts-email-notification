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

/**
 * Retrieves all the posts created on a specific date
 *
 * @param string $date
 * 
 * @return object
 */
function jpen_get_daily_posts( $date ) {
    $date  = strtotime( $date );
    $year  = date( "Y", $date );
    $month = date( "m", $date ); 
    $day   = date( "d", $date );

    $args = array(
        'date_query' => array(
            'year'  => $year,
            'month' => $month,
            'day'   => $day,
        )
    );
    
    $posts = get_posts( $args );
   
    return $posts;
}

/**
 * Counts the posts of a specific date
 *
 * @param string $date
 * 
 * @return int
 */
function jpen_get_daily_posts_count( $date ) {
    $posts = jpen_get_daily_posts( $date );

    return sizeof( $posts );
}