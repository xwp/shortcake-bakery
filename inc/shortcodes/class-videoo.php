<?php

namespace Shortcake_Bakery\Shortcodes;

class Videoo extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Videoo', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/png/icon-videoo.png' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to the Videoo', 'shortcake-bakery' ),
				),
			),
		);
	}

	/**
	 * Render the shortcode on-demand
	 *
	 * @param array $attrs
	 * @param string $content
	 */
	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'videoo.com' !== parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		$parts = explode( '?', $attrs['url'] );
		$url = array_shift( $parts );
		$url = add_query_arg( 'embed', 1, $url );
		$ret = '<script src="' . esc_url( $url ) . '"></script>';
		$ret .= <<<EOT
<style type="text/css">
/** Updated Fix for iphone 6 and 6 plus **/
@media(max-width:480px) { div.videooContainer, iframe.videoo-widget-player { width: 100vw !important; max-width: 100% !important; } }
@media(min-width:374px) and (max-width:374px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 374px !important } }
@media(min-width:375px) and (max-width:375px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 375px !important } }
@media(min-width:376px) and (max-width:376px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 376px !important } }
@media(min-width:413px) and (max-width:413px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 413px !important } }
@media(min-width:414px) and (max-width:414px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 414px !important } }
@media(min-width:415px) and (max-width:415px) { #videooWidget, .videoo-widget, iframe.videoo-widget-player { width: 415px !important } }
</style>
EOT;
		return $ret;
	}

	public static function reversal( $content ) {

		if ( false === stripos( $content, '<script' ) ) {
			return $content;
		}

		if ( preg_match_all( '#<script src="https://videoo\.com/([^\"]+)"></script>#', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$parts = explode( '?', $matches[1][ $key ] );
				$url = sprintf( 'https://videoo.com/%s', $parts[0] );
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

}
