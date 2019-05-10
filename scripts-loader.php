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
 * Load assets required for the frontend submission form.
 *
 * @return void
 */
function pno_load_listing_submission_form_assets() {

	if ( ! is_page( pno_get_listing_submission_page_id() ) ) {
		return;
	}

	$version = PNO_VERSION;
	$is_dev  = defined( 'PNO_VUE_DEV' ) && PNO_VUE_DEV === true ? true : false;

	if ( $is_dev ) {

		wp_register_script( 'pno-listing-submission-form', 'http://localhost:4001/app.js', [], $version, true );

	} else {

	}

	wp_enqueue_script( 'pno-listing-submission-form' );

}
add_action( 'wp_enqueue_scripts', 'pno_load_listing_submission_form_assets' );
