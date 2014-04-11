<?php

function get_config() {

return array(

'WSAS' => array(
    'page' => 'StateTestsPerformance.aspx',
    'fileprefix' => 'WSAS',
    'minyear' => '1998',
    'maxyear' => '2013',
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
#        'ACTSubj' => array('1RE', '3MA', '4SC', '0AS'),
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
    'maxyear' => '2012',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Grade', 'Disability', 'EconDisadv', 'ELP'),
        ),
    ),

'Enrollment' => array(
    'page' => 'GroupEnroll.aspx',
    'fileprefix' => 'Enrollment',
    'minyear' => '1996',
    'maxyear' => '2013',
    'params' => array(
         'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Grade', 'Disability', 'EconDisadv', 'ELP'),
        ),
    ),

'HS_Completion_legacy' => array(
    'page' => 'HSCompletionPage.aspx',
    'fileprefix' => 'hs_completion_legacy_rates',
    'minyear' => '1997',
    'maxyear' => '2012',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Disability', 'EconDisadv', 'ELP'),
		'TmFrm' => array( 'L'),
		),
    ),

'HS_Completion' => array(
    'page' => 'HSCompletionPage.aspx',
    'fileprefix' => 'hs_completion_adjusted_cohort_rates',
    'minyear' => '2010',
    'maxyear' => '2012',
    'params' => array(
        'Group' => array('AllStudentsFAY', 'Gender', 'RaceEthnicity', 'Disability', 'EconDisadv', 'ELP'),
		'TmFrm' => array( '4', '5', '6'),
		),
    ),

'Primary_Disability' => array(
    'page' => 'DisabilEnroll.aspx',
    'fileprefix' => 'Enrollment_by_Primary_Disability',
    'minyear' => '2003',
    'maxyear' => '2013',
    'params' => array(
	  'Group' => array('AllStudentsFAY'),
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
    'TmFrm' => array('L' => 'Legacy_Rate', '6' => 'Six_Year_Rate', '5' => 'Five_Year_Rate', '4' => 'Four_Year_Rate'),
);
}

function is_disabled_condition($dataset, $qs) {
    if (!is_array($qs)) $qs = qs_to_array($qs);
  
    switch ($dataset) {
    case 'WSAS':
        if (get_param('WOW', $qs) == 'WSAS') {
        //    if (any_equals($qs, array(array('Level','SWD'), array('Level','ELL')))) return true;  
            if (get_param('Year', $qs) < '2003') return true;
        } else {
            if (get_param('Year', $qs) > '2002') return true;
        }
//var_dump(get_param('SubjectID',$qs));
        if (get_param('SubjectID', $qs) == '0AS') return true;
        break;
    case 'Attendance':
        //if year is < 2005 && Disability, Economic Status, English Proficiency
        if (any_equals($qs, array(array('Group', 'Disability'), array('Group','ELP'),array('Group', 'EconDisadv')))) {
            if (get_param('Year', $qs) < 2005) return true;
        }
        break;
    case 'Primary_Disability':
    case 'Enrollment':
        if (get_param('Group', $qs) == 'Disability' && get_param('Year', $qs) < 2003) return true;
        if (get_param('Group', $qs) == 'EconDisadv' && get_param('Year', $qs) < 2001) return true;
        if (get_param('Group', $qs) == 'ELP' && get_param('Year', $qs) < 1999) return true;
        break;
    case 'HS_Completion_legacy':
    case 'HS_Completion':
        if (get_param('Group', $qs) == 'Disability' && get_param('Year', $qs) < 2003) return true;
        if (( get_param('Group', $qs) == 'EconDisadv' 
            || get_param('Group', $qs) == 'ELP' ) && get_param('Year', $qs) < 2008) return true;
        if (get_param('TmFrm', $qs) == '6' && get_param('Year', $qs) < 2012) return true;
        if (get_param('TmFrm', $qs) == '5' && get_param('Year', $qs) < 2011) return true;
        if (get_param('TmFrm', $qs) == '4' && get_param('Year', $qs) < 2010) return true;
    }

    return false;
}

?>
