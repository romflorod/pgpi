<?php
/**
 * Plugin Name: Lucky Wheel for WooCommerce
 * Description: Collect customers emails by letting them play interesting Lucky wheel game to get lucky discount coupon
 * Version: 1.0.8.2
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: woo-lucky-wheel
 * Domain Path: /languages
 * Copyright 2018 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 5.8
 * WC requires at least: 4.0
 * WC tested up to: 5.5
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
define( 'VI_WOO_LUCKY_WHEEL_VERSION', '1.0.8.2' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-lucky-wheel/woocommerce-lucky-wheel.php' ) ) {
	return;
}
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-lucky-wheel" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "includes.php";
	require_once $init_file;
}

if ( ! class_exists( 'Woo_Lucky_Wheel' ) ):
	class Woo_Lucky_Wheel {
		protected $settings;

		public function __construct() {
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				add_action( 'admin_notices', array( $this, 'notification' ) );

				return;
			}

			$this->settings = VI_WOO_LUCKY_WHEEL_DATA::get_instance();
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'create_custom_post_type' ) );
			add_filter( 'manage_wlwl_email_posts_columns', array( $this, 'add_column' ), 10, 1 );
			add_action( 'manage_wlwl_email_posts_custom_column', array( $this, 'add_column_data' ), 10, 2 );
			add_filter(
				'plugin_action_links_woo-lucky-wheel/woo-lucky-wheel.php', array(
					$this,
					'settings_link'
				)
			);
		}
		public function settings_link( $links ) {
			$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=woo-lucky-wheel" title="' . __( 'Settings', 'woo-lucky-wheel' ) . '">' . __( 'Settings', 'woo-lucky-wheel' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		public function create_custom_post_type() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( post_type_exists( 'wlwl_email' ) ) {
				return;
			}
			$args = array(
				'labels'              => array(
					'name'               => _x( 'Lucky Wheel Email', 'woo-lucky-wheel' ),
					'singular_name'      => _x( 'Email', 'woo-lucky-wheel' ),
					'menu_name'          => _x( 'Emails', 'Admin menu', 'woo-lucky-wheel' ),
					'name_admin_bar'     => _x( 'Emails', 'Add new on Admin bar', 'woo-lucky-wheel' ),
					'view_item'          => __( 'View Email', 'woo-lucky-wheel' ),
					'all_items'          => __( 'Email Subscribe', 'woo-lucky-wheel' ),
					'search_items'       => __( 'Search Email', 'woo-lucky-wheel' ),
					'parent_item_colon'  => __( 'Parent Email:', 'woo-lucky-wheel' ),
					'not_found'          => __( 'No Email found.', 'woo-lucky-wheel' ),
					'not_found_in_trash' => __( 'No Email found in Trash.', 'woo-lucky-wheel' )
				),
				'description'         => __( 'Lucky Wheel for WooCommerce emails.', 'woo-lucky-wheel' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_in_menu'        => false,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
			);
			register_post_type( 'wlwl_email', $args );
		}

		public function add_column( $columns ) {
			$columns['customer_name'] = __( 'Customer name', 'woo-lucky-wheel' );
			$columns['spins']         = __( 'Number of spins', 'woo-lucky-wheel' );
			$columns['last_spin']     = __( 'Last spin', 'woo-lucky-wheel' );
			$columns['label']         = __( 'Labels', 'woo-lucky-wheel' );
			$columns['coupon']        = __( 'Coupons', 'woo-lucky-wheel' );

			return $columns;
		}

		public function add_column_data( $column, $post_id ) {
			switch ( $column ) {
				case 'customer_name':
					if ( get_post( $post_id )->post_content ) {
						echo get_post( $post_id )->post_content;
					}
					break;
				case 'spins':
					if ( get_post_meta( $post_id, 'wlwl_spin_times', true ) ) {
						echo get_post_meta( $post_id, 'wlwl_spin_times', true )['spin_num'];
					}
					break;
				case 'last_spin':
					if ( get_post_meta( $post_id, 'wlwl_spin_times', true ) ) {
						echo date( 'Y-m-d h:i:s', get_post_meta( $post_id, 'wlwl_spin_times', true )['last_spin'] );
					}
					break;

				case 'label':
					if ( get_post_meta( $post_id, 'wlwl_email_labels', true ) ) {
						$label = get_post_meta( $post_id, 'wlwl_email_labels', true );
						if ( sizeof( $label ) > 1 ) {
							for ( $i = sizeof( $label ) - 1; $i >= 0; $i -- ) {
								echo '<p>' . $label[ $i ] . '</p>';
							}
						} else {
							echo $label[0];
						}
					}
					break;
				case 'coupon':
					if ( get_post_meta( $post_id, 'wlwl_email_coupons', true ) ) {
						$coupon = get_post_meta( $post_id, 'wlwl_email_coupons', true );
						if ( sizeof( $coupon ) > 1 ) {
							for ( $i = sizeof( $coupon ) - 1; $i >= 0; $i -- ) {
								echo '<p>' . $coupon[ $i ] . '</p>';
							}
						} else {
							echo $coupon[0];
						}
					}
					break;
			}
		}

		function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'woo-lucky-wheel' );
			load_textdomain( 'woo-lucky-wheel', WP_PLUGIN_DIR . "/woocommerce-lucky-wheel/languages/woocommerce-lucky-wheel-$locale.mo" );
			load_plugin_textdomain( 'woo-lucky-wheel', false, basename( dirname( __FILE__ ) ) . "/languages" );
			if ( class_exists( 'VillaTheme_Support' ) ) {
				new VillaTheme_Support(
					array(
						'support'   => 'https://wordpress.org/support/plugin/woo-lucky-wheel/',
						'docs'      => 'http://docs.villatheme.com/?item=woocommerce-lucky-wheel',
						'review'    => 'https://wordpress.org/support/plugin/woo-lucky-wheel/reviews/?rate=5#rate-response',
						'pro_url'   => 'https://1.envato.market/qXBNY',
						'css'       => VI_WOO_LUCKY_WHEEL_CSS,
						'image'     => VI_WOO_LUCKY_WHEEL_IMAGES,
						'slug'      => 'woo-lucky-wheel',
						'menu_slug' => 'woo-lucky-wheel',
						'version'   => VI_WOO_LUCKY_WHEEL_VERSION
					)
				);
			}
		}

		function notification() {
			?>
            <div id="message" class="error">
                <p><?php _e( 'Please install and activate WooCommerce to use Lucky Wheel for WooCommerce.', 'woo-lucky-wheel' ); ?></p>
            </div>
			<?php
		}
	}
endif;

new Woo_Lucky_Wheel();
