=== Scheduled Posts Calendar ===
Tags: scheduled,schedule,future posts,calendar,admin,metabox,posts metabox
Requires at least: 3.2
Tested up to: 3.4
Stable tag: 2.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show a calendar with scheduled posts

== Description ==

[PHP5 is required since version 2.70]

This plugin shows a Calendar (on admin area) with scheduled posts. 


== Installation ==

You can either install it automatically from the WordPress admin, or do it manually:

1. Unzip the archive and put the `scheduledcalendar` folder into your plugins folder (/wp-content/plugins/).
1. Activate the plugin from the Plugins menu.

= Usage =

Go to **Wp-Admin -> Scheduled Calendar** to see the calendar.

= Shortcodes =

To show the list of *scheduled posts* on your site, use the shortcode: **[scheduled_posts]**.

**Optional Attributes:**


**To show Scheduled posts Title use:**

[scheduled_posts display_title=true] 


**To change the Title format and title value use:**


[scheduled_posts display_title=true title_format="your format %s here" title="My Super title"]

**To change the Item Title format use:**

[scheduled_posts item_title_format="your item format %s here"]

**If you want to add your own css class, use 'container_class' attribute**

[scheduled_posts container_class="my_super_class"]

**If you want to show scheduled posts for another date use:**
*This example will show posts from month = september and year = 2014*

[scheduled_posts month="9" year="2014"]

== Screenshots ==

1. Scheduled Calendar (no posts)
2. Scheduled Calendar (one or more posts)
3. Scheduled Posts - Edit or Add new Post

== Changelog ==

= 2.4 =

* bugfix
* shortcode to show sheduled posts
* security fix


= 2.3.1 =

* bugfix
* new screenshot

= 2.3 =

* bugfix

= 2.2 =

* added a metabox with scheduled posts

= 2.1.2 =

* wordpress banner

= 2.1.1 =

* removed unused scripts

= 2.1 =

* bugfix: load_plugin_textdomain problem
* language files for pt_BR added

= 2.0 =

* Names of days of the week 