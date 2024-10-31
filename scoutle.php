<?php

/*
Plugin Name: Scoutle
Version: 3.2
Plugin URI: http://www.scoutle.com/
Description: Displays your Scoutle Stage which will make sure your Scout keeps walking while promoting your blog and finding other interesting blogs for you.
Author: Scoutle.com, Godfried van Loo
Author URI: http://www.scoutle.com/
*/

/*  
		Check scoutle.com to see if new Stages are available
		and to make sure you have the latest plugin version.

/*  Copyright 2008  Scoutle | Godfried van Loo

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define('MAGPIE_CACHE_AGE', 120);

$scoutle_options['widget_fields']['stagehash'] = array('label'=>'<b>Scout Hash</b>:', 'type'=>'text', 'default'=>'');
$scoutle_options['widget_fields']['stagetype'] = array('label'=>'<b>Stage Type</b> (dynamic180 / dynamic125 / classic / mini / static):', 'type'=>'text', 'default'=>'classic');

$scoutle_options['prefix'] = 'scoutle';

// Display Scoutle stage
function scoutle_stage($stagehash,$stagetype) {
	global $scoutle_options;
	if($stagetype == "mini") {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=mini&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}
	elseif($stagetype == "static") {
		print '<div style="text-align:center;"><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'"><img src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=static&amp;host=wordpressself" border="0" title="Connect with me at Scoutle.com" alt="Connect with me at Scoutle.com" /></a></div>';
	}
	elseif($stagetype == "classic") {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=classic&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}
	elseif($stagetype == "dynamic125") {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=dynamic125&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}
	elseif($stagetype == "dynamic 125") {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=dynamic125&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}
	elseif($stagetype == "dynamic-125") {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=dynamic125&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}
	else {
		print '<script type="text/javascript" src="http://www.scoutle.com/stageload/loadv2.php?stash='.$stagehash.'&amp;st=dynamic180&amp;host=wordpressself"></script><noscript><a href="http://www.scoutle.com/incoming/index.php?stash='.$stagehash.'" target="_blank">Connect with me at Scoutle.com</a></noscript>';
	}

}

// Scoutle widget stuff
function widget_scoutle_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;
	
	$check_options = get_option('widget_scoutle');
  if ($check_options['number']=='') {
    $check_options['number'] = 1;
    update_option('widget_scoutle', $check_options);
  }
  
	function widget_scoutle($args, $number = 1) {

		global $scoutle_options;
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		include_once(ABSPATH . WPINC . '/rss.php');
		$options = get_option('widget_scoutle');
		
		// fill options with default values if value is not set
		$item = $options[$number];
		foreach($scoutle_options['widget_fields'] as $key => $field) {
			if (! isset($item[$key])) {
				$item[$key] = $field['default'];
			}
		}
		

		// These lines generate our output.
		echo $before_widget;
  	scoutle_stage($item['stagehash'], $item['stagetype']);
  	echo $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_scoutle_control($number) {
	
		global $scoutle_options;

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_scoutle');
		if ( isset($_POST['scoutle-submit']) ) {

			foreach($scoutle_options['widget_fields'] as $key => $field) {
				$options[$number][$key] = $field['default'];
				$field_name = sprintf('%s_%s_%s', $scoutle_options['prefix'], $key, $number);

				if ($field['type'] == 'text') {
					$options[$number][$key] = strip_tags(stripslashes($_POST[$field_name]));
				} elseif ($field['type'] == 'checkbox') {
					$options[$number][$key] = isset($_POST[$field_name]);
				}
			}

			update_option('widget_scoutle', $options);
		}

		foreach($scoutle_options['widget_fields'] as $key => $field) {
			
			$field_name = sprintf('%s_%s_%s', $scoutle_options['prefix'], $key, $number);
			$field_checked = '';
			if ($field['type'] == 'text') {
				$field_value = htmlspecialchars($options[$number][$key], ENT_QUOTES);
			} elseif ($field['type'] == 'checkbox') {
				$field_value = 1;
				if (! empty($options[$number][$key])) {
					$field_checked = 'checked="checked"';
				}
			}
			
			printf('<p style="text-align:right;" class="scoutle_field"><label for="%s">%s <input id="%s" name="%s" type="%s" value="%s" class="%s" %s /></label></p>',
				$field_name, __($field['label']), $field_name, $field_name, $field['type'], $field_value, $field['type'], $field_checked);
		}

		echo '<input type="hidden" id="scoutle-submit" name="scoutle-submit" value="1" />';
	}
	
	function widget_scoutle_setup() {
		$options = $newoptions = get_option('widget_scoutle');
		
		if ( isset($_POST['scoutle-number-submit']) ) {
			$number = (int) $_POST['scoutle-number'];
			$newoptions['number'] = $number;
		}
		
		if ( $options != $newoptions ) {
			update_option('widget_scoutle', $newoptions);
			widget_scoutle_register();
		}
	}
		
	function widget_scoutle_register() {
		
		$options = get_option('widget_scoutle');
		$dims = array('width' => 300, 'height' => 300);
		$class = array('classname' => 'widget_scoutle');

		for ($i = 1; $i <= 9; $i++) {
			$name = sprintf(__('Scoutle'), $i);
			$id = "scoutle-$i"; // Never never never translate an id
			wp_register_sidebar_widget($id, $name, $i <= $options['number'] ? 'widget_scoutle' : /* unregister */ '', $class, $i);
			wp_register_widget_control($id, $name, $i <= $options['number'] ? 'widget_scoutle_control' : /* unregister */ '', $dims, $i);
		}
		
		add_action('sidebar_admin_setup', 'widget_scoutle_setup');
	}

	widget_scoutle_register();
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_scoutle_init');
?>