<?php

class ScheduledCalendar {

    const __ICON = 'ic-scheduledcalendar.png';

    private $month;

    private $year;

    public function __construct($month, $year) {
        $this->month = !empty($month) ? $month : date('n');
        $this->year  = !empty($year) ? $year : date('Y');
    }

    public function init() {
        add_action( 'admin_enqueue_scripts' , array( $this , 'load_scripts' ) );
    }

    public function load_scripts(){
        wp_register_style('scheduled_calendar_style', plugins_url('css/style.css', __FILE__), null, '1.0');
        wp_enqueue_style('scheduled_calendar_style');
    }

    public function addOptionsMenu() {
        add_menu_page(__('Scheduled Calendar', SCHEDULED_POST_CALENDAR_LANG), __('Scheduled Calendar', SCHEDULED_POST_CALENDAR_LANG), 'manage_options', 'scheduled-calendar', array(&$this, 'scheduledDashboard'), plugins_url('img/' . self::__ICON, __FILE__));
    }

    public static function getMonthNames($selected_month = null) {
        $array__month = array(
            __("January", SCHEDULED_POST_CALENDAR_LANG),
            __("February", SCHEDULED_POST_CALENDAR_LANG),
            __("March", SCHEDULED_POST_CALENDAR_LANG),
            __("April", SCHEDULED_POST_CALENDAR_LANG),
            __("May", SCHEDULED_POST_CALENDAR_LANG),
            __("June", SCHEDULED_POST_CALENDAR_LANG),
            __("July", SCHEDULED_POST_CALENDAR_LANG),
            __("August", SCHEDULED_POST_CALENDAR_LANG),
            __("September", SCHEDULED_POST_CALENDAR_LANG),
            __("October", SCHEDULED_POST_CALENDAR_LANG),
            __("November", SCHEDULED_POST_CALENDAR_LANG),
            __("December", SCHEDULED_POST_CALENDAR_LANG)
        );
        if (!is_null($selected_month)) {
            return $array__month[$selected_month];
        } else {
            return $array__month;
        }
    }

    public static function getWeekNames() {
        return array(
            __('Sunday', SCHEDULED_POST_CALENDAR_LANG),
            __('Monday', SCHEDULED_POST_CALENDAR_LANG),
            __('Tuesday', SCHEDULED_POST_CALENDAR_LANG),
            __('Wednesday', SCHEDULED_POST_CALENDAR_LANG),
            __('Thursday', SCHEDULED_POST_CALENDAR_LANG),
            __('Friday', SCHEDULED_POST_CALENDAR_LANG),
            __('Saturday', SCHEDULED_POST_CALENDAR_LANG)
        );
    }

    public static function getRandomColor($minVal = 0, $maxVal = 255) {
        $minVal = ($minVal < 0 || $minVal > 255) ? 0 : $minVal;
        $maxVal = ($maxVal < 0 || $maxVal > 255) ? 255 : $maxVal;
        $color = array();
        for ($i = 0; $i < 3; $i++) {
            array_push($color, dechex(rand($minVal, $maxVal)));
        }
        return implode('', $color);
    }

    public function Calendar($month, $year, $scheduled_posts_array = array()) {

        $prev__year = $next__year = $year;
        $prev__month = $month - 1;
        $next__month = $month + 1;


        if ($prev__month == 0) {
            $prev__month = 12;
            $prev__year = $year - 1;
        }
        if ($next__month == 13) {
            $next__month = 1;
            $next__year = $year + 1;
        }

        $prev__link = '?page=scheduled-calendar&month=' . $prev__month . '&year=' . $prev__year;
        $prev__label = __('Previous', SCHEDULED_POST_CALENDAR_LANG);

        $next__link = '?page=scheduled-calendar&month=' . $next__month . '&year=' . $next__year;
        $next__label = __('Next', SCHEDULED_POST_CALENDAR_LANG);

        $html = array();
        array_push($html, '<div style="display:block;"></div><table id="scheduled-calendar-table" class="table clearfix" cellpadding="0" cellspacing="0">');

        //calendar header
        array_push($html, '<thead>');
        array_push($html, '<tr><td colspan="7"><div class="scheduled-calendar-nav clearfix">');
        array_push($html, sprintf('<a href="%s" class="prev">%s</a>', $prev__link, $prev__label));
        array_push($html, '<span>' . self::getMonthNames($month - 1) . ' ' . $year . '</span>');
        array_push($html, sprintf('<a href="%s" class="next">%s</a>', $next__link, $next__label));
        array_push($html, '</div></td></tr>');
        array_push($html, '</thead>');


        //calendar body
        array_push($html, '<tbody>');
        array_push($html, '<tr class="week-list">');

        foreach (self::getWeekNames() as $week) {
            array_push($html, sprintf('<td title="%s">%s</td>', $week, substr($week, 0, 1)));
        }

        array_push($html, '</tr>');

        $today = date('d');
        $timestamp = mktime(0, 0, 0, $month, 1, $year);
        $maxday = date("t", $timestamp);
        $thismonth = getdate($timestamp);
        $startday = $thismonth['wday'];
        $html_calendar_date = array();
        for ($i = 0; $i < ($maxday + $startday); $i++) {

            $day = ($i - $startday + 1);
            if ($day < 10) {
                $day = '0' . $day;
            }

            $todayclass = $tdblock = '';
            if ($today == $day && $month == date('n')) {
                $todayclass = 'today';
            }

            if (($i % 7) == 0) {
                array_push($html_calendar_date, '<tr class="calendar-row">');
            }
            if ($i < $startday) {
                array_push($html_calendar_date, '<td></td>');
            } else {

                if (array_key_exists($day, $scheduled_posts_array)) {
                    $tdblock = sprintf('<div class="calendar-block" style="background-color:#%s">%d</div>', $scheduled_posts_array[$day][0]['color'], sizeof($scheduled_posts_array[$day]));
                }

                array_push($html_calendar_date, sprintf('<td class="%s">%d%s</td>', $todayclass, $day, $tdblock));
            }
            if (($i % 7) == 6) {
                array_push($html_calendar_date, '</tr>');
            }
        }

        array_push($html, implode('', $html_calendar_date));
        array_push($html, '</tbody>');
        array_push($html, '</table>');

        return implode('', $html);
    }

    public function getScheduledPosts() {

        $__scheduled = array();

        $args = array(
            'post_status' => 'future',
            'posts_per_page' => 100,
            'paged' => 1,
            'orderby' => 'date',
            'order' => 'asc',
            'monthnum' => $this->month,
            'year' => $this->year
        );

        $the_query = new WP_Query($args);

        while ($the_query->have_posts()) {
            $the_query->the_post();

            $__scheduled[get_the_time('d')][] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'time' => get_the_time(),
                'color' => self::getRandomColor(150, 255)
            );
        }
        wp_reset_postdata();
        return $__scheduled;
    }

    public function getListScheduled($array_scheduled, $display_title = true, $params = array()) {

        $params = array_merge( array(
            'container_class'   => 'scheduledposts-container',
            'title'             => null,
            'title_format'      => '<h1>%s</h1>',
            'item_title_format' => '<p>%s</p>'
        ) , $params );

        if(is_admin()){
             $params['item_title_format'] = '<h4 style="background-color:#%s">%s</h4>';
        }

        $html = array();

        array_push($html, sprintf('<div id="scheduled-list" class="%s">' , $params['container_class'] ));

        if ($display_title) {
            $title = empty( $params['title'] ) ?  __('Scheduled Posts', SCHEDULED_POST_CALENDAR_LANG) : $params['title'];
            array_push($html, sprintf( $params['title_format'] ,$title) );
        }   


        array_push($html, '<ul>');
        if (is_array($array_scheduled) && sizeof($array_scheduled) > 0) {

            foreach ($array_scheduled as $item_data => $item_value) {
                array_push($html, '<li>');

                if( is_admin() ){
                    $item_title = sprintf( $params['item_title_format'] , $item_value[0]['color'] , __('Day', SCHEDULED_POST_CALENDAR_LANG) . ' ' . $item_data );
                }else{
                    $item_title = sprintf( $params['item_title_format'], __('Day', SCHEDULED_POST_CALENDAR_LANG) . ' ' . $item_data );
                }

                array_push($html, $item_title);
                array_push($html, '<ul class="scheduled-post-item">');
                foreach ($item_value as $item) {
                    array_push($html, sprintf('<li><div class="title">%s</div><div class="time">%s</div></li>', $item['title'], $item['time']));
                }
                array_push($html, '</ul>');
                array_push($html, '</li>');
            }
        } else {
            array_push($html, sprintf('<li>%s</li>', __('0 posts found this month/year.', SCHEDULED_POST_CALENDAR_LANG)));
        }

        array_push($html, '</ul>');
        array_push($html, '</div>');
        return implode('', $html);
    }

    public function scheduledDashboard() {
        if (is_admin()) {
            $scheduled = $this->getScheduledPosts();
            echo '<div class="wrap" id="scheduled-wrap">';
            echo '<div class="icon32 icon32-scheduled-posts-calendar"><br></div>';
            echo '<h2 class="title">' . __('Scheduled Posts Calendar', SCHEDULED_POST_CALENDAR_LANG) . ' <span>' . SCHEDULED_POST_CALENDAR_VERSION . '</span></h2>';
            echo $this->author();
            echo $this->Calendar($this->month, $this->year, $scheduled);
            echo $this->getListScheduled($scheduled);
            echo '</div>';
        }
    }

    private function author() {
        $fb__subscribe = '<iframe src="//www.facebook.com/plugins/subscribe.php?href=https%3A%2F%2Fwww.facebook.com%2Feduardostuart&amp;layout=standard&amp;show_faces=false&amp;colorscheme=light&amp;font&amp;width=350&amp;appId=1239219039552300" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px;" allowTransparency="true"></iframe>';
        $ws__visit     = '<a href="http://eduardostuart.com.br/scheduled-post-calendar/?utm_source=wordpress-plugin&utm_medium=link&utm_campaign=wp_scheduled_posts_calendar" target="_blank">Eduardostuart.com.br</a>'; 

        $html__about = array();
        array_push($html__about, '<div id="author-scheduled-posts">');
        array_push($html__about, '<h3>' . __('News about Scheduled Posts Calendar', SCHEDULED_POST_CALENDAR_LANG) . '</h3>');
        array_push($html__about, '<div class="news clearfix">');
        array_push($html__about, '<div class="fb">' . $fb__subscribe . '</div>');
        array_push($html__about, '<div class="tw">' . $ws__visit . '</div>');
        array_push($html__about, '<div style="clear:both;"></div></div>');
        array_push($html__about, '</div>');

        echo implode('', $html__about);
    }

}