<?php get_header(); ?>
<div class="container section">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article class="card">
        <h1><?php the_title(); ?></h1>
        <div class="meta"><?php echo esc_html( get_the_date() ); ?></div>
        <div class="entry-content"><?php the_content(); ?></div>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 12px; margin-top: 20px;">
            <?php
            $pricing   = aichatbotfree_get_field( 'pricing' );
            $free      = aichatbotfree_get_field( 'free_limits' );
            $channels  = aichatbotfree_get_field( 'supported_channels' );
            $ai        = aichatbotfree_get_field( 'ai_support' );
            $best_for  = aichatbotfree_get_field( 'best_for' );
            $affiliate = aichatbotfree_get_field( 'affiliate_url' );
            $data      = [
                __( 'Pricing (from /month)', 'aichatbotfree' ) => $pricing,
                __( 'Free Plan Limits', 'aichatbotfree' ) => $free,
                __( 'Supported Channels', 'aichatbotfree' ) => $channels,
                __( 'AI Support', 'aichatbotfree' ) => $ai,
                __( 'Best For', 'aichatbotfree' ) => $best_for,
            ];
            foreach ( $data as $label => $value ) {
                if ( ! empty( $value ) ) {
                    echo '<div class="card"><h4>' . esc_html( $label ) . '</h4><p>' . esc_html( $value ) . '</p></div>';
                }
            }
            ?>
        </div>
        <?php if ( $affiliate ) : ?>
            <p><a class="button primary" href="<?php echo esc_url( $affiliate ); ?>" target="_blank" rel="nofollow noopener"><?php esc_html_e( 'Get Started', 'aichatbotfree' ); ?></a></p>
        <?php endif; ?>
    </article>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
