<?php

class block_licenses_vicensvives extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_licenses_vicensvives');
    }

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG, $COURSE;

        if( $this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        require_once("$CFG->dirroot/blocks/licenses_vicensvives/locallib.php");

        $context = context_system::instance();

        if (has_capability('moodle/course:update', $context)) {
            $this->title = get_string('pluginname', 'block_licenses_vicensvives');
            if (preg_match('/^vv-([0-9]+)-/', $COURSE->idnumber, $match)) {
                $idbook = (int) $match[1];
                try {
                    $licenses = vicensvives_count_licenses($idbook);
                } catch (vicensvives_ws_error $e) {
                    $this->content->text = html_writer::tag('div', $e->getMessage(), array('class' => 'error alert alert-error'));
                    return $this->context;
                }
                if (isset($licenses[$idbook])) {
                    $count = $licenses[$idbook];
                    $student = get_string('studentlicenses', 'block_licenses_vicensvives') . ': '
                             . $count->studentactivated . ' / ' . $count->studenttotal;
                    $teacher = get_string('teacherlicenses', 'block_licenses_vicensvives') . ': '
                             . $count->teacheractivated . ' / ' . $count->teachertotal;
                    $this->content->text .= $student . '<br/>' . $teacher . '<br/>';
                }
            }
            $url = new moodle_url('/blocks/licenses_vicensvives/licenses.php', array('course' => $COURSE->id));
            $text = get_string('showlicenses', 'block_licenses_vicensvives');
            $this->content->text .= html_writer::link($url, $text);
        }

        return $this->content;
    }
}
