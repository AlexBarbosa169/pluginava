<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page is provided for compatability and redirects the user to the default course_report_pluginAva
 *
 * @package   course_report_pluginAva
 * @copyright 
 * @license   
 */
   
   require_once '../../../config.php';
   require_once $CFG->libdir.'/gradelib.php';
   require_once $CFG->libdir.'/grade/grade_grade.php';
   require_once $CFG->libdir.'/datalib.php';
   require_once $CFG->dirroot.'/user/lib.php';
   require_once $CFG->dirroot.'/user/profile/lib.php';
   require_once $CFG->dirroot.'/grade/lib.php';
   require_once $CFG->dirroot.'/course/report/pluginava/lib.php';

//   Arrays contendo os grupos de avalizações dos usuários   
$bom = array();

$medio = array();

$ruim = array();
   
$context = get_context_instance(CONTEXT_SYSTEM, 1);

$PAGE->set_context($context);

$PAGE->set_url('/course/report/plugin/index.php');

$PAGE->navbar->add(get_string('pluginname', 'coursereport_pluginava'), new moodle_url("$CFG->httpswwwroot/coursereport/pluginava/index.php"));

$PAGE->navbar->add('pluginava');

$PAGE->set_title('PluginAva');

$PAGE->set_pagelayout('report');

$PAGE->set_heading('PluginAva');

echo $OUTPUT->header();

$courseid = required_param('id', PARAM_INT);
//Testando acesso as páginas

if(isset($_GET['userSend'])){
    $header_ava = header_ava(4);      
    echo $header_ava;
}else{
    if(isset($_GET['userInfo'])){
        $header_ava = header_ava(3);
        $useridInfo = $_GET['userInfo'];
        echo $header_ava;
        echo userGradeInfo($courseid, $useridInfo);
    }else{
        if(isset($_GET['group'])){
            $group = $_GET['group'];
            $header_ava = header_ava(2);
            echo $header_ava;            
            $selectedGroup = print_group($courseid, $group);             
                echo "<table style='width:100%; border: solid 1px black;margin: auto;'>";
                echo "<tr><th>Id</th><th>Nome do Usuário</th><th>Link</th></tr>";                
                foreach($selectedGroup as $t){                         
                    $uri = $_SERVER['REQUEST_URI'];
                    $uri.="&userInfo=$t->id";                    
                    echo "<tr><td>$t->id</td><td>$t->firstname</td><td><a class='link' href = '$uri'> Próximo </a></td></tr>";        
                }
                echo "</table>";                                                            
        }else{            
            $courseinfo = $COURSE;            
            $header_ava = header_ava(1,$courseinfo->shortname);            
            echo $header_ava;
            get_index_course($courseid);
        }
    }
}
//Foi
//Fim do teste de acesso as páginas
                   
        echo "<a class='link' id='next' style='visibility: hidden' href = ''> Próximo </a>";
//      }            
            
//}
      
echo $OUTPUT->footer();
