<?php
namespace Automattic\WooCommerce\Blocks\BlockTypes;

use WC_Shortcode_Cart;
use WC_Shortcode_Checkout;
use WC_Frontend_Scripts;

/**
 * Classic Shortcode class
 *
 * @internal
 */
class ClassicShortcode extends AbstractDynamicBlock {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'classic-shortcode';

	/**
	 * API version.
	 *
	 * @var string
	 */
	protected $api_version = '2';

	/**
	 * Render method for the Classic Template block. This method will determine which template to render.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block content.
	 * @param WP_Block $block      Block instance.
	 * @return string | void Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		if ( ! isset( $attributes['shortcode'] ) ) {
			return;
		}

		/**
		 * We need to load the scripts here because when using block templates wp_head() gets run after the block
		 * template. As a result we are trying to enqueue required scripts before we have even registered them.
		 *
		 * @see https://github.com/woocommerce/woocommerce-gutenberg-products-block/issues/5328#issuecomment-989013447
		 */
		if ( class_exists( 'WC_Frontend_Scripts' ) ) {
			$frontend_scripts = new WC_Frontend_Scripts();
			$frontend_scripts::load_scripts();
		}

		if ( 'cart' === $attributes['shortcode'] ) {
			return $this->render_cart();
		}

		if ( 'checkout' === $attributes['shortcode'] ) {
			return $this->render_checkout();
		}

		return "You're using the ClassicShortcode block";
	}

	/**
	 * Render method for rendering the cart shortcode.
	 *
	 * @return string Rendered block type output.
	 */
	protected function render_cart() {
		if ( ! isset( WC()->cart ) ) {
			return '';
		}

		ob_start();

		echo '<div class="wp-block-group">';
		WC_Shortcode_Cart::output( array() );
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Render method for rendering the checkout shortcode.
	 *
	 * @return string Rendered block type output.
	 */
	protected function render_checkout() {
		if ( ! isset( WC()->cart ) ) {
			return '';
		}

		ob_start();

		echo '<div class="wp-block-group">';
		WC_Shortcode_Checkout::output( array() );
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Get the frontend style handle for this block type.
	 *
	 * @return null
	 */
	protected function get_block_type_style() {
		return null;
	}
}
