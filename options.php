<?php

/**
 * Slidr Options page
 * 
 * @package Slidr
 * @since 1.0
 */
class Slidr_Options {
	
	private $sections;
	private $checkboxes;
	private $settings;
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
		
		$this->sections['settings']   	= __( 'Main Settings' , 'slidr' );
		$this->sections['gallery']    	= __( 'WordPress Gallery' , 'slidr' );
		$this->sections['defaults']    	= __( 'Slidr Defaults' , 'slidr' );
		$this->sections['about']     	= __( 'Documentation' , 'slidr' );
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'slidr_options' ) )
			$this->initialize_settings();
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_management_page( __( 'Slidr Options' , 'slidr' ), __( 'Slidr' , 'slidr' ), 'manage_options', 'slidr-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'slidr_scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => '',
			'para' => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'paragraph' => $para
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'slidr-options', $section, $field_args );
	}

	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h1>' . __( 'Slidr Options' , 'slidr' ) . '</h1>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Options updated.' , 'slidr' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'slidr_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' , 'slidr' ) . '" /></p>
		
	</form>';
	
	/* Check WordPress version and set the h2 or h3 tag depending on whether it is v. 4.4 and above or earlier 
	 * (fixes "Error: jQuery UI Tabs: Mismatching fragment identifier")
	*/
	global $wp_version;
	$htag = ( $wp_version >= 4.4 ) ? 'h2' : 'h3';

	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap ' . $htag . '").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("' . $htag . '").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap ' . $htag . ', .wrap table").show();
		
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
				$("form").attr("autocomplete", "off");
		});
	</script>
</div>';
		
	}
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {
		
		// This displays on the "About" tab. Echo regular HTML
		
	}
	
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'slidr_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4><p>' . $paragraph .'</p>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="slidr_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="slidr_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;

			case 'upload': // Media uploader (see: http://www.justinwhall.com/multiple-upload-inputs-in-a-wordpress-theme-options-page/)
					echo '<input id="' . $id . '" class="upload-url' . $field_class . '" type="text" name="slidr_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" /><input id="st_upload_button" class="st_upload_button" type="button" name="upload_button" value="' . __( 'Upload', 'slidr' ) . '" />';
				
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				echo '<div class="radio-container">';
				foreach ( $choices as $value => $label ) {
					echo '<div class="radio-option-container"><input class="radio' . $field_class . '" type="radio" name="slidr_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label></div>';
					// if ( $i < count( $options ) - 1 )
					// 	echo '<br />';
					// $i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span></div>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="slidr_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . format_for_editor( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="slidr_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="slidr_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {

		/*
			Set default values
		*/
		require dirname(__FILE__) . '/inc/gallery-defaults.php';
		
		/* Carousel settings
		===========================================*/

		$this->settings['settings'] = array(
			'section' => 'settings',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Appearance' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => '<div class="alert">' . __( 'You can add a carousel on any page you like with the <code>[slidr]</code> shortcode. To add the shortcode directly in your php code, use <code>&lt;?php echo do_shortcode( \'[slidr]\' ); ?&gt;</code>. The shortcode has many parameters that you can adjust like so: <code>[slidr parameter="value"]</code>. For a full list of the available options, please check the <strong>Documentation</strong> tab.' , 'slidr' ) . '</div>'
		);

			$this->settings['style'] = array(
				'section' => 'settings',
				'title'   => __( 'Carousel style' , 'slidr' ),
				'desc'    => __( 'Select the carousel style. If you want to style it yourself or have different style for each carousel you use, disable it.' , 'slidr' ),
				'type'    => 'select',
				'std'     => 'default',
				'choices' => array(
					'default' 	=> __( 'Default' , 'slidr' ),
					'none' 		=> __( 'Disabled (no style)' , 'slidr' )
				)
			);

			$this->settings['custom_css'] = array(
				'title'   => __( 'Custom CSS' , 'slidr' ),
				'desc'    => __( 'Add your custom CSS here. If you want to use an external stylesheet file, just leave this blank.' , 'slidr' ),
				'std'     => '',
				'type'    => 'textarea',
				'section' => 'settings'
			);

		$this->settings['nav'] = array(
			'section' => 'settings',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Navigation buttons' , 'slidr' ),
			'type'    => 'heading'
		);
			$this->settings['nav_prev'] = array(
				'section' => 'settings',
				'title'   => __( 'Previous' , 'slidr' ),
				'desc'    => __( 'Change the "previous" arrow. You can use text or custom fonts (e.g. Font Awesome).' , 'slidr' ),
				'type'    => 'text',
				'std'     => '&#8249;'
			);

			$this->settings['nav_next'] = array(
				'section' => 'settings',
				'title'   => __( 'Next' , 'slidr' ),
				'desc'    => __( 'Change the "next" arrow. You can use text or custom fonts (e.g. Font Awesome).' , 'slidr' ),
				'type'    => 'text',
				'std'     => '&#8250;'
			);

		$this->settings['cond_head'] = array(
			'section' 	=> 'settings',
			'title'   	=> '', // Not used for headings.
			'desc'    	=> __( 'Performance' , 'slidr' ),
			'type'    	=> 'heading',
			'para'		=> __( 'You dont\' need to load the plugins\' styles and scripts when they are not in use. If you want to load them on specific pages, select them below. To keep them called everywhere, leave all options un-checked. To further customize conditional loading, use the <code>slidr_conditional()</code> function as described on the <strong>Documentation</strong> tab.', 'slidr' )
		);
			$this->settings['cond_type'] = array(
				'section' => 'settings',
				'title'   => __( 'Condition' , 'slidr' ),
				'desc'    => __( 'Select whether the checked options below will get excluded (default) or included. In both cases, if none of the following checkboxes is checked, scripts will get called everywhere.' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'exclude',
				'choices' => array(
					'include' 	=> __( 'Include' , 'slidr' ),
					'exclude' 	=> __( 'Exclude' , 'slidr' )
				)
			);
			$this->settings['is_front_page'] = array(
				'section' => 'settings',
				'title'   => __( 'Front Page' , 'slidr' ),
				'desc'    => __( 'When on the Front Page (equals to <code>is_front_page()</code>).' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 0
			);
			$this->settings['is_home'] = array(
				'section' => 'settings',
				'title'   => __( 'Main Page' , 'slidr' ),
				'desc'    => __( 'When on the Main Page (equals to <code>is_home()</code>).' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 0
			);
			$this->settings['is_single'] = array(
				'section' => 'settings',
				'title'   => __( 'Single Posts' , 'slidr' ),
				'desc'    => __( 'When on single posts of any post type (except attachment and page post types).  (equals to <code>is_single()</code>).' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 0
			);
			$this->settings['is_page'] = array(
				'section' => 'settings',
				'title'   => __( 'Pages' , 'slidr' ),
				'desc'    => __( 'When on pages.  (equals to <code>is_page()</code>).' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 0
			);
			$this->settings['is_archive'] = array(
				'section' => 'settings',
				'title'   => __( 'Archives' , 'slidr' ),
				'desc'    => __( 'When on any type of Archive page. Category, Tag, other Taxonomy Term, custom post type archive, Author and Date-based pages are all types of Archives.  (equals to <code>is_archive()</code>).' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 0
			);

		/* Gallery Shortcode
		===========================================*/


		$this->settings['gallery_default'] = array( // Carousel container.
			'section' => 'gallery',
			'title'   => '',
			'desc'    => __( 'Slidr for Gallery' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'Replace the default WordPress gallery (<code>[gallery]</code> shortcode) with Slidr. Doing so you will be able to use the default gallery management mechanism of WordPress as usual and the output will be displayed in a Slidr carousel. In a typical use case, you wouldn\'t need to mess with shortcodes - just create a gallery via the WordPress editor as you would anyway and see the results.' , 'slidr' )
		);

			$this->settings['g_shortcode'] = array(
				'section' => 'gallery',
				'title'   => __( 'Use Slidr for Gallery' , 'slidr' ),
				'desc'    => __( 'Replace the default WordPress gallery with Slidr.' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => $g_enabled_def
			);
		
		$this->settings['gallery_settings'] = array( // Carousel container.
			'section' => 'gallery',
			'title'   => '',
			'desc'    => __( 'Default settings' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'Set the defaults for the <code>[gallery]</code> shortcode. Only works if <strong>Slidr for Gallery</strong> is enabled.' , 'slidr' )
		);
			$this->settings['g_height'] = array(
				'section' => 'gallery',
				'title'   => __( 'Carousel height' , 'slidr' ),
				'desc'    => __( 'Change the default (which is 150 pixels) height of the carousel.' , 'slidr' ),
				'type'    => 'text',
				'std'     => $g_height_def
			);
			$this->settings['g_size'] = array(
				'section' => 'gallery',
				'title'   => __( 'Thumbnail size' , 'slidr' ),
				'desc'    => __( 'Default is <code>thumbnail</code>. Other options usually include <code>medium</code>, <code>large</code> and <code>original</code>, but you can use your own registered sizes as well.' , 'slidr' ),
				'type'    => 'text',
				'std'     => $g_size_def
			);
			$this->settings['g_loader'] = array(
				'section' => 'gallery',
				'title'   => __( 'Show loader' , 'slidr' ),
				'desc'    => __( 'Show a "loading" animation untill all items are loaded. Uncheck to disable.' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => $g_loader_def
			);
			$this->settings['g_cycle'] = array(
				'section' => 'gallery',
				'title'   => __( 'Cycle items' , 'slidr' ),
				'desc'    => __( 'If enabled, when the carousel reaches it\'s first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. Auto scroll enables the cycling and scrolls it automatically.' , 'slidr' ),
				'type'    => 'select',
				'std'     => $g_cycle_def,
				'choices' => array(
					'no' 	=> __( 'Disabled' , 'slidr' ),
					'yes' 	=> __( 'Enabled' , 'slidr' ),
					'auto' 	=> __( 'Auto scroll' , 'slidr' )
				)
			);
			$this->settings['g_speed'] = array(
				'section' => 'gallery',
				'title'   => __( 'Autoscroll speed' , 'slidr' ),
				'desc'    => __( 'Autoscroll speed in miliseconds (1000ms = 1 second). Default is 4000 (4 seconds).' , 'slidr' ),
				'type'    => 'text',
				'std'     => $g_speed_def
			);
			$this->settings['g_nav'] = array(
				'section' => 'gallery',
				'title'   => __( 'Show navigation' , 'slidr' ),
				'desc'    => __( 'If you want to hide the navigation buttons, uncheck this.' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => $g_nav_def
			);
			$this->settings['g_info_box'] = array(
				'section' => 'gallery',
				'title'   => __( 'Show additional information (namely, title and caption) on mouseover.' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => $g_info_box_def,
				'choices' => array(
					'yes' 	=> __( 'yes (Show title and caption)' , 'slidr' ),
					'no' 	=> __( 'no (Do not show title and caption)' , 'slidr' )
				)
			);
			$this->settings['g_title'] = array(
				'section' => 'gallery',
				'title'   => __( 'Use caption as title if title is empty or same as item\'s filename.' , 'slidr' ),
				'desc'    => __( 'When you upload an image, WordPress gets its filename and displays it as the title. If Slidr finds that the title and filename are the same and if a caption exists, it uses the caption as the item]\'s title. Otherwise it will hide the infobox for the particular item. If for some reason you don\'t like that behaviour, you can disable it here.' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'Enabled' , 'slidr' ),
					'no' 	=> __( 'Disabled' , 'slidr' )
				)
			);
			$this->settings['g_excerpt'] = array(
				'section' => 'gallery',
				'title'   => __( 'Show caption.' , 'slidr' ),
				'desc'    => __( 'Default is "no".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => $g_excerpt_def,
				'choices' => array(
					'yes' 	=> __( 'yes (Show the caption)' , 'slidr' ),
					'no' 	=> __( 'no (Do not show the caption)' , 'slidr' )
				)
			);
			$this->settings['g_img_link'] = array(
				'section' => 'gallery',
				'title'   => __( 'Image link.' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => $g_img_link_def,
				'choices' => array(
					'yes' 	=> __( 'yes (Use image link)' , 'slidr' ),
					'no' 	=> __( 'no (Remove image link)' , 'slidr' )
				)
			);

		/* Slidr Defaults
		===========================================*/

		$this->settings['defaults'] = array(
			'section' => 'defaults',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Override defaults' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'Perhaps you don\'t intent to use many different types of carousel but instead you want to use it in a very specific way. For example, always get the same post type, or always use it as a simple image gallery etc. To avoid passing the same parameters everytime you use the shortcode, you can set the defaults below. That way, you can get the desired result with a simple <code>[slidr]</code>. Passing parameters for each shortcode is still valid and if you do so, it will override those defaults. The plugin takes advantage of the <code>WP_Query</code> class. If you are uncertain on which value you should put in each of the parameters below, you can check the respective documentation in the <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">Codex</a>.' , 'slidr' )
		);

		$this->settings['container_def'] = array( // Carousel container.
			'section' => 'defaults',
			'title'   => '',
			'desc'    => __( 'Basic options' , 'slidr' ),
			'type'    => 'heading'
		);
			$this->settings['height'] = array(
				'section' => 'defaults',
				'title'   => __( 'Carousel height' , 'slidr' ),
				'desc'    => __( 'Change the default (which is 150 pixels) height of the carousel.' , 'slidr' ),
				'type'    => 'text',
				'std'     => '150'
			);
			$this->settings['loader'] = array(
				'section' => 'defaults',
				'title'   => __( 'Show loader' , 'slidr' ),
				'desc'    => __( 'Show a "loading" animation untill all items are loaded. Uncheck to disable.' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 1
			);
			$this->settings['nav_cycle'] = array(
				'section' => 'defaults',
				'title'   => __( 'Cycle items' , 'slidr' ),
				'desc'    => __( 'If enabled, when the carousel reaches it\'s first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. Auto scroll enables the cycling and scrolls it automatically.' , 'slidr' ),
				'type'    => 'select',
				'std'     => 'no',
				'choices' => array(
					'no' 	=> __( 'Disabled' , 'slidr' ),
					'yes' 	=> __( 'Enabled' , 'slidr' ),
					'auto' 	=> __( 'Auto scroll' , 'slidr' )

				)
			);
			$this->settings['scroll_speed'] = array(
				'section' => 'defaults',
				'title'   => __( 'Autoscroll speed' , 'slidr' ),
				'desc'    => __( 'Autoscroll speed in miliseconds (1000ms = 1 second). Default is 4000 (4 seconds).' , 'slidr' ),
				'type'    => 'text',
				'std'     => '4000'
			);
			$this->settings['nav_buttons'] = array(
				'section' => 'defaults',
				'title'   => __( 'Show navigation' , 'slidr' ),
				'desc'    => __( 'If you want to hide the navigation buttons, uncheck this.' , 'slidr' ),
				'type'    => 'checkbox',
				'std'     => 1
			);

		$this->settings['gallery_def'] = array( // Gallery mode.
			'section' => 'defaults',
			'title'   => '', 
			'desc'    => __( 'Gallery mode' , 'slidr' ),
			'type'    => 'heading'
		);

			$this->settings['gallery'] = array(
				'section' => 'defaults',
				'title'   => __( 'Gallery mode' , 'slidr' ),
				'desc'    => __( 'Instead of posts, the Carousel can be used in "Gallery mode" displaying the images attached to the post in which you call it. By default it is disabled.' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'no',
				'choices' => array(
					'yes' 	=> __( 'yes (Enable gallery mode)' , 'slidr' ),
					'no' 	=> __( 'no (Disable gallery mode)' , 'slidr' )
				)
			);
			$this->settings['gallery_link'] = array(
				'section' => 'defaults',
				'title'   => __( 'Items link to:' , 'slidr' ),
				'desc'    => __( 'Whether the link should lead to the attachment page or the actual media file if gallery mode is enabled. Default is the Media file.' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'media',
				'choices' => array(
					'media' 		=> __( 'Media file' , 'slidr' ),
					'attachment' 	=> __( 'Attachment page' , 'slidr' )
				)
			);

		$this->settings['query_def'] = array( // The Query.
			'section' => 'defaults',
			'title'   => '', 
			'desc'    => __( 'The Query' , 'slidr' ),
			'type'    => 'heading'
		);

			$this->settings['type'] = array(
				'section' => 'defaults',
				'title'   => __( 'Post type' , 'slidr' ),
				'desc'    => __( 'Default is <code>post</code>.' , 'slidr' ),
				'type'    => 'text',
				'std'     => 'post'
			);
			$this->settings['number'] = array(
				'section' => 'defaults',
				'title'   => __( 'Items\' number' , 'slidr' ),
				'desc'    => __( 'Default is <code>10</code>' , 'slidr' ),
				'type'    => 'text',
				'std'     => '10'
			);
			$this->settings['category'] = array(
				'section' => 'defaults',
				'title'   => __( 'Category' , 'slidr' ),
				'desc'    => __( 'Default is disabled, to get all categories. To get items from a specific category, use the category ID. You can have multiple categories by adding IDs separated by comma, like so <code>1,2,3</code>.' , 'slidr' ),
				'type'    => 'text',
				'std'     => ''
			);
			$this->settings['orderby'] = array(
				'section' => 'defaults',
				'title'   => __( 'Order By' , 'slidr' ),
				'desc'    => __( 'Default is <code>date</code>.' , 'slidr' ),
				'type'    => 'text',
				'std'     => 'date'
			);
			$this->settings['order'] = array(
				'section' => 'defaults',
				'title'   => __( 'Order type' , 'slidr' ),
				'desc'    => __( 'Default is "DESC".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'DESC',
				'choices' => array(
					'DESC' 	=> __( 'DESC (Descending)' , 'slidr' ),
					'ASC' 	=> __( 'ASC (Ascending)' , 'slidr' )
				)
			);
			$this->settings['img_size'] = array(
				'section' => 'defaults',
				'title'   => __( 'Thumbnail size' , 'slidr' ),
				'desc'    => __( 'Default is <code>thumbnail</code>. Other options usually include <code>medium</code>, <code>large</code> and <code>original</code>, but you can use your own registered sizes as well.' , 'slidr' ),
				'type'    => 'text',
				'std'     => 'thumbnail'
			);

		$this->settings['content_def'] = array( // Item content.
			'section' => 'defaults',
			'title'   => '', 
			'desc'    => __( 'Item content' , 'slidr' ),
			'type'    => 'heading'
		);
			$this->settings['thumb'] = array(
				'section' => 'defaults',
				'title'   => __( 'Show thumbnails' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'yes (Show thumbnails)' , 'slidr' ),
					'no' 	=> __( 'no (Do not show thumbnails)' , 'slidr' )
				)
			);
			$this->settings['info_box'] = array(
				'section' => 'defaults',
				'title'   => __( 'Show additional information (namely, title and excerpt) on mouseover.' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'yes (Show title and excerpt)' , 'slidr' ),
					'no' 	=> __( 'no (Do not show title and excerpt)' , 'slidr' )
				)
			);
			$this->settings['smart_title'] = array(
				'section' => 'defaults',
				'title'   => __( 'Use caption as title if title is empty or same as item\'s filename.' , 'slidr' ),
				'desc'    => __( 'When you upload an image, WordPress gets its filename and displays it as the title. If Slidr finds that the title and filename are the same and if a caption exists, it uses the caption as the item]\'s title. Otherwise it will hide the infobox for the particular item. If for some reason you don\'t like that behaviour, you can disable it here.' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'Enabled' , 'slidr' ),
					'no' 	=> __( 'Disabled' , 'slidr' )
				)
			);
			$this->settings['excerpt'] = array(
				'section' => 'defaults',
				'title'   => __( 'Show excerpt.' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'yes (Show the excerpt)' , 'slidr' ),
					'no' 	=> __( 'no (Do not show the excerpt)' , 'slidr' )
				)
			);
			$this->settings['img_link'] = array(
				'section' => 'defaults',
				'title'   => __( 'Image link.' , 'slidr' ),
				'desc'    => __( 'Default is "yes".' , 'slidr' ),
				'type'    => 'radio',
				'std'     => 'yes',
				'choices' => array(
					'yes' 	=> __( 'yes (Use image link)' , 'slidr' ),
					'no' 	=> __( 'no (Remove image link)' , 'slidr' )
				)
			);

		/* Documentation
		===========================================*/
		
		$this->settings['doc_params'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Available parameters' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __('The plugin takes advantage of the <code>WP_Query</code> class. If you are uncertain on which value you should put in each of the parameters below, you can check its documentation in the <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">Codex</a>.', 'slidr' ) . '<ol class="doc_list">' . __( '
					<li><code>[slidr height="some_number"]</code> : Set the height for the specific carousel, overriding the defaults like so: <code>[slidr height="200"]</code>. That way you can have carousels of different sizes in different pages of your website.</li>
					<li><code>[slidr loader="no"]</code> : Shows a "loading" animation until all items are loaded. By default it is enabled.</li>
					<li><code>[slidr cycle="yes"]</code> : If enabled, when the carousel reaches its first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. With <code>[slidr cycle="auto"]</code> you can enable autoscoll, which animates the carousel automatically every 4 seconds.</li>
					<li><code>[slidr speed="4000"]</code> : Set the autoscroll speed in miliseconds. Default value is 4000ms (4 seconds). This option works only if "cycle" parameter, mentioned above, is set to "auto".</li>
					<li><code>[slidr nav="hide"]</code> : Completely hides the navigation buttons.</li>
					<li><code>[slidr gallery="yes"]</code> : Instead of posts, the Carousel can be used in "Gallery mode" displaying the images attached to the post in which you call it. By default it is disabled. You can use gallery mode with specific images, by providing the IDs of those images like so: <code>[slidr gallery="1,2,3"]</code>. If gallery mode is enabled, then other conflicting parameters such as post type or hide thumbnail will be ignored.</li>
					<li><code>[slidr gallery_link="attachment"]</code> : Whether each item\'s link should lead to the attachment page or the actual media file if gallery mode is enabled. Default is the Media file. If gallery mode is set to "no" (disabled), then this setting is ignored.</li>
					<li><code>[slidr type="post_type"]</code> : The post type whose items will be displayed in the carousel (e.g. <code>[slidr type="post"]</code> for posts, <code>[slidr type="page"]</code> for pages etc.). Default value is "post".</li>
					<li><code>[slidr number=some_number]</code> : The number of items to be displayed. Pay attention to the lack of quotation marks (e.g. <code>[slidr number=15]</code>). Default value is 10.</li>
					<li><code>[slidr category="category_id"]</code> : The category from which you want to get your items. You should use the categorie\'s ID like <code>[slidr category="2"]</code>. By default this option is disabled, to get all categories.</li>
					<li><code>[slidr parent="parent_page_id"]</code> : Display the children of a page. You should use the page\'s ID like <code>[slidr parent="2"]</code>. By default this option is disabled.</li>
					<li><code>[slidr sticky="yes"]</code> : By default the plugin doesn\'t care about whether a post is sticky. To only show sticky posts, though, use <code>[slidr sticky="yes"]</code>).</li>
					<li><code>[slidr orderby="date"]</code> : The items\' order. Default order is by date.</li>
					<li><code>[slidr order="ASC"]</code> : Whether the order will be ascending (ASC) or descentind (DESC). Default is "DESC" (descending). If the gallery mode is enabled and is set to get specific images, then this order option gets ignored and the images are ordered based on how the user added the image IDs in his/her shortcode.</li>
					<li><code>[slidr size="thumbnail"]</code> : The thumbnail size, based on the registered sizes of your theme. Default value is "thumbnail". Other options usually include "medium", "large" and "original".</li>
					<li><code>[slidr thumb="no"]</code> : If you need the carousel to display posts without thumbnails, you can completely disable images. Default value is enabled, of course.</li>
					<li><code>[slidr info_box="no"]</code> : By default each item shows a box with the title and excerpt on mouseover or tap. With this option you can disable it.</li>
					<li><code>[slidr excerpt="no"]</code> : Hide the excerpt from the info box.</li>
					<li><code>[slidr img_link="no"]</code> : Remove the link from each image.</li>
					<li><code>[slidr class="yourclass"]</code> : If you have carousels in many different pages, there is a chance that you want to style them separately. With this option you can add a custom class at the carousel\'s outer container and customize it using CSS.</li>
					<li><code>[slidr link="none"]</code> : Removes the link from the title (in case you want to show the details of an image but not link to its attachment page or media file).</li>
					<li><code>[slidr template="no"]</code> : Removes the default template for the specific carousel. That way you can keep the default template for all other carousel instances but remove it for those you wish to style differently.</li>
				' , 'slidr' ) . '</ol>'
		);
		$this->settings['doc_gallery'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Replace default WordPress gallery' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'By enabling <strong>"Use Slidr for Gallery"</strong> under the <strong>"WordPress Gallery"</strong> tab, you can replace the default WordPress gallery ([gallery] shortcode) with Slidr. Doing so you will be able to use the default gallery management mechanism of WordPress as usual and the output will be displayed in a Slidr carousel. In a typical use case, you wouldn\'t need to mess with shortcodes - just create a gallery via the WordPress editor as you would anyway and see the results. For example, you can order set the order via the Gallery Management screen, set the titles and captions of each image and define whether clicking on images should get to the media file, attachment page or do nothing. Keep in mind that in those cases the outputted shortcode is not [slidr] but <strong>[gallery]</strong> and that many of the aforementioned parameters for the [slidr] shortcode don\'t make sense in a gallery context and won\'t work there. Those which do work are the following:', 'slidr' ) . '<ol class="doc_list">' . __( '
					<li><code>[gallery height="some_number"]</code> : Set the height of the gallery.</li>
					<li><code>[gallery loader="no"]</code> : Shows a "loading" animation until all items are loaded.</li>
					<li><code>[gallery cycle="yes"]</code> : If enabled, when the carousel reaches its first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. With <code>[gallery cycle="auto"]</code> you can enable autoscoll, which animates the carousel automatically every 4 seconds.</li>
					<li><code>[gallery speed="4000"]</code> : Set the autoscroll speed in miliseconds. Default value is 4000ms (4 seconds). This option works only if "cycle" parameter, mentioned above, is set to "auto".</li>
					<li><code>[gallery nav="hide"]</code> : Completely hides the navigation buttons.</li>
					<li><code>[gallery orderby="menu_order ID"]</code> : The items\' order. Default order is by the order passed at the media manager.</li>
					<li><code>[gallery order="ASC"]</code> : Whether the order will be ascending (ASC) or descentind (DESC). Default is "ASC" (ascending).</li>
					<li><code>[gallery size="thumbnail"]</code> : The thumbnail size, based on the registered sizes of your theme. Default value is "thumbnail". Other options usually include "medium", "large" and "original".</li>
					<li><code>[gallery thumb="no"]</code> : If you need the carousel to display posts without thumbnails, you can completely disable images. Default value is enabled, of course.</li>
					<li><code>[gallery info_box="no"]</code> : By default each item shows a box with the title and excerpt on mouseover or tap. With this option you can disable it.</li>
					<li><code>[gallery excerpt="no"]</code> : Hide the excerpt from the info box.</li>
					<li><code>[gallery img_link="no"]</code> : Remove the link from each image.</li>
					<li><code>[gallery class="yourclass"]</code> : If you have carousels in many different pages, there is a chance that you want to style them separately. With this option you can add a custom class at the carousel\'s outer container and customize it using CSS.</li>
					<li><code>[gallery link="none"]</code> : Removes the link from the title (in case you want to show the details of an image but not link to its attachment page or media file).</li>
					<li><code>[gallery template="no"]</code> : Removes the default template for the specific carousel. That way you can keep the default template for all other carousel instances but remove it for those you wish to style differently.</li>
			' , 'slidr' )
		);
		$this->settings['doc_combine'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Combinations and alternatives' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'You can combine almost all of the above parameters to customize your query. For example, <code>[slidr type="portfolio" height="200" number=5 category="5" size="medium" excerpt="no" class="myworks" cycle="auto" speed="2000"]</code> should create a carousel of 200 pixels height which would display the five most recent items from your "portfolio" custom post type, AND under a specific category with the id of "5". Items\' thumbnails should use the "medium" size and no excerpts should be displayed. Finally, this carousel should autoscroll its items every 2000ms (2 seconds) and it should have a custom class "myworks".<br/><br/>If you want to add your carousel directly in your php code, you can use the <code>&lt;?php echo do_shortcode( \'[slidr]\' ); ?&gt;</code> function, setting parameters the same way as previously described.' , 'slidr' )
		);
		$this->settings['doc_infobox'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Customizing infobox\'s data' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'Perhaps you might want to alter the default display of the title and excerpt in the infobox. In that case, you can override the output by adding the following function in your theme\'s functions.php:<br/><br/><code>&lt;?php function slidr_custom_content( $link, $title, $excerpt, $a ) { <br/>echo \'Your custom output here\'; <br/> } ?&gt;</code><br/><br/>You can call each item\'s link, title and excerpt using the <code>$link</code>, <code>$title</code> and <code>$excerpt</code> variables respectively inside your function (in the above example you should use it inside your echo). If you have more than one slidr shortcodes with different attributes for each one, you can run tests using the <code>$a</code> variable. For example, to test if gallery mode is disabled: <br/><br/>
				<code>&lt;?php function slidr_custom_content( $link, $title, $excerpt, $a ) { <br/>
					if($a[\'gallery\'] !== \'yes\') { <br/>
						// Do something <br/>
					}<br/>
				} ?&gt;</code>'  , 'slidr' )
		);
		$this->settings['doc_defaults'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Overriding default values' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'If you are a theme developer, you can pass your own default values for the [gallery] shortcode directly in your theme. That way you don\'t need to instruct your users to go and set the appropriate values at the Plugin\'s options or ask them to mess with the shortcode. You can use it like this:<br/><br/>
				<code>&lt;?php include_once( ABSPATH . \'wp-admin/includes/plugin.php\' );<br/>
				if ( is_plugin_active( \'slidr/slidr.php\' ) ) {<br/>
					function slidr_gallery_defaults() {<br/>
						$default[\'enable\'] 		= \'yes\'; 	// Enable or disable Slidr for Gallery option by default <br/>
						$default[\'height\'] 		= \'500\'; 	// The gallery height <br/>
						$default[\'size\'] 		= \'medium\'; // Thumbnail size <br/>
						$default[\'speed\'] 		= \'4000\'; 	// Carousel speed if "cycle" mode is set to "auto" <br/>
						$default[\'info_box\'] 	= \'yes\'; 	// Show or hide infobox <br/>
						$default[\'excerpt\'] 	= \'no\'; 	// Show or hide excerpt <br/>
						$default[\'loader\'] 		= \'yes\'; 	// Use loading animation <br/>
						$default[\'nav\'] 		= \'show\'; 	// Show or hide navigation buttons <br/> 
						$default[\'cycle\'] 		= \'no\'; 	// Enable or disable "cycle" mode (options are "yes", "no" and "auto") <br/>
						$default[\'template\'] 	= \'no\'; 	// Disable the default template <br/>
						$default[\'class\']		= \'myclass\' // Pass your class to the container <br/>
						$default[\'link_class\'] 	= \'myclass\' // Pass class to the link <br/>
						$default[\'img_link\'] 	= \'no\' 		// Enable or disable image link <br/>
						
						return $default; <br/>
					}<br/>
				} ?&gt;</code>'  , 'slidr' )
		);
		$this->settings['doc_conditional'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Customizing conditional loading' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'To further customize conditional loading of the plugin\'s resources, put <br/><br/><code>&lt;?php function slidr_conditional() { <br/>if( YOUR_CONDITIONS ) { <br/>slidr_dequeue(); <br/>}<br/> } ?&gt;</code> <br/><br/>in your theme\'s <strong>functions.php</strong> file. For details on <strong>Conditional Tags</strong> check the <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">Codex</a>.'  , 'slidr' )
		);
		$this->settings['doc_widgets'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Slidr in widgets' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'If you want to add a carousel in your WordPress Sidebar Widgets, you need to enable shortcodes in your WordPress sidebar. To do so, paste <code>add_filter(\'widget_text\', \'do_shortcode\');</code> in your <strong>functions.php</strong> file. Then all you have to do is go to your <strong>Appearance » Widgets</strong> Screen and create a <strong>text widget</strong>. There you can paste <code>[slidr]</code> (or any shortcode that you have enabled on your site for that matter), and it will function properly.'  , 'slidr' )
		);
		$this->settings['doc_custom_loop'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Custom loop' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'The plugin contains the <code>slidr_loop()</code> function, which allows you to use your own custom loop instead of the plugin\'s parameters. That means that you can keep all the existing functionality of the carousel, but the content with which you will fill it is entirely up to you. For example, you could create a carousel displaying videos, instagram photos or any other content that you wish to format in a particular way. Your custom loop can be a typical foreach() php loop and the only thing you need to do is adding the <code>slidr-item</code> class to the container of each of your loop\'s items. Then, you call the function like this: <code>slidr_loop(\'your_loop_function\')</code> Here is a simple example which shows some youtube videos in a carousel:<br/><br/>
				<code>
					function my_loop() {<br/>
						$myloop = array(\'n64TMQH0rxg\', \'JwYX52BP2Sk\', \'Ahr4KFl79WI\', \'yFAnn2j4iB0\', \'6fMnF0Fvdpo\', \'dFh71_ftxLE\');<br/>
						$output = \'\';<br/>
						foreach ($myloop as $item) {<br/>
							$output .= \'&lt;div class="slidr-item" style="height: 290px;max-height: none;"&gt;
								&lt;iframe width="420" height="315" src="https://www.youtube.com/embed/\' . $item . \'" frameborder="0" allowfullscreen&gt;
								&lt;/iframe&gt;&lt;/div&gt;\';<br/>
						}<br/>
						return $output;<br/>
					}
				</code><br/><br/>
				After creating the above loop, you would call it like that: <code>echo slidr_loop(\'my_loop\');</code>. If you want to customize the plugin\'s parameters, you have the following options available:<br/><br/>
				<code>slidr_loop($loop, $height, $class, $cycle, $loader, $nav, $larrow, $rarrow)</code>. Here\'s a detailed explanation of each parameter:<br/><br/>
				<ol>
					<li><code>$loop</code>: Accepts a function (your custom loop). It is the only required parameter in order for you to have a functional carousel.</li>
					<li><code>$height</code>: Accepts a string with a number. (e.g. \'500\'). It sets the carousel\'s height.</li>
					<li><code>$class</code>: Aceepts a string with your custom classes (e.g. \'class-1 class-2\'). If left empty, it defaults to \'default\'. </li>
					<li><code>$cycle</code>: Options include: true, false, \'auto\', \'SPEED_NUMBER_IN_MS\'. true enables cycling, \'auto\' enables cycling AND auto cycles the slides, \'SPEED_NUMBER_IN_MS\' (e.g. \'2000\') enables cycling AND ayto cycles the slides AND sets your custom cycling speed in ms. </li>
					<li><code>$loader</code>: Options include: true, false, \'Your custom text\'. true enables the loader, false disables it and a custom string (e.g. \'Loading slider...\') enables the loader AND sets a custom text message. </li>
					<li><code>$nav</code>: If set to true or left empty it shows the navigation arrows. If set to false it hides them.</li>
					<li><code>$larrow</code>: Accepts a string to change the default left arrow. You can add html, font icons, plain text or special characters.</li>
					<li><code>$rarrow</code>: Accepts a string to change the default right arrow. You can add html, font icons, plain text or special characters. </li>
				</ol>
				A full example would be something like that: <br/><br/>
				<code>&lt;?php echo slidr_loop(\'my_loop\', \'500\', \'myclass-1 myclass-2\', \'2000\', \'Loading videos...\', true, \'previous\', \'next\'); ?&gt;</code><br/><br/>
				The above would set the container height to 500px, add two custom classes, set auto cycle with a speed of 2000ms, change the loader text and change the navigation arrows.
				'  , 'slidr' )
		);
		$this->settings['doc_version'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Minimum requirements' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'This plugin has been initially tested with Wordpress 4.1 and above. It might probably work with older versions too, but you should always use the latest version of Wordpress anyway.'  , 'slidr' )
		);
		$this->settings['doc_report'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'I found a bug / I have a suggestion or a feature request.' , 'slidr' ),
			'type'    => 'heading',
			'para'	  => __( 'You can post a message at the plugin\'s <a target="_blank" href="http://wordpress.org/support/plugin/slidr">support forum</a> and I will to my best to help you out.'  , 'slidr' )
		);
	}

	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = isset($setting['std']) ? $setting['std'] : '';
		}
		
		update_option( 'slidr_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'slidr_options', 'slidr_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'slidr-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'slidr-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*
	* @since 1.0
	*/
	public function slidr_scripts() {
		
		wp_enqueue_script(array('jquery','jquery-ui-tabs'));

	}
	
	/**
	* Styling for the plugin options page
	*
	* @since 1.0
	*/
	public function styles() {
		
		wp_register_style( 'slidr-admin', plugins_url( 'css/slidr-options.css' , __FILE__ ) );
		wp_enqueue_style( 'slidr-admin' );
		
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_plugin'] ) ) {
			$options = get_option( 'slidr_options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		return false;
		
	}
	
}

$slidr_options = new Slidr_Options();