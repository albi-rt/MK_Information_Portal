<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package NSW_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the theme's favicon in the document <head>. Block (FSE) themes build
 * their <head> via wp_head() and never load header.php, so the favicon has to
 * be hooked here. If a WordPress Site Icon is set, WordPress emits its own
 * tags and we skip ours.
 */
add_action(
	'wp_head',
	function () {
		if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
			return;
		}
		/* Favicon set generated from the NSW brand mark (the window symbol from
		   the logo lockup — the wordmark is illegible below ~64px). The mark is
		   navy, so it sits on a white tile: a transparent background would make
		   it disappear on a dark browser tab. Each URL carries the theme version
		   so a rebrand actually reaches browsers, which cache icons hard. */
		$icons = array(
			array( 'rel' => 'icon',             'file' => 'favicon.ico',          'type' => 'image/x-icon', 'sizes' => 'any' ),
			array( 'rel' => 'icon',             'file' => 'favicon-32.png',       'type' => 'image/png',    'sizes' => '32x32' ),
			array( 'rel' => 'icon',             'file' => 'favicon-16.png',       'type' => 'image/png',    'sizes' => '16x16' ),
			array( 'rel' => 'apple-touch-icon', 'file' => 'apple-touch-icon.png', 'type' => '',             'sizes' => '180x180' ),
			array( 'rel' => 'icon',             'file' => 'favicon-192.png',      'type' => 'image/png',    'sizes' => '192x192' ),
			array( 'rel' => 'icon',             'file' => 'favicon-512.png',      'type' => 'image/png',    'sizes' => '512x512' ),
		);
		foreach ( $icons as $icon ) {
			printf(
				'<link rel="%s"%s sizes="%s" href="%s" />' . "\n",
				esc_attr( $icon['rel'] ),
				'' === $icon['type'] ? '' : ' type="' . esc_attr( $icon['type'] ) . '"',
				esc_attr( $icon['sizes'] ),
				esc_url( NSW_THEME_URI . 'assets/images/logos/' . $icon['file'] . '?v=' . NSW_THEME_VERSION )
			);
		}
	}
);

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'nsw-theme', NSW_THEME_DIR . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 185,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		/*
		 * Load the SAME front-end stylesheet into the block/site editor, plus a
		 * small editor-only override file. WordPress reads these files, rewrites
		 * their relative url() (fonts) to absolute, and scopes their selectors to
		 * .editor-styles-wrapper (rewriting :root/html/body to the wrapper) — so
		 * the editor renders from one source of truth and matches the front end.
		 * NOTE: do not @import main.css from editor.css — @import is dropped from
		 * the editor's injected inline styles, which is what broke parity before.
		 */
		add_editor_style(
			array(
				'assets/css/main.css',
				'assets/css/editor.css',
			)
		);


		add_image_size( 'nsw-theme-card', 800, 540, true );
		add_image_size( 'nsw-theme-hero', 1920, 1080, true );
	}
);

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'nsw-theme';
		$classes[] = 'lang-' . nsw_theme_current_locale();
		return $classes;
	}
);
