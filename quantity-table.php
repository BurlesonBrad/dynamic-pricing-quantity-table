<?php

/*
Plugin Name: Woocommerce Quantity Table
Plugin URI: https://riotweb.nl
Description: Extension for Dynamic Pricing plugin that shows a table with price and quantity.
Author: RiotWeb
Version: 1.0
Author URI: https://riotweb.nl/plugins
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


add_filter( 'woocommerce_get_price_html', 'omniwp_credit_dollars_price', 10, 2 );
			
	function omniwp_credit_dollars_price( $price, $product ) {
		$pricing_rule_sets = get_post_meta( $product->post->ID, '_pricing_rules', true );
		$pricing_rule_sets = array_shift( $pricing_rule_sets );
		 
		if ( $pricing_rule_sets 
			&& is_array( $pricing_rule_sets ) 
			&& sizeof( $pricing_rule_sets ) ) {
		ob_start();
		?>

<table>
  <thead>
    <tr>
      <th><?php _e('Quantity', 'omniwp_core_functionality' ) ?></th>
      <th><?php _e('Price', 'omniwp_core_functionality' ) ?></th>
    </tr>
  </thead>
  <?php
				foreach ( $pricing_rule_sets['rules'] as $key => $value ) {
					if ( '*' == $pricing_rule_sets['rules'][$key]['to'] ) {
		?>
  <tr>
    <td><?php printf( __( '%s - %s', 'omniwp_core_functionality' ) , $pricing_rule_sets['rules'][$key]['from'] )  ?></td>
    <td><?php echo woocommerce_price( $pricing_rule_sets['rules'][$key]['amount'] ); ?></td>
  </tr>
  <?php
					} else {
		?>
  <tr>
    <td><?php printf( __( '%s - %s', 'omniwp_core_functionality' ) , $pricing_rule_sets['rules'][$key]['from'], $pricing_rule_sets['rules'][$key]['to'] )  ?></td>
    <td><?php echo woocommerce_price( $pricing_rule_sets['rules'][$key]['amount'] ); ?></td>
  </tr>
  <?php
  					}
				}
?>
</table>
<?php		
				$price = ob_get_clean();
			} 
			return $price;
		}
}

add_action( 'plugins_loaded', 'woocommerce_quantity_table_textdomain' );