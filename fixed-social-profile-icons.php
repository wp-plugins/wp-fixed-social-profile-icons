<?php
/*
Plugin Name: WP Fixed Social Profile Icons
Plugin URI: http://amansaini.me
Description: Add the Social profile icons fixed on right or left side of page.
Version: 1.0
Author: Aman Saini
Author URI:  http://amansaini.me/
License: GPLv2 or later
*/
// don't load directly
if ( !defined( 'ABSPATH' ) ) die( '-1' );

class FixedSocialIcons {
	function __construct() {
		// We safely integrate our hooks

		add_action( 'admin_menu', array( $this, 'add_fsi_menu' ) );
		add_action( 'admin_init', array( $this, 'regsiter_fsi_setting_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this,  'wp_enqueue_color_picker' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_settings_link' ), 10, 2 );

		add_action( 'wp_enqueue_scripts',  array( $this, 'add_style_sheet' ) );
		add_action ( 'wp_footer',  array( $this, 'add_icons_to_page' ) );
		add_action ( 'wp_head' , array( $this, 'add_icon_links' ) );

	}


	function add_style_sheet() {

		wp_enqueue_style( 'fsistyle', plugins_url( 'fsi.css', __FILE__ ) );
	}



	function add_icons_to_page() {

		$setting = get_option( 'fsi_settings' );

		$social_names = array( 'facebook', 'google', 'twitter', 'linkedin', 'youtube', 'tumblr', 'pinterest', 'instagram' );

		$position = $setting['icons_position'];

?>

		<div class="fsi-icons fsi-social-icons-<?php echo $position; ?>">
		<?php
		foreach ( $social_names as $name ) {
			if ( $setting['enable_'.$name] ) {
?>
				<a href="<?php echo $setting[$name.'_link']; ?>" class="<?php echo $name; ?>i7 i8<?php echo $position; ?>"></a>

		<?php }
		} ?>

		</div>
		<?php
	}


	function add_icon_links() {

		$setting = get_option( 'fsi_settings' );

		$social_names = array( 'facebook', 'google', 'twitter', 'linkedin', 'youtube', 'tumblr', 'pinterest', 'instagram' );
?>
		<style>
		<?php
		foreach ( $social_names as $name ) {
			if ( $setting['enable_'.$name] ) { ?>
		<?php echo '.'.$name; ?>i7{
				background-image:url( "<?php echo $setting[$name] ?>");
				background-color:<?php echo $setting[$name.'_color']; ?>;
				}
			<?php
			}
		}


?>
div.fsi-social-icons-right,div.fsi-social-icons-left{
	margin-top:<?php echo $setting['top_margin'] ?>
}
		</style>
				<style>
				div.fsi-icons{
				_position:absolute;
				}
				div.fsi-social-icons{
				_bottom:auto;_top:
				expression(ie6=(document.documentElement.scrollTop+document.documentElement.clientHeight - 52+"px") );
				}
		</style>

		<style>
				.i8right:hover, .i8right:active, .i8right:focus{
				outline:0;
				right:-10px;
				width:60px;
				transform: rotate(-11deg);-ms-transform: rotate(-11deg);-webkit-transform: rotate(-11deg);
				}

				.i8left:hover, .i8left:active, .i8left:focus{
				outline:0;
				left:-26px;
				width:60px;
				transform: rotate(11deg);-ms-transform: rotate(11deg);-webkit-transform: rotate(11deg);
				}
				</style>


	<?php }

	function add_fsi_menu() {
		add_menu_page( 'Fixed Social Icons Settings', 'Fixed Social Icons ', 'manage_options', 'fsi_settings', array( $this, 'add_fsi_setting_fields' ) );

		// If the social options don't exist, create them.
		if ( false == get_option( 'fsi_settings' ) ) {


			$defaults =  array(
				'icons_position'=>"right",
				'top_margin'=>"-250px",
				'facebook'=>  plugins_url( 'img/facebook-icon.png', __FILE__ ),
				'twitter'=>  plugins_url( 'img/twitter-icon.png', __FILE__ ),
				'google'=>  plugins_url( 'img/google-icon.png', __FILE__ ),
				'linkedin'=>  plugins_url( 'img/linkedin-icon.png', __FILE__ ),
				'youtube'=>  plugins_url( 'img/youtube-icon.png', __FILE__ ),
				'tumblr'=>  plugins_url( 'img/tumblr-icon.png', __FILE__ ),
				'pinterest'=>  plugins_url( 'img/pinterest-icon.png', __FILE__ ),
				'instagram'=>  plugins_url( 'img/instagram-icon.png', __FILE__ ),
				'facebook_color'=>'#4a6ea9',
				'google_color'=>'#e25e43',
				'twitter_color'=>'#30dcf3',
				'linkedin_color'=>'#0095bc',
				'youtube_color'=>'#e44840',
				'tumblr_color'=>'#375775',
				'pinterest_color'=>'#D53330',
				'instagram_color'=>'#D4C6AA',
			);

			add_option( 'fsi_settings', $defaults );

		}
	}

	function add_fsi_setting_fields() { ?>
  <!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<!-- Add the icon to the page -->
		<div id="icon-themes" class="icon32"></div>
		<h2>Fixed Social Icons Settings</h2>

		<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
		<?php

		settings_errors(); ?>

		<!-- Create the form that will be used to render our options -->
		<form method="post" action="options.php">
			<?php settings_fields( 'fsi_settings' ); ?>
			<?php do_settings_sections( 'fsi_settings' ); ?>
			<?php submit_button(); ?>
		</form>

		<script type="text/javascript">

		jQuery(function(){

			jQuery('.color-pick').wpColorPicker();
			//Hide empty link fields on load
			jQuery('.icon-links').each(function(){
				var id= jQuery(this).attr('id');
				var type = id.replace("_link", "");

			var check = '#enable_'+type;

			if( ! jQuery(check).is(':checked')){
				jQuery(this).parent().parent().hide();
			}

			})

			// show hide link on click of checkbox
			 jQuery('.fsi-icon-check').click(function(){

				if(jQuery(this).is(':checked')){
					jQuery(this).parent().parent().next().show('slow')
				}else{
					jQuery(this).parent().parent().next().hide('slow')
				}
			 })


		})
		</script>

	</div><!-- /.wrap -->

<?php
	}

	function wp_enqueue_color_picker() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}


	function regsiter_fsi_setting_fields() {

		add_settings_section( 'fsi_general_setting', '',  array( $this, 'fsi_general_setting_callback' ), 'fsi_settings' );

		add_settings_field( 'icons_position', 'Icons Position',  array( $this, 'icons_position' ), 'fsi_settings', 'fsi_general_setting', array( 'icons_position' ) );
		add_settings_field( 'top_margin', 'Top Margin',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'top_margin' ) );
		//  Fields
		add_settings_field( 'facebook_icon', 'Facebook Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'facebook' ) );
		add_settings_field( 'facebook_link', 'Facebook Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'facebook' ) );
		add_settings_field( 'google_icon', 'Google Plus Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'google' ) );
		add_settings_field( 'google_link', 'Google Plus Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'google' ) );
		add_settings_field( 'twitter_icon', 'Twitter Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'twitter' ) );
		add_settings_field( 'twitter_link', 'Twitter Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'twitter' ) );
		add_settings_field( 'linkedin_icon', 'LinkedIn Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'linkedin' ) );
		add_settings_field( 'linkedin_link', 'LinkedIn Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'linkedin' ) );
		add_settings_field( 'youtube_icon', 'Youtube Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'youtube' ) );
		add_settings_field( 'youtube_link', 'Youtube Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'youtube' ) );
		add_settings_field( 'tumblr_icon', 'Tumblr Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'tumblr' ) );
		add_settings_field( 'tumblr_link', 'Tumblr Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'tumblr' ) );
		add_settings_field( 'pinterest_icon', 'Pinterest Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'pinterest' ) );
		add_settings_field( 'pinterest_link', 'Pinterest Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'pinterest' ) );
		add_settings_field( 'instagram_icon', 'Instagram Icon',  array( $this, 'add_icons' ), 'fsi_settings', 'fsi_general_setting', array( 'instagram' ) );
		add_settings_field( 'instagram_link', 'Instagram Link',  array( $this, 'add_links' ), 'fsi_settings', 'fsi_general_setting', array( 'instagram' ) );

		register_setting( 'fsi_settings', 'fsi_settings' );
	}

	function fsi_general_setting_callback() {

		echo 'For icons use 48x48 size.';
	}

	function icons_position( $args ) {
		$setting = get_option( 'fsi_settings' );


		// Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
		$html = '<select id="' . $args[0] . '"  name="fsi_settings[' . $args[0] . ']" />';

		$html.='<option value="left" '.selected( $setting[$args[0]], 'left', false ).'>Left</option>';
		$html.='<option value="right" '.selected( $setting[$args[0]], 'right', false ).'>Right</option>';

		$html.='</select>';
		echo $html;
	}

	function add_icons( $args ) {

		$setting = get_option( 'fsi_settings' );
		if (  $args[0] != 'top_margin' ) {
			// Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
			$html = '<input class="regular-text" type="text" value="'.$setting[$args[0]].'" id="' . $args[0] . '"  name="fsi_settings[' . $args[0] . ']" />';

			// We also access the show_header element of the options collection in the call to the checked() helper function
			$html .= '<input type="checkbox" class="fsi-icon-check" id="enable_' . $args[0] . '" name="fsi_settings[enable_' . $args[0] . ']" value="1" ' . checked( 1, $setting["enable_".$args[0]], false ) . '/> ';
			$html .= '<label for="' . $args[0] . '"> Enable</label>';
		}else {
			$html = '<input class="" type="text" value="'.$setting[$args[0]].'" id="' . $args[0] . '"  name="fsi_settings[' . $args[0] . ']" />';
		}

		echo $html;
	}

	function add_links( $args ) {

		$setting = get_option( 'fsi_settings' );

		// Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
		$html = '<input class="regular-text icon-links " type="text" value="'.$setting[$args[0].'_link'].'" id="' . $args[0] . '_link"  name="fsi_settings[' . $args[0] . '_link]" />';
		$html .= '<div style="margin-top:10px;"> <label style="vertical-align: top;margin-top: 3px;display: inline-block;" for="' . $args[0] . '_color"> Background-color:</label>';
		$html .= '<input class="color-pick " type="text" value="'.$setting[$args[0].'_color'].'" id="' . $args[0] . '_color"  name="fsi_settings[' . $args[0] . '_color]" />';
		$html .= '</div>';
		echo $html;
	}


	function add_plugin_settings_link( $links, $file ) {


		if ( $file == plugin_basename( __FILE__ ) ) {

			$settings_link = sprintf( '<a href="%s"> %s </a>', admin_url( 'admin.php?page=fsi_settings' ), __( 'Settings', 'plugin_domain' ) );
			array_unshift( $links, $settings_link );
		}


		return $links;

	}

}
// Finally initialize code
new FixedSocialIcons();
