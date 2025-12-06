<?php get_header(); ?>
<div class="container section">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article class="card">
        <h1><?php the_title(); ?></h1>
        <div class="meta"><?php echo esc_html( get_the_date() ); ?></div>
        <div class="entry-content"><?php the_content(); ?></div>

        <?php
        $post_id    = get_the_ID();
        $takeaways  = (array) aichatbotfree_get_field( 'takeaways', $post_id, [] );
        $pull_quote = (array) aichatbotfree_get_field( 'pull_quote', $post_id, [] );
        $faqs       = (array) aichatbotfree_get_field( 'faqs', $post_id, [] );
        $cta_banner = (array) aichatbotfree_get_field( 'cta_banner', $post_id, [] );
        ?>

        <?php if ( ! empty( $takeaways ) ) : ?>
            <section class="post-takeaways">
                <h2><?php esc_html_e( 'Key Takeaways', 'aichatbotfree' ); ?></h2>
                <ul>
                    <?php foreach ( $takeaways as $item ) : ?>
                        <?php if ( ! empty( $item['text'] ) ) : ?>
                            <li><?php echo esc_html( $item['text'] ); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $pull_quote['text'] ) ) : ?>
            <aside class="post-pull-quote">
                <blockquote>
                    <p><?php echo esc_html( $pull_quote['text'] ); ?></p>
                    <?php if ( ! empty( $pull_quote['attribution'] ) ) : ?>
                        <cite><?php echo esc_html( $pull_quote['attribution'] ); ?></cite>
                    <?php endif; ?>
                </blockquote>
            </aside>
        <?php endif; ?>

        <?php if ( ! empty( $faqs ) ) : ?>
            <section class="post-faqs">
                <h2><?php esc_html_e( 'FAQs', 'aichatbotfree' ); ?></h2>
                <div class="faq-list">
                    <?php foreach ( $faqs as $faq ) : ?>
                        <?php
                        $question = $faq['question'] ?? '';
                        $answer   = $faq['answer'] ?? '';
                        if ( '' === $question || '' === $answer ) {
                            continue;
                        }
                        ?>
                        <article class="faq-item">
                            <h3><?php echo esc_html( $question ); ?></h3>
                            <div class="faq-answer"><?php echo wp_kses_post( $answer ); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( array_filter( $cta_banner ) ) ) : ?>
            <section class="post-cta">
                <?php if ( ! empty( $cta_banner['heading'] ) ) : ?>
                    <h2><?php echo esc_html( $cta_banner['heading'] ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $cta_banner['body'] ) ) : ?>
                    <p><?php echo esc_html( $cta_banner['body'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $cta_banner['label'] ) && ! empty( $cta_banner['url'] ) ) : ?>
                    <a class="button" href="<?php echo esc_url( $cta_banner['url'] ); ?>"><?php echo esc_html( $cta_banner['label'] ); ?></a>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </article>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
