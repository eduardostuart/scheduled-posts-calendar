<?php

defined('ABSPATH') || die;


function shortcode_scheduled_posts( $atts ){   

    extract( shortcode_atts( array(
        'display_title'     => false,
        'title'             => null,
        'container_class'   => null,
        'title_format'      => '<h3>%s</h3>',
        'month'             => date('m'),
        'year'              => date('Y'),
        'item_title_format' => '<p>%s</p>'
    ), $atts ) );


    $ScheduledCalendar = new ScheduledCalendar( $month , $year );
    $scheduled = $ScheduledCalendar->getScheduledPosts();

    $params = array();

    $params['title'] = $title;

    if( !is_null($container_class) && !empty($container_class) ){
    	$params['container_class'] = $container_class;
    }

    if( !is_null($title_format) && !empty($title_format) ){
		$params['title_format']   = $title_format;
	}

    return $ScheduledCalendar->getListScheduled($scheduled, $display_title , $params );
}




