</main>
<footer class="footer">
    <div class="container footer-grid">
        <div>
            <h4><?php esc_html_e( 'About', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer_about',
                'container'      => false,
                'fallback_cb'    => false,
            ] );
            ?>
        </div>
        <div>
            <h4><?php esc_html_e( 'Popular Guides', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer_guides',
                'container'      => false,
                'fallback_cb'    => false,
            ] );
            ?>
        </div>
        <div>
            <h4><?php esc_html_e( 'Industries', 'aichatbotfree' ); ?></h4>
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer_industry',
                'container'      => false,
                'fallback_cb'    => false,
            ] );
            ?>
        </div>
        <div>
            <h4><?php esc_html_e( 'Newsletter & Social', 'aichatbotfree' ); ?></h4>
            <div class="newsletter">
                <form>
                    <label class="screen-reader-text" for="newsletter-email">Email</label>
                    <input type="email" id="newsletter-email" name="newsletter-email" placeholder="Enter your email" required />
                    <button class="primary" type="submit"><?php esc_html_e( 'Subscribe', 'aichatbotfree' ); ?></button>
                </form>
            </div>
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer_social',
                'container'      => false,
                'fallback_cb'    => false,
            ] );
            ?>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
