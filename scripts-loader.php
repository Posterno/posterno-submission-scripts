<?php
/**
 * Scripts and styles registration for the frontend listing submission form.
 *
 * @package     posterno
 * @copyright   Copyright (c) 2018, Pressmodo, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get js settings for the listings submission form.
 *
 * @return array
 */
function pno_get_listings_submission_form_js_vars() {

	$js_settings = [
		'selected_listing_type'   => isset( $_GET['listing_type_id'] ) && ! empty( sanitize_text_field( $_GET['listing_type_id'] ) ) ? absint( $_GET['listing_type_id'] ) : false, // phpcs: ignore
		'ajax'                    => admin_url( 'admin-ajax.php' ),
		'get_tags_nonce'          => wp_create_nonce( 'pno_get_tags_from_categories_for_submission' ),
		'get_starter_tags_nonce'  => wp_create_nonce( 'pno_get_tags' ),
		'get_subcategories_nonce' => wp_create_nonce( 'pno_get_subcategories' ),
		'days'                    => pno_get_days_of_the_week(),
		'is_editing_mode'         => is_page( pno_get_listing_editing_page_id() ),
		'editing_listing_id'      => is_page( pno_get_listing_editing_page_id() ) && isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : false,
	];

	return apply_filters( 'pno_listings_submission_form_js_vars', $js_settings );

}

/**
 * Load assets required for the frontend submission form.
 *
 * @return void
 */
function pno_load_listing_submission_form_assets() {

	if ( is_page( pno_get_listing_submission_page_id() ) || is_page( pno_get_listing_editing_page_id() ) ) {
		$version = PNO_VERSION;
		$is_dev  = defined( 'PNO_VUE_DEV' ) && PNO_VUE_DEV === true ? true : false;

		// Register Styles.
		wp_register_style( 'pno-submission-select2-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', false, $version );
		wp_register_style( 'pno-submission-flatpickr-style', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], $version );

		// Register Scripts.
		wp_register_script( 'pno-submission-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), $version, true );
		wp_register_script( 'pno-submission-flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', false, $version, true );

		if ( $is_dev ) {

			wp_register_script( 'pno-vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', [], $version, true );
			wp_register_script( 'pno-listing-submission-form', 'http://localhost:4001/app.js', [], $version, true );

		} else {

			wp_register_script( 'pno-vuejs', 'https://cdn.jsdelivr.net/npm/vue', [], $version, true );

		}

		// Load styles.
		wp_enqueue_style( 'pno-submission-select2-style' );
		wp_enqueue_style( 'pno-submission-flatpickr-style' );

		// Load scripts.
		wp_enqueue_script( 'pno-submission-select2' );
		wp_enqueue_script( 'pno-submission-flatpickr' );
		wp_enqueue_script( 'pno-vuejs' );
		wp_enqueue_script( 'pno-listing-submission-form' );

		wp_localize_script( 'pno-listing-submission-form', 'pno_submission', pno_get_listings_submission_form_js_vars() );
	}

}
add_action( 'wp_enqueue_scripts', 'pno_load_listing_submission_form_assets', 20 );
