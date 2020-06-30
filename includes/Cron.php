<?php

namespace JWP\JPEN;

/**
 * Cron scheduler handler class
 */
class Cron {

    /**
     * Schedules cron events
     *
     * @return void
     */
    public function schedule() {                 

        if ( ! wp_next_scheduled ( 'jpen_cron' ) ) {
            wp_schedule_event( strtotime( "tomorrow" ) - 1, 'daily', 'jpen_cron' );
        } 

        add_action( 'jpen_cron', [ $this, 'init_cron' ] );

        register_deactivation_hook( JPEN_FILE, [ $this, 'deactivate_cron' ] );
    }

    /**
     * Deactivates cron
     *
     * @return void
     */
    public function deactivate_cron() {
        wp_clear_scheduled_hook( 'jpen_cron' );
    }

    /**
     * Initializes the corn event
     *
     * @return void
     */
    public function init_cron() {
        $subject   = 'Daily Posts Report';
        $mail_to   = get_option( 'admin_email' );
        
        $curr_date = current_time( 'j-m-Y' );
        $posts     = jpen_get_daily_posts( $curr_date );
        $tot_post  = sizeof( $posts );
        
        ob_start();
        ?>
        <h5>Date: <?php echo $curr_date; ?></h5>
        <h5>Total Posts: <?php echo sizeof( $posts ); ?></h5>
        <hr>
        <?php foreach ( $posts as $post ) : ?>
            <p><a href="<?php echo get_permalink( $post ); ?>"><?php echo $post->post_title; ?></a></p>
        <?php endforeach;
        $message = ob_get_clean();

        wp_mail( $mail_to, $subject, $message );
    }
}