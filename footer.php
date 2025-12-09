</main>
<footer class="site-footer" role="contentinfo">
    <div class="container footer-grid">
        <div class="footer-brand">
            <div class="footer-logo">[LOGO HERE]</div>
            <p class="footer-note"><?php echo esc_html__( 'AIChatBotFree compares free and paid chatbot builders with transparent reviews to help you choose the right tool.', 'aichatbotfree' ); ?></p>
        </div>

        <div class="footer-column" data-footer-column="about">
            <h4><?php esc_html_e( 'About', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'footer_about',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'aria_label'     => __( 'Footer About Links', 'aichatbotfree' ),
                ]
            );
            ?>
        </div>

        <div class="footer-column" data-footer-column="guides">
            <h4><?php esc_html_e( 'Guides', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'footer_guides',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'aria_label'     => __( 'Footer Guides Links', 'aichatbotfree' ),
                ]
            );
            ?>
        </div>

        <div class="footer-column" data-footer-column="industry">
            <h4><?php esc_html_e( 'Industries', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'footer_industry',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'aria_label'     => __( 'Footer Industry Links', 'aichatbotfree' ),
                ]
            );
            ?>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
