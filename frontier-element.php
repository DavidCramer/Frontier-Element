<?php
/**
 * Plugin Name: Pods Frontier Element
 * Plugin URI:  
 * Description: Example to show how to create Frontier elements
 * Version:     1.000
 * Author:      David Cramer
 * Author URI:  
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// if this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Add our element to the list of available elements
add_filter('frontier_get_element_types', 'my_frontier_element');

// add the headers action function
/*
 * Process headers
 * This runs before any wp_head so wp_enqueue_style & wp_enqueue_script can be used here
*/
function my_element_headers($element){	
	// Use the globals for setting header inline styles & scripts.
	global $frontier_styles, $frontier_scripts;

}

// add the render function
/*
 * This is the output of out element.
*/
function my_element_renderer($code, $element, $atts, $content){

	// this is a filter so $code contains the output from the last run.
	// this allows you to modify another element to some extent.
	// Be sure to return your output as it will be pushed to the do_shortcode for enbeded shortcodes.

	$out = '<p>My test value in <code>$element[\'group_slug\'][\'field_slug\']</code> is: '. $element['group_slug']['field_slug'].'</p>';

	if(isset($element['settings']['second_tab'])){

		$out .= '<p>The result of my second tab: found in <code>$element[\'settings\'][\'second_tab\']</code> fields based panel is:</p>';
		$out .= '<pre>';
		ob_start();
		print_r($element['settings']['second_tab']);
		$out .= ob_get_clean();
		$out .= '</pre>';
		
	}

	return $out;

}

function my_frontier_element($elements){
	
	$path = plugin_dir_path(__FILE__);

	// add out elements processors in
	add_action('frontier_element_headers-my_frontier_element', 'my_element_headers');
	add_filter('frontier_render_element-my_frontier_element', 'my_element_renderer', 10, 4);		



	$elements['my_frontier_element'] = array(
		"name"          =>  "My Cool Element", // the name of out element
		"setup"     =>  array( // defines the setup structures
			"scripts"	=>  array(
				"jquery",  // specify the handle of the library to be included
				plugin_dir_url(__FILE__) . "assets/js/element-setup.js",  // we can add as many additional libraries as needed by URL or handle
			),
			"styles"	=>  array(
				plugin_dir_url(__FILE__) . "assets/css/element-setup.css",  // specify the handle or URL of the library to be included
			),
			"tabs"      =>  array( // sets the tab areas - 1 tab per config panel
				"core"	=> array(), // defines core based tabs (first level - still to be implemented)
				"groups" => array( // defines group based tabs (second level)
					"first_tab" => array(
						"name" => "First Tab",
						"label" => "Heading of the first tab",
						"actions" => array(), // additional file actions can be included - mainly buttons - still working on it.						
						"canvas" => $path . "ui/my-element-canvas.php", // specify a path to a file to load as the main ui
						"side_panel" => $path . "ui/my-element-side.php", // specify a path to a file to load as the side bar ui (optional)
						"repeat" => false, // set this tab to be repeatable ( only if fields are used not canvas based)
					),
					"second_tab" => array(
						"name" => "Second Tab",
						"label" => "The Second Tab - Repeatable",
						"repeat" => true, // set to repeatable
						"fields" => array( // array of fields - the whole group is repeated
							"my_first_field" => array(
								"label" => "First Field", // the field label
								"slug" => "my_first_field", // slug the value is stored as
								"caption" => "My first field to be captured", // tool tip help ( to be completed )
								"type" => "single_line_field", // Field type to use see pods-frontier/fields for types
								"config" => array( // configure the defaults / options 
									"default" => "First Value - default",
								),
							),
							"my_second_field" => array(
								"label" => "Second Field", // the field label
								"slug" => "my_second_field", // slug the value is stored as
								"caption" => "My second field as dropdown", // tool tip help ( to be completed )
								"type" => "dropdown", // Field type to use see pods-frontier/fields for types
								"config" => array( // configure the defaults / options 
									"default" => "maybe",
									"option" => array(
										array(
											'value' => 'ok',
											'label' => 'OKAY'
										),
										array(
											'value' => 'maybe',
											'label' => 'Maybe'
										),
										array(
											'value' => 'no',
											'label' => 'Nope!'
										),
									)
								),
							),
						),
					),
				),
			),
		),
	);

	return $elements;   
}