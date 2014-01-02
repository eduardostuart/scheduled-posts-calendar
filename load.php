<?php

/*
  Plugin Name: Scheduled Post Calendar
  Plugin URI: http://eduardostuart.com.br/scheduled-post-calendar
  Description: Show a calendar with scheduled posts (wp-admin)
  Version: 2.4.3
  Author: Eduardo Stuart
  Author URI: http://eduardostuart.com.br
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

define('SCHEDULED_POST_CALENDAR_VERSION', '2.4.3');
define('SCHEDULED_POST_CALENDAR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SCHEDULED_POST_CALENDAR_LANG', 'scheduled-posts-calendar');
define('SCHEDULED_DIR', dirname(__FILE__) . '/');


load_plugin_textdomain(SCHEDULED_POST_CALENDAR_LANG, false, dirname(plugin_basename(__FILE__)) . '/lang/');

 

$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('n');
$year  = isset($_REQUEST['year'])  ? $_REQUEST['year']  : date('Y');


require_once 'admin/ScheduledCalendar.php';

$ScheduledCalendar = new ScheduledCalendar($month, $year);

require_once 'admin/functions.php';
require_once 'inc/front-shortcodes.php';


add_action('admin_init', array($ScheduledCalendar, 'init'));
add_action('admin_menu', array($ScheduledCalendar, 'addOptionsMenu'));
add_action('add_meta_boxes', 'scheduledpostscalendar_metabox');

add_shortcode( 'scheduled_posts' , 'shortcode_scheduled_posts' );