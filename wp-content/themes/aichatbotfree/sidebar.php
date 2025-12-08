<?php
/**
 * Sidebar template
 */
if ( ! is_active_sidebar( 'primary-sidebar' ) ) {
    return;
}
?>
<aside class="sidebar" aria-label="Sidebar">
    <?php dynamic_sidebar( 'primary-sidebar' ); ?>
</aside>
