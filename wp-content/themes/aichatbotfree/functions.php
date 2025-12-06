<?php
/**
 * Theme functions for AI Chatbot Free.
 */

if ( ! defined( 'AI_CHATBOTFREE_VERSION' ) ) {
    define( 'AI_CHATBOTFREE_VERSION', '1.0.0' );
}

add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );

    register_nav_menus(
        [
            'primary'          => __( 'Primary Menu', 'aichatbotfree' ),
            'footer_about'     => __( 'Footer About', 'aichatbotfree' ),
            'footer_guides'    => __( 'Footer Guides', 'aichatbotfree' ),
            'footer_industry'  => __( 'Footer Industry', 'aichatbotfree' ),
            'footer_social'    => __( 'Footer Social', 'aichatbotfree' ),
        ]
    );
});

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'aichatbotfree-style', get_stylesheet_uri(), [], AI_CHATBOTFREE_VERSION );
    wp_enqueue_style( 'aichatbotfree-main', get_template_directory_uri() . '/assets/css/main.css', [], AI_CHATBOTFREE_VERSION );
});

// Register a dedicated options page when ACF Pro is available.
if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page(
        [
            'page_title' => __( 'Homepage Options', 'aichatbotfree' ),
            'menu_title' => __( 'Homepage Options', 'aichatbotfree' ),
            'menu_slug'  => 'aichatbotfree-homepage-options',
            'capability' => 'manage_options',
            'redirect'   => false,
        ]
    );
}

/**
 * Surface a dashboard notice when ACF is missing so editors know how to unlock
 * the homepage controls.
 */
add_action( 'admin_notices', function () {
    // If ACF is missing entirely, show the install prompt.
    if ( ! function_exists( 'get_field' ) ) {
        $url = esc_url( admin_url( 'plugin-install.php?s=Advanced+Custom+Fields&tab=search&type=term' ) );

        echo '<div class="notice notice-warning"><p>' . wp_kses_post( sprintf( __( 'The AI Chatbot Free theme uses Advanced Custom Fields for homepage options. Please install and activate ACF (Pro recommended) to edit the homepage sections. <a href="%s">Install ACF</a>.', 'aichatbotfree' ), $url ) ) . '</p></div>';

        return;
    }

    // ACF Free does not expose options pages. Guide editors to the front-page editor instead.
    if ( function_exists( 'get_field' ) && ! function_exists( 'acf_add_options_page' ) ) {
        $front_page_id = (int) get_option( 'page_on_front' );

        if ( $front_page_id ) {
            $edit_link = get_edit_post_link( $front_page_id, '' );

            if ( $edit_link ) {
                echo '<div class="notice notice-info"><p>' . wp_kses_post( sprintf( __( 'Homepage controls are stored on your static front page because ACF Options Pages require ACF Pro. <a href="%s">Edit the front page</a> to manage the hero, categories, comparisons, and trust blocks.', 'aichatbotfree' ), $edit_link ) ) . '</p></div>';
            }
        } else {
            echo '<div class="notice notice-info"><p>' . esc_html__( 'Set a static Front Page in Settings → Reading to unlock the Homepage fields when using the ACF free plugin.', 'aichatbotfree' ) . '</p></div>';
        }
    }
} );

/**
 * Provide an easy nav item in Appearance for editing the homepage fields when
 * the ACF options page is unavailable (e.g., ACF Free users).
 */
add_action( 'admin_menu', function () {
    if ( function_exists( 'acf_add_options_page' ) ) {
        return; // ACF Pro users will see the dedicated options page.
    }

    $front_page_id = (int) get_option( 'page_on_front' );

    add_theme_page(
        __( 'Homepage Fields', 'aichatbotfree' ),
        __( 'Homepage Fields', 'aichatbotfree' ),
        'edit_pages',
        'aichatbotfree-homepage-fields',
        function () use ( $front_page_id ) {
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__( 'Homepage Fields', 'aichatbotfree' ) . '</h1>';

            if ( $front_page_id && 'publish' === get_post_status( $front_page_id ) ) {
                $edit_link = get_edit_post_link( $front_page_id, '' );

                if ( $edit_link ) {
                    echo '<p>' . wp_kses_post( __( 'Use Advanced Custom Fields on your static front page to control the hero, category folders, comparisons, industry use cases, and trust blocks.', 'aichatbotfree' ) ) . '</p>';
                    echo '<p><a class="button button-primary" href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit Front Page Fields', 'aichatbotfree' ) . '</a></p>';
                }
            } else {
                echo '<p>' . wp_kses_post( __( 'Set a static Front Page in Settings → Reading, then edit that page to access the Homepage fields when using the ACF free plugin.', 'aichatbotfree' ) ) . '</p>';
            }

            echo '</div>';
        }
    );
} );

/**
 * Safely retrieve ACF fields with sensible fallbacks when ACF is not active.
 *
 * @param string     $selector Field name or key.
 * @param int|string $post_id  Optional post/context.
 * @param mixed      $default  Default value when no data exists.
 *
 * @return mixed
 */
function aichatbotfree_get_field( $selector, $post_id = false, $default = null ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $selector, $post_id );

        return null !== $value ? $value : $default;
    }

    // Support options lookups even when ACF is unavailable.
    if ( $post_id === 'option' || $post_id === 'options' ) {
        $option_value = get_option( $selector, null );

        if ( null !== $option_value && '' !== $option_value ) {
            return $option_value;
        }
    }

    if ( $post_id ) {
        $value = get_post_meta( $post_id, $selector, true );

        if ( '' !== $value ) {
            return $value;
        }
    }

    return $default;
}

/**
 * Register Custom Post Type: chatbot_tool
 */
add_action( 'init', function () {
    $labels = [
        'name'               => __( 'Chatbot Tools', 'aichatbotfree' ),
        'singular_name'      => __( 'Chatbot Tool', 'aichatbotfree' ),
        'add_new_item'       => __( 'Add New Chatbot Tool', 'aichatbotfree' ),
        'edit_item'          => __( 'Edit Chatbot Tool', 'aichatbotfree' ),
        'new_item'           => __( 'New Chatbot Tool', 'aichatbotfree' ),
        'view_item'          => __( 'View Chatbot Tool', 'aichatbotfree' ),
        'search_items'       => __( 'Search Chatbot Tools', 'aichatbotfree' ),
        'not_found'          => __( 'No chatbot tools found', 'aichatbotfree' ),
        'menu_name'          => __( 'Chatbot Tools', 'aichatbotfree' ),
    ];

    register_post_type( 'chatbot_tool', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-format-chat',
        'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author' ],
        'rewrite'             => [ 'slug' => 'chatbot-tools' ],
    ] );

    // Taxonomies
    register_taxonomy(
        'tool_type',
        'chatbot_tool',
        [
            'label'        => __( 'Tool Types', 'aichatbotfree' ),
            'public'       => true,
            'hierarchical' => true,
            'show_in_rest' => true,
        ]
    );

    register_taxonomy(
        'primary_channel',
        'chatbot_tool',
        [
            'label'        => __( 'Primary Channels', 'aichatbotfree' ),
            'public'       => true,
            'hierarchical' => false,
            'show_in_rest' => true,
        ]
    );

    register_taxonomy(
        'pricing_model',
        'chatbot_tool',
        [
            'label'        => __( 'Pricing Models', 'aichatbotfree' ),
            'public'       => true,
            'hierarchical' => false,
            'show_in_rest' => true,
        ]
    );
});

/**
 * ACF fields for homepage and chatbot_tool data.
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group(
        [
            'key'                   => 'group_homepage_blocks',
            'title'                 => 'Homepage Blocks',
            'fields'                => [
                [
                    'key'   => 'field_home_hero_heading',
                    'label' => 'Hero Heading',
                    'name'  => 'hero_heading',
                    'type'  => 'text',
                    'instructions' => 'Overrides the site title in the hero.',
                ],
                [
                    'key'   => 'field_hero_subheading',
                    'label' => 'Hero Subheading',
                    'name'  => 'hero_subheading',
                    'type'  => 'textarea',
                    'rows'  => 3,
                ],
                [
                    'key'   => 'field_hero_reason_title',
                    'label' => 'Hero Side Card Title',
                    'name'  => 'hero_reason_title',
                    'type'  => 'text',
                    'default_value' => 'Why aichatbotfree.net?',
                ],
                [
                    'key'   => 'field_hero_reason_items',
                    'label' => 'Hero Side Card Bullets',
                    'name'  => 'hero_reason_items',
                    'type'  => 'repeater',
                    'button_label' => 'Add Bullet',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_hero_reason_text',
                            'label' => 'Bullet Text',
                            'name'  => 'text',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_hero_icons',
                    'label' => 'Hero Icons/Highlights',
                    'name'  => 'hero_icons',
                    'type'  => 'repeater',
                    'button_label' => 'Add Highlight',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_hero_icon',
                            'label' => 'Icon (emoji or text)',
                            'name'  => 'icon',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_hero_icon_text',
                            'label' => 'Highlight Text',
                            'name'  => 'text',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_hero_bg_color',
                    'label' => 'Hero Background Color',
                    'name'  => 'hero_background_color',
                    'type'  => 'color_picker',
                ],
                [
                    'key'   => 'field_hero_bg_image',
                    'label' => 'Hero Background Image',
                    'name'  => 'hero_background_image',
                    'type'  => 'image',
                    'return_format' => 'array',
                ],
                [
                    'key'   => 'field_hero_cta_primary',
                    'label' => 'Primary CTA Label',
                    'name'  => 'hero_cta_primary_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_hero_cta_primary_url',
                    'label' => 'Primary CTA URL',
                    'name'  => 'hero_cta_primary_url',
                    'type'  => 'url',
                ],
                [
                    'key'   => 'field_hero_cta_secondary',
                    'label' => 'Secondary CTA Label',
                    'name'  => 'hero_cta_secondary_label',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_hero_cta_secondary_url',
                    'label' => 'Secondary CTA URL',
                    'name'  => 'hero_cta_secondary_url',
                    'type'  => 'url',
                ],
                [
                    'key'   => 'field_categories_title',
                    'label' => 'Categories Title',
                    'name'  => 'categories_title',
                    'type'  => 'text',
                    'default_value' => 'Browse by Category',
                ],
                [
                    'key'   => 'field_categories_intro',
                    'label' => 'Categories Intro',
                    'name'  => 'categories_intro',
                    'type'  => 'textarea',
                    'rows'  => 2,
                    'default_value' => 'Chatbot basics, builders, industries, and implementation guides.',
                ],
                [
                    'key'   => 'field_category_cards',
                    'label' => 'Category Cards',
                    'name'  => 'category_cards',
                    'type'  => 'repeater',
                    'button_label' => 'Add Category Card',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_category_choice',
                            'label' => 'Category',
                            'name'  => 'category',
                            'type'  => 'taxonomy',
                            'taxonomy' => 'category',
                            'field_type' => 'select',
                            'return_format' => 'object',
                        ],
                        [
                            'key'   => 'field_category_color',
                            'label' => 'Accent Color',
                            'name'  => 'accent_color',
                            'type'  => 'color_picker',
                        ],
                        [
                            'key'   => 'field_category_icon',
                            'label' => 'Icon (emoji or text)',
                            'name'  => 'icon',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_pillar_title',
                    'label' => 'Pillar Section Title',
                    'name'  => 'pillar_title',
                    'type'  => 'text',
                    'default_value' => 'Featured Pillar Articles',
                ],
                [
                    'key'   => 'field_pillar_intro',
                    'label' => 'Pillar Section Intro',
                    'name'  => 'pillar_intro',
                    'type'  => 'textarea',
                    'rows'  => 2,
                    'default_value' => 'Start with the fundamentals and deep-dive guides.',
                ],
                [
                    'key'   => 'field_pillar_posts',
                    'label' => 'Featured Pillar Articles',
                    'name'  => 'pillar_articles',
                    'type'  => 'relationship',
                    'post_type' => [ 'post' ],
                    'filters' => [ 'search', 'taxonomy' ],
                    'elements' => '',
                    'return_format' => 'object',
                    'max' => 4,
                ],
                [
                    'key'   => 'field_tool_highlight_title',
                    'label' => 'Tool Highlight Title',
                    'name'  => 'tool_highlight_title',
                    'type'  => 'text',
                    'default_value' => 'Tool Comparison Highlight',
                ],
                [
                    'key'   => 'field_tool_highlight_intro',
                    'label' => 'Tool Highlight Intro',
                    'name'  => 'tool_highlight_intro',
                    'type'  => 'textarea',
                    'rows'  => 2,
                    'default_value' => 'Free plan limits, channels, AI support, and best-fit use cases.',
                ],
                [
                    'key'   => 'field_tool_highlight',
                    'label' => 'Tool Comparison Highlight',
                    'name'  => 'tool_highlight',
                    'type'  => 'relationship',
                    'post_type' => [ 'chatbot_tool' ],
                    'filters' => [ 'search', 'taxonomy' ],
                    'return_format' => 'object',
                    'max' => 4,
                ],
                [
                    'key'   => 'field_tool_highlight_terms',
                    'label' => 'Tool Highlight Terms',
                    'name'  => 'tool_highlight_terms',
                    'type'  => 'taxonomy',
                    'taxonomy' => 'tool_type',
                    'field_type' => 'multi_select',
                    'return_format' => 'id',
                    'instructions' => 'Select tool_type terms to auto-populate the comparison table. Leaves manual picks above as fallback.',
                ],
                [
                    'key'   => 'field_tool_highlight_limit',
                    'label' => 'Tool Highlight Count',
                    'name'  => 'tool_highlight_count',
                    'type'  => 'number',
                    'default_value' => 4,
                    'min' => 1,
                    'max' => 10,
                ],
                [
                    'key'   => 'field_tool_highlight_headers',
                    'label' => 'Tool Highlight Headers',
                    'name'  => 'tool_highlight_headers',
                    'type'  => 'group',
                    'sub_fields' => [
                        [
                            'key' => 'field_tool_header_tool',
                            'label' => 'Tool Label',
                            'name' => 'tool',
                            'type' => 'text',
                            'default_value' => 'Tool',
                        ],
                        [
                            'key' => 'field_tool_header_free',
                            'label' => 'Free Plan Label',
                            'name' => 'free_plan',
                            'type' => 'text',
                            'default_value' => 'Free Plan',
                        ],
                        [
                            'key' => 'field_tool_header_channels',
                            'label' => 'Channels Label',
                            'name' => 'channels',
                            'type' => 'text',
                            'default_value' => 'Channels',
                        ],
                        [
                            'key' => 'field_tool_header_ai',
                            'label' => 'AI Label',
                            'name' => 'ai_support',
                            'type' => 'text',
                            'default_value' => 'AI Support',
                        ],
                        [
                            'key' => 'field_tool_header_best',
                            'label' => 'Best For Label',
                            'name' => 'best_for',
                            'type' => 'text',
                            'default_value' => 'Best For',
                        ],
                        [
                            'key' => 'field_tool_header_rating',
                            'label' => 'Rating Label',
                            'name' => 'rating',
                            'type' => 'text',
                            'default_value' => 'Rating',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_free_table',
                    'label' => 'Free Plan Comparison',
                    'name'  => 'free_comparison',
                    'type'  => 'repeater',
                    'button_label' => 'Add Free Tool',
                    'sub_fields' => [
                        [
                            'key' => 'field_free_tool',
                            'label' => 'Tool',
                            'name' => 'tool',
                            'type' => 'post_object',
                            'post_type' => [ 'chatbot_tool' ],
                            'return_format' => 'object',
                        ],
                        [
                            'key' => 'field_free_plan',
                            'label' => 'Free Plan Details',
                            'name' => 'free_plan',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_free_channels',
                            'label' => 'Channels',
                            'name' => 'channels',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_free_ai',
                            'label' => 'AI Support',
                            'name' => 'ai_support',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_free_rating',
                            'label' => 'Rating (0-5)',
                            'name' => 'rating',
                            'type' => 'number',
                            'min' => 0,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                ],
                [
                    'key'   => 'field_paid_table',
                    'label' => 'Paid Plan Comparison',
                    'name'  => 'paid_comparison',
                    'type'  => 'repeater',
                    'button_label' => 'Add Paid Tool',
                    'sub_fields' => [
                        [
                            'key' => 'field_paid_tool',
                            'label' => 'Tool',
                            'name' => 'tool',
                            'type' => 'post_object',
                            'post_type' => [ 'chatbot_tool' ],
                            'return_format' => 'object',
                        ],
                        [
                            'key' => 'field_paid_price',
                            'label' => 'Starting Price',
                            'name' => 'price',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_paid_channels',
                            'label' => 'Channels',
                            'name' => 'channels',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_paid_ai',
                            'label' => 'AI Support',
                            'name' => 'ai_support',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_paid_rating',
                            'label' => 'Rating (0-5)',
                            'name' => 'rating',
                            'type' => 'number',
                            'min' => 0,
                            'max' => 5,
                            'step' => 0.1,
                        ],
                    ],
                ],
                [
                    'key'   => 'field_free_headers',
                    'label' => 'Free Table Headers',
                    'name'  => 'free_headers',
                    'type'  => 'group',
                    'sub_fields' => [
                        [ 'key' => 'field_free_header_tool', 'label' => 'Tool', 'name' => 'tool', 'type' => 'text', 'default_value' => 'Tool' ],
                        [ 'key' => 'field_free_header_plan', 'label' => 'Plan', 'name' => 'plan', 'type' => 'text', 'default_value' => 'Free Plan' ],
                        [ 'key' => 'field_free_header_channels', 'label' => 'Channels', 'name' => 'channels', 'type' => 'text', 'default_value' => 'Channels' ],
                        [ 'key' => 'field_free_header_ai', 'label' => 'AI', 'name' => 'ai', 'type' => 'text', 'default_value' => 'AI' ],
                        [ 'key' => 'field_free_header_rating', 'label' => 'Rating', 'name' => 'rating', 'type' => 'text', 'default_value' => 'Rating' ],
                    ],
                ],
                [
                    'key'   => 'field_paid_headers',
                    'label' => 'Paid Table Headers',
                    'name'  => 'paid_headers',
                    'type'  => 'group',
                    'sub_fields' => [
                        [ 'key' => 'field_paid_header_tool', 'label' => 'Tool', 'name' => 'tool', 'type' => 'text', 'default_value' => 'Tool' ],
                        [ 'key' => 'field_paid_header_price', 'label' => 'Price', 'name' => 'price', 'type' => 'text', 'default_value' => 'Starting At' ],
                        [ 'key' => 'field_paid_header_channels', 'label' => 'Channels', 'name' => 'channels', 'type' => 'text', 'default_value' => 'Channels' ],
                        [ 'key' => 'field_paid_header_ai', 'label' => 'AI', 'name' => 'ai', 'type' => 'text', 'default_value' => 'AI' ],
                        [ 'key' => 'field_paid_header_rating', 'label' => 'Rating', 'name' => 'rating', 'type' => 'text', 'default_value' => 'Rating' ],
                    ],
                ],
                [
                    'key'   => 'field_use_cases_title',
                    'label' => 'Use Cases Title',
                    'name'  => 'use_cases_title',
                    'type'  => 'text',
                    'default_value' => 'Industry Use Cases',
                ],
                [
                    'key'   => 'field_use_cases_intro',
                    'label' => 'Use Cases Intro',
                    'name'  => 'use_cases_intro',
                    'type'  => 'textarea',
                    'rows'  => 2,
                    'default_value' => 'Finance, healthcare, real estate, travel, restaurants, HR, SaaS, logistics, and more.',
                ],
                [
                    'key'   => 'field_use_cases',
                    'label' => 'Use Case Cards',
                    'name'  => 'use_cases',
                    'type'  => 'repeater',
                    'button_label' => 'Add Use Case',
                    'sub_fields' => [
                        [
                            'key'   => 'field_use_case_title',
                            'label' => 'Title',
                            'name'  => 'title',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_use_case_description',
                            'label' => 'Description',
                            'name'  => 'description',
                            'type'  => 'textarea',
                            'rows'  => 2,
                        ],
                        [
                            'key'   => 'field_use_case_category',
                            'label' => 'Category Link',
                            'name'  => 'category',
                            'type'  => 'taxonomy',
                            'taxonomy' => 'category',
                            'field_type' => 'select',
                            'return_format' => 'object',
                        ],
                        [
                            'key'   => 'field_use_case_icon',
                            'label' => 'Icon (emoji or text)',
                            'name'  => 'icon',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_use_case_bg',
                            'label' => 'Background Image',
                            'name'  => 'background',
                            'type'  => 'image',
                            'return_format' => 'array',
                        ],
                        [
                            'key'   => 'field_use_case_color',
                            'label' => 'Background Color',
                            'name'  => 'background_color',
                            'type'  => 'color_picker',
                        ],
                    ],
                ],
                [
                    'key'   => 'field_latest_title',
                    'label' => 'Latest Posts Title',
                    'name'  => 'latest_title',
                    'type'  => 'text',
                    'default_value' => 'Latest Blog & Trends',
                ],
                [
                    'key'   => 'field_latest_intro',
                    'label' => 'Latest Posts Intro',
                    'name'  => 'latest_intro',
                    'type'  => 'textarea',
                    'rows'  => 2,
                    'default_value' => 'Stay updated with new tactics, roll-outs, and product updates.',
                ],
                [
                    'key'   => 'field_latest_category',
                    'label' => 'Latest Posts Category Filter',
                    'name'  => 'latest_category',
                    'type'  => 'taxonomy',
                    'taxonomy' => 'category',
                    'field_type' => 'select',
                    'return_format' => 'id',
                    'allow_null' => 1,
                ],
                [
                    'key'   => 'field_latest_count',
                    'label' => 'Latest Posts Count',
                    'name'  => 'latest_count',
                    'type'  => 'number',
                    'default_value' => 3,
                    'min' => 1,
                    'max' => 6,
                ],
                [
                    'key'   => 'field_trust_title',
                    'label' => 'Trust Title',
                    'name'  => 'trust_title',
                    'type'  => 'text',
                    'default_value' => 'Trust & Credibility',
                ],
                [
                    'key'   => 'field_trust_items',
                    'label' => 'Trust Items',
                    'name'  => 'trust_items',
                    'type'  => 'repeater',
                    'button_label' => 'Add Trust Item',
                    'sub_fields' => [
                        [ 'key' => 'field_trust_icon', 'label' => 'Icon (emoji or text)', 'name' => 'icon', 'type' => 'text' ],
                        [ 'key' => 'field_trust_title_item', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ],
                        [ 'key' => 'field_trust_text', 'label' => 'Paragraph', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ],
                    ],
                ],
                [
                    'key'   => 'field_trust_copy',
                    'label' => 'Trust Block Copy (legacy)',
                    'name'  => 'trust_copy',
                    'type'  => 'textarea',
                    'rows'  => 4,
                    'instructions' => 'Use Trust Items above; this is a fallback paragraph.',
                ],
            ],
            'location'              => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'aichatbotfree-homepage-options',
                    ],
                ],
                [
                    [
                        'param'    => 'page_type',
                        'operator' => '==',
                        'value'    => 'front_page',
                    ],
                ],
            ],
        ]
    );

    acf_add_local_field_group(
        [
            'key' => 'group_chatbot_tool_meta',
            'title' => 'Chatbot Tool Details',
            'fields' => [
                [
                    'key' => 'field_price',
                    'label' => 'Pricing (from /month)',
                    'name' => 'pricing',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_free_limits',
                    'label' => 'Free Plan Limits',
                    'name' => 'free_limits',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_channels',
                    'label' => 'Supported Channels',
                    'name' => 'supported_channels',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_ai_support',
                    'label' => 'AI Support',
                    'name' => 'ai_support',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_tool_rating',
                    'label' => 'Star Rating (0-5)',
                    'name' => 'star_rating',
                    'type' => 'number',
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                [
                    'key' => 'field_tool_rating_note',
                    'label' => 'Rating Note',
                    'name' => 'rating_note',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_best_for',
                    'label' => 'Best For',
                    'name' => 'best_for',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_affiliate_url',
                    'label' => 'Affiliate URL',
                    'name' => 'affiliate_url',
                    'type' => 'url',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'chatbot_tool',
                    ],
                ],
            ],
        ]
    );

    acf_add_local_field_group(
        [
            'key'      => 'group_post_enhancements',
            'title'    => 'Post Enhancements',
            'fields'   => [
                [
                    'key'           => 'field_post_takeaways',
                    'label'         => 'Key Takeaways',
                    'name'          => 'takeaways',
                    'type'          => 'repeater',
                    'button_label'  => 'Add Takeaway',
                    'instructions'  => 'Optional punchy bullets that summarize the article.',
                    'sub_fields'    => [
                        [
                            'key'   => 'field_post_takeaway_text',
                            'label' => 'Takeaway',
                            'name'  => 'text',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'          => 'field_post_pull_quote',
                    'label'        => 'Pull Quote',
                    'name'         => 'pull_quote',
                    'type'         => 'group',
                    'instructions' => 'Optional highlighted quote for emphasis.',
                    'sub_fields'   => [
                        [
                            'key'   => 'field_post_pull_quote_text',
                            'label' => 'Quote Text',
                            'name'  => 'text',
                            'type'  => 'textarea',
                            'rows'  => 3,
                        ],
                        [
                            'key'   => 'field_post_pull_quote_attr',
                            'label' => 'Attribution',
                            'name'  => 'attribution',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'           => 'field_post_faqs',
                    'label'         => 'FAQs',
                    'name'          => 'faqs',
                    'type'          => 'repeater',
                    'button_label'  => 'Add FAQ',
                    'instructions'  => 'Add question and answer pairs for this article.',
                    'sub_fields'    => [
                        [
                            'key'   => 'field_post_faq_question',
                            'label' => 'Question',
                            'name'  => 'question',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_post_faq_answer',
                            'label' => 'Answer',
                            'name'  => 'answer',
                            'type'  => 'wysiwyg',
                            'tabs'  => 'visual',
                            'toolbar' => 'basic',
                        ],
                    ],
                ],
                [
                    'key'          => 'field_post_schema',
                    'label'        => 'FAQ / Article Schema (JSON-LD)',
                    'name'         => 'schema_jsonld',
                    'type'         => 'textarea',
                    'rows'         => 6,
                    'instructions' => 'Paste JSON-LD (e.g., FAQPage). If empty, FAQPage schema will be auto-generated from the FAQ repeater.',
                ],
                [
                    'key'         => 'field_post_cta_banner',
                    'label'       => 'CTA Banner',
                    'name'        => 'cta_banner',
                    'type'        => 'group',
                    'instructions'=> 'Optional CTA at the end of the article.',
                    'sub_fields'  => [
                        [
                            'key'   => 'field_post_cta_heading',
                            'label' => 'Heading',
                            'name'  => 'heading',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_post_cta_body',
                            'label' => 'Body',
                            'name'  => 'body',
                            'type'  => 'textarea',
                            'rows'  => 3,
                        ],
                        [
                            'key'   => 'field_post_cta_label',
                            'label' => 'Button Label',
                            'name'  => 'label',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_post_cta_url',
                            'label' => 'Button URL',
                            'name'  => 'url',
                            'type'  => 'url',
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'post',
                    ],
                ],
            ],
            'position' => 'acf_after_title',
        ]
    );
}

/**
 * Convert a numeric rating to star icons.
 */
function aichatbotfree_render_rating( $rating ) {
    if ( ! is_numeric( $rating ) ) {
        return '';
    }

    $full_stars = floor( $rating );
    $half_star  = $rating - $full_stars >= 0.5;
    $output     = '<div class="rating" aria-label="' . esc_attr( $rating ) . ' out of 5 stars">';

    for ( $i = 0; $i < $full_stars; $i++ ) {
        $output .= '<span class="star full">★</span>';
    }

    if ( $half_star ) {
        $output .= '<span class="star half">★</span>';
    }

    $remaining = 5 - $full_stars - ( $half_star ? 1 : 0 );

    for ( $i = 0; $i < $remaining; $i++ ) {
        $output .= '<span class="star empty">☆</span>';
    }

    $output .= '<span class="rating-number">' . esc_html( number_format( (float) $rating, 1 ) ) . '</span>';
    $output .= '</div>';

    return $output;
}

/**
 * Helper to render the comparison table rows.
 */
function aichatbotfree_render_comparison_rows( $items, $type = 'free' ) {
    if ( empty( $items ) ) {
        return;
    }

    foreach ( $items as $item ) {
        $tool      = $item['tool'] ?? null;
        $plan      = $type === 'free' ? ( $item['free_plan'] ?? '' ) : ( $item['price'] ?? '' );
        $channels  = $item['channels'] ?? '';
        $ai        = $item['ai_support'] ?? '';
        $rating    = $item['rating'] ?? '';
        $link      = $tool ? get_permalink( $tool ) : '';
        $tool_name = $tool ? get_the_title( $tool ) : '';
        echo '<tr>';
        echo '<td>' . esc_html( $tool_name ) . '</td>';
        echo '<td>' . esc_html( $plan ) . '</td>';
        echo '<td>' . esc_html( $channels ) . '</td>';
        echo '<td>' . esc_html( $ai ) . '</td>';
        echo '<td>' . aichatbotfree_render_rating( $rating ) . '</td>';
        echo '<td><a class="button secondary" href="' . esc_url( $link ) . '">Read Review</a></td>';
        echo '</tr>';
    }
}

/**
 * Output FAQ schema for posts when available.
 */
function aichatbotfree_output_post_schema() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    $post_id        = get_the_ID();
    $manual_schema  = aichatbotfree_get_field( 'schema_jsonld', $post_id, '' );
    $faq_items      = (array) aichatbotfree_get_field( 'faqs', $post_id, [] );
    $schema_payload = '';

    if ( ! empty( $manual_schema ) ) {
        $schema_payload = $manual_schema;
    } elseif ( ! empty( $faq_items ) ) {
        $entities = [];

        foreach ( $faq_items as $faq ) {
            $question = trim( wp_strip_all_tags( $faq['question'] ?? '' ) );
            $answer   = $faq['answer'] ?? '';

            if ( '' === $question || '' === $answer ) {
                continue;
            }

            $entities[] = [
                '@type'          => 'Question',
                'name'           => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_kses_post( $answer ),
                ],
            ];
        }

        if ( ! empty( $entities ) ) {
            $schema_payload = wp_json_encode(
                [
                    '@context'    => 'https://schema.org',
                    '@type'       => 'FAQPage',
                    'mainEntity'  => $entities,
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }

    if ( $schema_payload ) {
        echo '\n<!-- AI Chatbot Free FAQ Schema -->\n';
        echo '<script type="application/ld+json">' . $schema_payload . '</script>';
    }
}
add_action( 'wp_head', 'aichatbotfree_output_post_schema' );

