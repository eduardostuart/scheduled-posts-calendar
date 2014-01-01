<?php

defined('ABSPATH') || die;

function scheduledpostscalendar_metabox() {
    add_meta_box(
        'scheduledpostscalendar_metabox', __('Scheduled Posts', SCHEDULED_POST_CALENDAR_LANG), 'scheduledpostscalendar_metabox_content', 'post', 'side', 'high'
    );
}

function scheduledpostscalendar_metabox_content() {
    global $ScheduledCalendar;
    $scheduled = $ScheduledCalendar->getScheduledPosts();
    echo $ScheduledCalendar->getListScheduled($scheduled, false);
}