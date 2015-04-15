<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WooCommerce_Smart_Sale_Badge {
	private $dir;
	private $file;

	public function __construct( $file ) {
		$this->dir = dirname( $file );
		$this->file = $file;

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		add_filter( 'woocommerce_sale_flash', array( &$this, 'badge_sale_amount' ), 10, 3 );
	}

	public function badge_sale_amount( $message, $post, $product ) {

		$saving_amount = 0;

		if ( $product->has_child() ) {

			// Loop through children if this is a variable product
			foreach ( $product->get_children() as $child_id ) {

				$regular_price = get_post_meta( $child_id, '_regular_price', true );
				$sale_price = get_post_meta( $child_id, '_sale_price', true );

				if( $regular_price != '' && $sale_price != '' && $regular_price > $sale_price ) {
					$new_saving_amount = $regular_price - $sale_price;

					// Only display the largest saving amount
					if( $new_saving_amount > $saving_amount ) {
						$saving_amount = $new_saving_amount;
					}
				}

			}

			$button_text = apply_filters("wc_smart_sale_badge_title", __( 'Save up to', 'wc_smart_sale_badge' ), $product->has_child(), $product);

		} else {

			// Fetch prices for simple products
			$regular_price = get_post_meta( $post->ID, '_regular_price', true );
			$sale_price = get_post_meta( $post->ID, '_sale_price', true );

			if( $regular_price != '' && $sale_price != '' && $regular_price > $sale_price ) {
				$saving_amount = $regular_price - $sale_price;
			}

			$button_text = apply_filters("wc_smart_sale_badge_title", __( 'Save', 'wc_smart_sale_badge' ), $product->has_child(), $product);

		}

		// Only modify badge if saving amount is larger than 0
		if( $saving_amount > 0 ) {
			$saving_price = woocommerce_price( $saving_amount );
			$saving_price_r = apply_filters("wc_smart_sale_badge_price", sprintf( __( ' %s!', 'wc_smart_sale_badge' ), $saving_price ), $saving_price, $product);
			$message = '<span class="onsale">' . $button_text . $saving_price_r . '</span>';
		}

		return $message;
	}

	public function load_localisation() {
		load_plugin_textdomain( 'wc_smart_sale_badge' , false , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	public function load_plugin_textdomain() {
	    $domain = 'wc_smart_sale_badge';

	    $locale = apply_filters( 'plugin_locale' , get_locale() , $domain );

	    load_textdomain( $domain , WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain , FALSE , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

}
