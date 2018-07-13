<?php

function pluginava_report_extend_navigation($reportnav, $course, $context) {
    $url = new moodle_url('/course/report/pluginava/index.php', array('id' => $course->id));
    $reportnav->add(get_string('pluginname', 'coursereport_pluginava'), $url);
}