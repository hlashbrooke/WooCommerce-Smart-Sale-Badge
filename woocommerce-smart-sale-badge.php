<?php
/*
 * Plugin Name: WooCommerce Smart Sale Badge
 * Version: 1.0
 * Plugin URI: http://wordpress.org/extend/plugins/woocommerce-smart-sale-badge/
 * Description: Enhances the WooCommerce sale badge by displaying the total saving a customer will receive
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 3.0
 * Tested up to: 4.0
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( 'classes/class-woocommerce-smart-sale-badge.php' );

global $wc_smart_sale_badge;
$wc_smart_sale_badge = new WooCommerce_Smart_Sale_Badge( __FILE__ );