<?php

function get_config() {

return array(

'WSAS' => array(
    'page' => 'StateTestsPerformance.aspx',
    'fileprefix' => 'WSAS',
    'minyear' => '1997',
    'maxyear' => '2012',
    'params' => array(
        'SubjectID' => array('0AS', '1RE', '2LA', '3MA', '4SC', '5SS'),
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Disability', 'EconDisadv', 'ELP', 'Mig'),
//        'Level' => array('ALL', 'ADV', 'A-P', 'B-M-NT', 'No-W', 'SWD', 'ELL'),
        'WOW' => array('WSAS', 'WKCE'), 
        ),
    ),

'ACT' => array(
    'page' => 'ACTPage.aspx',
    'fileprefix' => 'ACT',
    'minyear' => '1996',
    'maxyear' => '2012',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity'),
        'ACTSubj' => array('1RE', '2LA', '3MA', '4SC', '0AS'),
        ),
    ),

'AP' => array(
    'page' => 'APTestsPage.aspx',
    'fileprefix' => 'Advanced_Placement_Program_Exams',
    'minyear' => '1997',
    'maxyear' => '2012',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity'),
        ),
    ),

'Attendance' => array(
    'page' => 'AttendancePage.aspx',
    'fileprefix' => 'Attendance',
    'minyear' => '1997',
    'maxyear' => '2011',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Grade', 'Disability', 'EconDisadv', 'ELP'),
        ),
    ),

'Enrollment' => array(
    'page' => 'GroupEnroll.aspx',
    'fileprefix' => 'Enrollment',
    'minyear' => '2003',
    'maxyear' => '2012',
    'params' => array(
         'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Grade', 'Disability', 'EconDisadv', 'ELP'),
        ),
    ),
);

}

function get_labels() {
return array(
    'Group' =>  array('AllStudentsFAY' => 'All_Students_Combined', 'Grade' => 'Grade_Level_Placement', 'Gender' => 'Gender', 'RaceEthnicity' => 'Race_Ethnicity', 'Disability' => 'Disability_Status', 'EconDisadv' => 'Economic_Status', 'ELP' => 'English_Language_Proficiency_Status', 'Mig' => 'Migrant_Status'),
    'ACTSubj' => array('1RE' => 'Reading', '2LA' => 'Language_Arts', '3MA' => 'Mathematics', '4SC' => 'Science', '0AS' => 'All_Subjects', '5SS' => 'Social_Studies'),
    'SubjectID' => array('0AS' => 'All_Subjects', '1RE' => 'Reading', '2LA' => 'Language_Arts', '3MA' => 'Mathematics', '4SC' => 'Science', '5SS' => 'Social_Studies'),
    'WOW' => array('WSAS' => 'Combined-wsas-wkce'),
);
}

?>
