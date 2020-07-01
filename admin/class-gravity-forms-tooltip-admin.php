<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.facebook.com/disismehbub
 * @since      1.0.0
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/admin
 * @author     Mehbub Rashid <rashidiam1998@gmail.com>
 */
class Gravity_Forms_Tooltip_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gravity_Forms_Tooltip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gravity_Forms_Tooltip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'classic.min.css', plugin_dir_url( __FILE__ ) . 'css/classic.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'monolith.min.css', plugin_dir_url( __FILE__ ) . 'css/monolith.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'nano.min.css', plugin_dir_url( __FILE__ ) . 'css/nano.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gravity-forms-tooltip-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gravity_Forms_Tooltip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gravity_Forms_Tooltip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'pickr.min.js', plugin_dir_url( __FILE__ ) . 'js/pickr.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'pickr.es5.min.js', plugin_dir_url( __FILE__ ) . 'js/pickr.es5.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gravity-forms-tooltip-admin.js', array( 'jquery' ), $this->version, false );

	}

	function tooltip_input( $position, $form_id ) {
 
		//create settings on position 25 (right after Field Label)
		if ( $position == 25 ) {
			?>
			<li class="tooltip_input field_setting" style="display:list-item !important">
				<label for="tooltip_input" class="section_label">
					<?php esc_html_e( 'Tooltip Text', 'gravityforms' ); ?>
				</label>
				<input type="text" id="tooltip_input" onchange="SetFieldProperty('tooltiptext', this.value);" /> 
			</li>
			<?php
		}
	}

	function tooltip_editor_script(){
		?>
		<script type='text/javascript'>
			//adding setting to fields of type "text"
			fieldSettings.text += ', tooltip_input';
	 
			//binding to the load field settings event to initialize the values
			jQuery(document).on('gform_load_field_settings', function(event, field, form){
				jQuery('#tooltip_input').val(field['tooltiptext']);
				jQuery('.tooltip_input.field_setting').show();
			});
		</script>
		<?php
	}

	
	function render_tooltips( $content, $field, $value, $lead_id, $form_id ) {
		return str_replace( "class='gfield_label'", "class='gfield_label' data-tooltiptext='".$field->tooltiptext."'", $content );
	}

	function tooltip_update_checker() {
		if( get_transient( 'tooltip_update_checker' ) ){
			?>
			<div class="error is-dismissible"><p><?php echo __( 'New version of <strong>Tooltip for Gravity Forms is available!</strong> Update now to get new features', 'tooltip-for-gravity-forms' ); ?></p></div>
			<?php
		}
	}

	function set_updater_transient( $data, $response ) {
		if( isset( $data['update'] ) ) {
			set_transient( 'tooltip_update_checker', true);
		}
		else {
			delete_transient( 'tooltip_update_checker' );
		}
	}

	function detect_plugin_update() {
		if (get_option( 'tooltip_plugin_version' ) != GRAVITY_FORMS_TOOLTIP_VERSION) {
			//Plugin has been updated
			delete_transient( 'tooltip_update_checker' );
			update_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
		}
	}

	function auto_update_this_plugin ( $update, $item ) {
		// Array of plugin slugs to always auto-update
		$plugins = array (
			'tooltip-for-gravity-forms'
		);
		if ( in_array( $item->slug, $plugins ) ) {
			return true;
		} else {
			return $update;
		}
	}

	//Dashboard menu
	public function tooltip_settings_menu()
	{
		add_menu_page('Tooltip Settings', 'Tooltip Settings', 'manage_options', 'tooltip-settings', array($this, 'tooltip_settings_content'), 'dashicons-admin-comments', 11);
	}

	public function tooltip_settings_content() {
		if(isset($_POST['bgcolor'])) {
			echo $_POST['bgcolor'];
		}
		?>
			<div class="tooltip_settings_panel">
				<h1>Settings</h1>
				<div class="setting_inputs">
					<form action="" method="POST">
						<div>
							<label for="">Background color:</label>
							<input type="text" name="bgcolor" id="bgcolor">
							<div class="picker1"></div>
						</div>
						<div>
							<label for="">Text color:</label>
							<input type="text" name="txtcolor" id="txtcolor">
							<div class="picker2"></div>
						</div>
						<input type="submit" value="Save">
					</form>
				</div>
			</div>
			<script>
				var pickr1 = Pickr.create({
					el: '.picker1',
					theme: 'classic', // or 'monolith', or 'nano'
					default : '#111111',
					swatches: [
						'rgba(244, 67, 54, 1)',
						'rgba(233, 30, 99, 0.95)',
						'rgba(156, 39, 176, 0.9)',
						'rgba(103, 58, 183, 0.85)',
						'rgba(63, 81, 181, 0.8)',
						'rgba(33, 150, 243, 0.75)',
						'rgba(3, 169, 244, 0.7)',
						'rgba(0, 188, 212, 0.7)',
						'rgba(0, 150, 136, 0.75)',
						'rgba(76, 175, 80, 0.8)',
						'rgba(139, 195, 74, 0.85)',
						'rgba(205, 220, 57, 0.9)',
						'rgba(255, 235, 59, 0.95)',
						'rgba(255, 193, 7, 1)'
					],
				
					components: {
				
						// Main components
						preview: true,
						opacity: true,
						hue: true,
				
						// Input / output Options
						interaction: {
							hex: true,
							rgba: true,
							hsla: true,
							hsva: true,
							cmyk: true,
							input: true,
							clear: true,
							save: true
						}
					}
				});
				var pickr2 = Pickr.create({
					el: '.picker2',
					theme: 'classic', // or 'monolith', or 'nano'
					default : '#111111',
					swatches: [
						'rgba(244, 67, 54, 1)',
						'rgba(233, 30, 99, 0.95)',
						'rgba(156, 39, 176, 0.9)',
						'rgba(103, 58, 183, 0.85)',
						'rgba(63, 81, 181, 0.8)',
						'rgba(33, 150, 243, 0.75)',
						'rgba(3, 169, 244, 0.7)',
						'rgba(0, 188, 212, 0.7)',
						'rgba(0, 150, 136, 0.75)',
						'rgba(76, 175, 80, 0.8)',
						'rgba(139, 195, 74, 0.85)',
						'rgba(205, 220, 57, 0.9)',
						'rgba(255, 235, 59, 0.95)',
						'rgba(255, 193, 7, 1)'
					],
				
					components: {
				
						// Main components
						preview: true,
						opacity: true,
						hue: true,
				
						// Input / output Options
						interaction: {
							hex: true,
							rgba: true,
							hsla: true,
							hsva: true,
							cmyk: true,
							input: true,
							clear: true,
							save: true
						}
					}
				});
				pickr1.on('save', (color, instance) => {
					jQuery('#bgcolor').val(color.toHEXA().toString());
					pickr1.hide();
				});
				pickr2.on('save', (color, instance) => {
					jQuery('#txtcolor').val(color.toHEXA().toString());
					pickr2.hide();
				});
			</script>
		<?php
	}

}
