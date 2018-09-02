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
   require_once $CFG->libdir.'/pagelib.php';
   require_once $CFG->dirroot.'/user/lib.php';
   require_once $CFG->dirroot.'/user/profile/lib.php';
   require_once $CFG->dirroot.'/grade/lib.php';   
   require_once $CFG->dirroot.'/course/report/pluginava/lib.php';

//   Arrays contendo os grupos de avalizações dos usuários   
$bom = array();

$medio = array();

$ruim = array();
   
$context = get_context_instance(CONTEXT_SYSTEM, 1);

$courseid = required_param('id', PARAM_INT);

$PAGE->set_context($context);

// Alteração Criada para incluir na URL
$PAGE->set_url('/course/report/pluginava/index.php?id='.$courseid);

$PAGE->requires->css('/course/report/pluginava/css/stylepluginava.css', true);

$PAGE->navbar->add(get_string('pluginname', 'coursereport_pluginava'), new moodle_url("$CFG->httpswwwroot/course/report/pluginava/index.php?id=".$courseid));

$PAGE->navbar->add('pluginava');

$PAGE->set_title('PluginAva');

$PAGE->set_pagelayout('report');

$PAGE->set_heading('PluginAva');

echo $OUTPUT->header();

$groupTeste = optional_param('group',null, PARAM_TEXT);
$userInfoTeste = optional_param('userInfo', null,PARAM_TEXT);
$UserSendTeste = optional_param('userSend', null,PARAM_TEXT);

$courseinfo = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
//var_dump($courseinfo);
$group_name;

if($UserSendTeste){
    $header_ava = header_ava(4,$courseinfo->shortname);      
    echo $header_ava;
}else{
    if($userInfoTeste){
        $header_ava = header_ava(3, $courseinfo->shortname);  
        
        echo $header_ava;
        if (isset($_GET['group'])) {
            //echo $_GET['group'];
            $group_name = $_GET['group'];
            //echo $group_name . " aeae";
        } else {
            // Fallback behaviour goes here
        }
        echo "<a href='index.php?id=$courseid&group=$group_name'>Voltar</a>";
        
        //var_dump($courseid);
        echo userGradeInfo($courseid, $userInfoTeste);
        
    }else{
        if($groupTeste){
            $header_ava = header_ava(2,$courseinfo->shortname);
            echo $header_ava;            
            $selectedGroup = print_group($courseid, $groupTeste);                             
                echo "<table style='width:100%; border: solid 1px black;margin: auto;'>";
                echo "<tr><th>Id</th><th>Nome do Usuário</th><th>Link</th></tr>";  
                echo "<a href='index.php?id=$courseid'>Voltar</a>";
                //var_dump($selectedGroup);
                //echo '<pre>', print_r($selectedGroup), '</pre';
                //echo "as";
               // var_dump($users_filtrado);
                
//                if(strpos($strTeste, "tes") === 0){
//                    echo "ok";
//                }
//                foreach($selectedGroup as $obj){
//                    echo "$obj->firstname";
//                }

                if (isset($_GET['group'])) {
                        $group_name = $_GET['group'];
                } else {
                    // Fallback behaviour goes here
                }
                echo "<form action=\"index.php?id=$courseid&group=$group_name\" method='post'>
                    <input type='text' name='usuario' id='user' value=''>
                    <input type='submit' value='pesquisar'>
                    </form>
                    ";
                             
//                $valor_filtrado = $_POST['usuario'];
                $valor_filtrado = optional_param('usuario',null, PARAM_TEXT);
                if(!$valor_filtrado){
                    foreach($selectedGroup as $t){                         
                    $uri = $_SERVER['REQUEST_URI'];
                    $uri.="&userInfo=$t->id";             
                    echo "<tr><td>$t->id</td><td>$t->firstname</td><td><a class='link' href = '$uri'> Próximo </a></td></tr>";                 
                }
                }else{
                $users_filtrado = array_filter($selectedGroup, function($user) use($valor_filtrado){
                    if(strpos($user->firstname, $valor_filtrado) === 0){
                        return $user;
                    }
                    //return $user->firstname === 'User2';
                }
                );
                //$selectedGroup
                //var_dump($users_filtrado);
                if(empty($users_filtrado)){
                    echo "Não encontramos nemhum dado com essa pesquisa!";
                }else{
                    foreach($users_filtrado as $t){                         
                    $uri = $_SERVER['REQUEST_URI'];
                    $uri.="&userInfo=$t->id";             
                    echo "<tr><td>$t->id</td><td>$t->firstname</td><td><a class='link' href = '$uri'> Próximo </a></td></tr>";                 
                }
                }
                
                 
                }
                echo "</table>"; 
        }else{                                    
            $header_ava = header_ava(1,$courseinfo->shortname);            
            echo $header_ava;
            get_index_course($courseid);
        }
    }
}
//
//Fim do teste de acesso as páginas
                   
//            echo "<a class='link' id='next' style='visibility: hidden' href = ''> Próximo </a>";
//      }            
            
//}
      
echo $OUTPUT->footer();

// Lucas 