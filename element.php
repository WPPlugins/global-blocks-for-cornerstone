<?php
/**
* Build Cornerstone Element
*
* @package   Global Blocks for Cornerstone
* @author    Michael Bourne
* @license   GPL3
* @link      https://xthemetips.com
* @since     1.1.2
*/


/**
*  Register our element
*/

add_action('cornerstone_register_elements', 'global_blocks_register_elements');
function global_blocks_register_elements()
{
	cornerstone_register_element('Global_Blocks', 'global-blocks', GLOBAL_BLOCKS_PATH . 'includes/global-blocks');
}

/**
* Icon made by Gregor Cresnar from www.flaticon.com and is licensed by CC 3.0 BY
* Register SVG icon in Cornerstone
*/

add_filter('cornerstone_icon_map', 'global_blocks_icon_map');
function global_blocks_icon_map($icon_map)
{
	$icon_map['global-blocks'] = GLOBAL_BLOCKS_URL . 'assets/svg/global-block.svg';
	return $icon_map;
}

/**
* Retrieve custom CSS and JS
*/

function global_block_get_cornerstone_page_settings( $post_id ) {
	if ( function_exists( 'cs_get_serialized_post_meta' ) ) {
		$settings = cs_get_serialized_post_meta( $post_id, '_cornerstone_settings', true );
		return ( is_array( $settings ) ) ? $settings : array();
	}
}

/**
* Create shortcode to use block outside of CS
* Usage: [global_block block="123"]
*/

function global_blocks_output($atts) {

	if ( !empty($atts['block']) && is_string( get_post_status( $atts['block']) ) ) {
		$content  = get_post_field('post_content', $atts['block'], 'raw');
		$custom   = global_block_get_cornerstone_page_settings( $atts['block'] );

		ob_start();

		echo do_shortcode($content);

		if ( apply_filters( '_cornerstone_custom_css', isset( $custom['custom_css'] ) ) && !empty($custom['custom_css']) ) {
			echo '<style class="global-block-custom-page-css" type="text/css">';
			echo $custom['custom_css'];
			echo '</style>';
		}
		if ( apply_filters( '_cornerstone_custom_page_js', true ) && !empty($custom['custom_js_mini']) ){
			echo '<script class="global-block-custom-page-js" type="text/javascript">';
			echo $custom['custom_js_mini'];
			echo '</script>';
		}

		$finalblock = ob_get_clean();

		return $finalblock;
	} else {
		return '<div style="text-align: center; border: 1px dotted #333;"><h2 class="mvl">' . __('Select your global block.', 'global-blocks-cornerstone') . '</h2></div>';
	}
}
function global_blocks_register_shortcode() {
	add_shortcode('global_block', 'global_blocks_output');
}
add_action( 'init', 'global_blocks_register_shortcode');