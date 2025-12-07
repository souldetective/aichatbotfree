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
        $glance_heading = aichatbotfree_get_field( 'glance_heading', $post_id, '' );
        $glance_columns = (array) aichatbotfree_get_field( 'glance_columns', $post_id, [] );
        $icon_grid_heading = aichatbotfree_get_field( 'icon_grid_heading', $post_id, '' );
        $icon_grid_items   = (array) aichatbotfree_get_field( 'icon_grid_items', $post_id, [] );
        $types_heading     = aichatbotfree_get_field( 'types_heading', $post_id, '' );
        $types_accordion   = (array) aichatbotfree_get_field( 'types_accordion', $post_id, [] );
        $bullet_heading    = aichatbotfree_get_field( 'bullet_box_heading', $post_id, '' );
        $bullet_style      = aichatbotfree_get_field( 'bullet_box_style', $post_id, 'check' );
        $bullet_items      = (array) aichatbotfree_get_field( 'bullet_box_items', $post_id, [] );
        $comparison_heading= aichatbotfree_get_field( 'comparison_heading', $post_id, '' );
        $comparison_headers= array_values( array_filter( (array) aichatbotfree_get_field( 'comparison_headers', $post_id, [] ) ) );
        $comparison_rows   = (array) aichatbotfree_get_field( 'comparison_rows', $post_id, [] );
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

        <?php if ( ! empty( $glance_columns ) ) : ?>
            <section class="post-glance">
                <?php if ( $glance_heading ) : ?>
                    <h2><?php echo esc_html( $glance_heading ); ?></h2>
                <?php endif; ?>
                <div class="glance-grid">
                    <?php foreach ( $glance_columns as $column ) :
                        $column_rows = array_filter( $column['rows'] ?? [] );
                        if ( empty( $column_rows ) ) {
                            continue;
                        }
                        $header_one = $column['header_one'] ?? '';
                        $header_two = $column['header_two'] ?? '';
                        ?>
                        <article class="glance-column">
                            <?php if ( ! empty( $column['title'] ) ) : ?>
                                <h3><?php echo esc_html( $column['title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( $header_one || $header_two ) : ?>
                                <div class="glance-headers">
                                    <span><?php echo esc_html( $header_one ); ?></span>
                                    <span><?php echo esc_html( $header_two ); ?></span>
                                </div>
                            <?php endif; ?>
                            <ul class="glance-rows">
                                <?php foreach ( $column_rows as $row ) :
                                    $label  = $row['label'] ?? '';
                                    $detail = $row['detail'] ?? '';
                                    if ( '' === trim( $label . $detail ) ) {
                                        continue;
                                    }
                                    ?>
                                    <li class="glance-row">
                                        <span class="glance-label"><?php echo esc_html( $label ); ?></span>
                                        <span class="glance-detail"><?php echo wp_kses_post( $detail ); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $icon_grid_items ) ) : ?>
            <section class="post-icon-grid">
                <?php if ( $icon_grid_heading ) : ?>
                    <h2><?php echo esc_html( $icon_grid_heading ); ?></h2>
                <?php endif; ?>
                <div class="icon-grid">
                    <?php foreach ( $icon_grid_items as $item ) :
                        $icon = $item['icon'] ?? '';
                        $text = $item['text'] ?? '';
                        if ( '' === trim( $icon . $text ) ) {
                            continue;
                        }
                        ?>
                        <div class="icon-grid-row">
                            <?php if ( $icon ) : ?>
                                <div class="icon-grid-icon" aria-hidden="true"><img src="<?php echo esc_url( $icon ); ?>" alt="" /></div>
                            <?php endif; ?>
                            <div class="icon-grid-text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $types_accordion ) ) : ?>
            <section class="post-types">
                <?php if ( $types_heading ) : ?>
                    <h2><?php echo esc_html( $types_heading ); ?></h2>
                <?php endif; ?>
                <div class="types-accordion">
                    <?php foreach ( $types_accordion as $type ) :
                        $title = $type['title'] ?? '';
                        $desc  = $type['description'] ?? '';
                        if ( '' === trim( $title . $desc ) ) {
                            continue;
                        }
                        ?>
                        <details class="accordion-item">
                            <summary><?php echo esc_html( $title ); ?></summary>
                            <div class="accordion-body"><?php echo wp_kses_post( wpautop( $desc ) ); ?></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( ! empty( $bullet_items ) ) : ?>
            <section class="post-bullet-box bullet-style-<?php echo esc_attr( $bullet_style ); ?>">
                <?php if ( $bullet_heading ) : ?>
                    <h2><?php echo esc_html( $bullet_heading ); ?></h2>
                <?php endif; ?>
                <ul>
                    <?php foreach ( $bullet_items as $bullet ) :
                        if ( empty( $bullet['text'] ) ) {
                            continue;
                        }
                        ?>
                        <li><?php echo esc_html( $bullet['text'] ); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php
        $has_comparison = ! empty( $comparison_headers ) && ! empty( $comparison_rows );
        $header_count   = $has_comparison ? count( $comparison_headers ) : 0;
        ?>
        <?php if ( $has_comparison ) : ?>
            <section class="post-comparison">
                <?php if ( $comparison_heading ) : ?>
                    <h2><?php echo esc_html( $comparison_heading ); ?></h2>
                <?php endif; ?>
                <div class="table-scroll">
                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <?php foreach ( $comparison_headers as $header ) : ?>
                                    <th><?php echo esc_html( $header['label'] ?? '' ); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $comparison_rows as $row ) :
                                $cells = array_values( $row['cells'] ?? [] );
                                if ( empty( $cells ) ) {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <?php for ( $i = 0; $i < $header_count; $i++ ) :
                                        $value = $cells[ $i ]['value'] ?? '';
                                        ?>
                                        <td><?php echo esc_html( $value ); ?></td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
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
