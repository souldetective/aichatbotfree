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
                    'key'   => 'field_hero_subheading',
                    'label' => 'Hero Subheading',
                    'name'  => 'hero_subheading',
                    'type'  => 'textarea',
                    'rows'  => 3,
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
                    ],
                ],
                [
                    'key'   => 'field_trust_copy',
                    'label' => 'Trust Block Copy',
                    'name'  => 'trust_copy',
                    'type'  => 'textarea',
                    'rows'  => 4,
                    'instructions' => 'Explain testing process, affiliate transparency, and review objectivity.',
                ],
            ],
            'location'              => [
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
}

/**
 * Helper to render the comparison table rows.
 */
function aichatbotfree_render_comparison_rows( $items, $type = 'free' ) {
    if ( empty( $items ) ) {
        return;
    }

    foreach ( $items as $item ) {
        $tool      = $item['tool'];
        $plan      = $type === 'free' ? ( $item['free_plan'] ?? '' ) : ( $item['price'] ?? '' );
        $channels  = $item['channels'] ?? '';
        $ai        = $item['ai_support'] ?? '';
        $link      = $tool ? get_permalink( $tool ) : '';
        $tool_name = $tool ? get_the_title( $tool ) : '';
        echo '<tr>';
        echo '<td>' . esc_html( $tool_name ) . '</td>';
        echo '<td>' . esc_html( $plan ) . '</td>';
        echo '<td>' . esc_html( $channels ) . '</td>';
        echo '<td>' . esc_html( $ai ) . '</td>';
        echo '<td><a class="button secondary" href="' . esc_url( $link ) . '">Read Review</a></td>';
        echo '</tr>';
    }
}

