<?php
get_header();
$hero_subheading       = get_field( 'hero_subheading' );
$cta_primary_label     = get_field( 'hero_cta_primary_label' );
$cta_primary_url       = get_field( 'hero_cta_primary_url' );
$cta_secondary_label   = get_field( 'hero_cta_secondary_label' );
$cta_secondary_url     = get_field( 'hero_cta_secondary_url' );
$pillar_articles       = get_field( 'pillar_articles' );
$tool_highlight        = get_field( 'tool_highlight' );
$free_comparison       = get_field( 'free_comparison' );
$paid_comparison       = get_field( 'paid_comparison' );
$trust_copy            = get_field( 'trust_copy' );
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <div class="badge"><?php esc_html_e( 'AI Chatbot Guides & Reviews', 'aichatbotfree' ); ?></div>
            <h1><?php bloginfo( 'name' ); ?> – <?php esc_html_e( 'Find the Best Free & AI Chatbots for Your Business', 'aichatbotfree' ); ?></h1>
            <?php if ( $hero_subheading ) : ?>
                <p><?php echo esc_html( $hero_subheading ); ?></p>
            <?php else : ?>
                <p><?php esc_html_e( 'We compare chatbot builders, highlight free vs paid plans, and map the best tools by industry.', 'aichatbotfree' ); ?></p>
            <?php endif; ?>
            <div class="hero-actions">
                <?php if ( $cta_primary_label && $cta_primary_url ) : ?>
                    <a class="button primary" href="<?php echo esc_url( $cta_primary_url ); ?>"><?php echo esc_html( $cta_primary_label ); ?></a>
                <?php endif; ?>
                <?php if ( $cta_secondary_label && $cta_secondary_url ) : ?>
                    <a class="button secondary" href="<?php echo esc_url( $cta_secondary_url ); ?>"><?php echo esc_html( $cta_secondary_label ); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-card">
            <h3><?php esc_html_e( 'Why aichatbotfree.net?', 'aichatbotfree' ); ?></h3>
            <ul>
                <li><?php esc_html_e( 'Objective testing of free vs paid chatbot builders.', 'aichatbotfree' ); ?></li>
                <li><?php esc_html_e( 'Use-case guidance across industries and platforms.', 'aichatbotfree' ); ?></li>
                <li><?php esc_html_e( 'Affiliate transparency and always-updated reviews.', 'aichatbotfree' ); ?></li>
            </ul>
        </div>
    </div>
</section>

<section class="section categories">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Browse by Category', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Chatbot basics, builders, industries, and implementation guides.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="grid cards">
            <?php
            $category_slugs = [ 'chatbot-basics', 'chatbot-builders-tools', 'industries', 'implementation-guides' ];
            foreach ( $category_slugs as $slug ) {
                $category = get_category_by_slug( $slug );
                if ( $category ) {
                    echo '<div class="card">';
                    echo '<h3>' . esc_html( $category->name ) . '</h3>';
                    echo '<p>' . esc_html( $category->description ) . '</p>';
                    echo '<a class="button secondary" href="' . esc_url( get_category_link( $category ) ) . '">' . esc_html__( 'View Guides', 'aichatbotfree' ) . '</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<section class="section pillars">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Featured Pillar Articles', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Start with the fundamentals and deep-dive guides.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="grid cards">
            <?php
            if ( $pillar_articles ) {
                foreach ( $pillar_articles as $post ) {
                    setup_postdata( $post );
                    ?>
                    <div class="card">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <a class="button secondary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Guide', 'aichatbotfree' ); ?></a>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p>' . esc_html__( 'Select pillar articles in the homepage fields.', 'aichatbotfree' ) . '</p>';
            }
            ?>
        </div>
    </div>
</section>

<section class="section tool-highlight">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Tool Comparison Highlight', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Free plan limits, channels, AI support, and best-fit use cases.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="card">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Tool', 'aichatbotfree' ); ?></th>
                        <th><?php esc_html_e( 'Free Plan', 'aichatbotfree' ); ?></th>
                        <th><?php esc_html_e( 'Channels', 'aichatbotfree' ); ?></th>
                        <th><?php esc_html_e( 'AI Support', 'aichatbotfree' ); ?></th>
                        <th><?php esc_html_e( 'Best For', 'aichatbotfree' ); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( $tool_highlight ) {
                        foreach ( $tool_highlight as $post ) {
                            setup_postdata( $post );
                            $free_limits = get_field( 'free_limits', $post->ID );
                            $channels    = get_field( 'supported_channels', $post->ID );
                            $ai_support  = get_field( 'ai_support', $post->ID );
                            $best_for    = get_field( 'best_for', $post->ID );
                            $review_url  = get_permalink( $post );
                            ?>
                            <tr>
                                <td><?php the_title(); ?></td>
                                <td><?php echo esc_html( $free_limits ); ?></td>
                                <td><?php echo esc_html( $channels ); ?></td>
                                <td><?php echo esc_html( $ai_support ); ?></td>
                                <td><?php echo esc_html( $best_for ); ?></td>
                                <td><a class="button secondary" href="<?php echo esc_url( $review_url ); ?>"><?php esc_html_e( 'Read Review', 'aichatbotfree' ); ?></a></td>
                            </tr>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<tr><td colspan="5">' . esc_html__( 'Add chatbot tools to the highlight field.', 'aichatbotfree' ) . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="section comparison-double">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Free vs Paid Chatbot Plans', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Quickly compare plans and jump to in-depth reviews.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <div class="card">
                <h3><?php esc_html_e( 'Free Plan Comparison', 'aichatbotfree' ); ?></h3>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Tool', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'Free Plan', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'Channels', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'AI', 'aichatbotfree' ); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php aichatbotfree_render_comparison_rows( $free_comparison, 'free' ); ?>
                    </tbody>
                </table>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Paid Plan Comparison', 'aichatbotfree' ); ?></h3>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Tool', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'Starting At', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'Channels', 'aichatbotfree' ); ?></th>
                            <th><?php esc_html_e( 'AI', 'aichatbotfree' ); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php aichatbotfree_render_comparison_rows( $paid_comparison, 'paid' ); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="section use-cases">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Industry Use Cases', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Finance, healthcare, real estate, travel, restaurants, HR, SaaS, logistics, and more.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="grid cards">
            <?php
            $use_cases = [
                [
                    'label' => 'Finance',
                    'slug'  => 'finance',
                ],
                [
                    'label' => 'Healthcare',
                    'slug'  => 'healthcare',
                ],
                [
                    'label' => 'Real Estate',
                    'slug'  => 'real-estate',
                ],
                [
                    'label' => 'Travel',
                    'slug'  => 'travel',
                ],
                [
                    'label' => 'Restaurants',
                    'slug'  => 'restaurants',
                ],
                [
                    'label' => 'HR',
                    'slug'  => 'hr',
                ],
                [
                    'label' => 'SaaS',
                    'slug'  => 'saas',
                ],
                [
                    'label' => 'Logistics',
                    'slug'  => 'logistics',
                ],
            ];
            foreach ( $use_cases as $case ) {
                $category = get_category_by_slug( $case['slug'] );
                $link     = $category ? get_category_link( $category ) : '#';
                echo '<div class="card">';
                echo '<h3>' . esc_html( $case['label'] ) . '</h3>';
                echo '<p>' . esc_html__( 'See the best chatbot flows and tools for this vertical.', 'aichatbotfree' ) . '</p>';
                echo '<a class="button secondary" href="' . esc_url( $link ) . '">' . esc_html__( 'View Use Case', 'aichatbotfree' ) . '</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<section class="section latest-posts">
    <div class="container">
        <div class="section-title">
            <h2><?php esc_html_e( 'Latest Blog & Trends', 'aichatbotfree' ); ?></h2>
            <p><?php esc_html_e( 'Stay updated with new tactics, roll-outs, and product updates.', 'aichatbotfree' ); ?></p>
        </div>
        <div class="grid">
            <?php
            $latest = new WP_Query(
                [
                    'post_type'      => 'post',
                    'posts_per_page' => 6,
                ]
            );
            if ( $latest->have_posts() ) {
                while ( $latest->have_posts() ) {
                    $latest->the_post();
                    ?>
                    <article>
                        <div class="meta"><?php echo esc_html( get_the_date() ); ?></div>
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                        <a class="button secondary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Article', 'aichatbotfree' ); ?></a>
                    </article>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</section>

<section class="section trust">
    <div class="container">
        <h3><?php esc_html_e( 'Trust & Credibility', 'aichatbotfree' ); ?></h3>
        <p><?php echo $trust_copy ? esc_html( $trust_copy ) : esc_html__( 'We manually test chatbot tools, disclose affiliate partnerships, and keep our comparisons objective and refreshed.', 'aichatbotfree' ); ?></p>
    </div>
</section>
<?php
get_footer();
