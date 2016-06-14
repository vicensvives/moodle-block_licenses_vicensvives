<?php

require_once("$CFG->dirroot/blocks/courses_vicensvives/lib/vicensvives.php");

function vicensvives_count_licenses($idbook=null) {
    $result = array();
    $ws = new vicensvives_ws();

    foreach ($ws->licenses($idbook) as $license) {
        $idbook = $license->idBook;
        if (!isset($result[$idbook])) {
            $result[$idbook] = new stdClass;
            $result[$idbook]->studenttotal = 0;
            $result[$idbook]->studentactivated = 0;
            $result[$idbook]->teachertotal = 0;
            $result[$idbook]->teacheractivated = 0;
        }
        if ($license->userType == 'Student') {
            $result[$idbook]->studenttotal++;
            $result[$idbook]->studentactivated += (int) $license->activated;
        } elseif( $license->userType == 'Teacher') {
            $result[$idbook]->teachertotal++;
            $result[$idbook]->teacheractivated += (int) $license->activated;
        }
    }

    return $result;
}
