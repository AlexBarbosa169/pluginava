<?php
   
   require_once '../../../config.php';
   require_once $CFG->libdir.'/gradelib.php';
   require_once $CFG->dirroot.'/user/lib.php';
   require_once $CFG->dirroot.'/user/profile/lib.php';
   require_once $CFG->dirroot.'/grade/lib.php';
//   require_once $CFG->dirroot.'/grade/report/overview/lib.php';

$context = get_context_instance(CONTEXT_SYSTEM, 1);
$PAGE->set_context($context);
$PAGE->set_url('/course/report/plugin/index.php');
$PAGE->navbar->add(get_string('pluginname', 'coursereport_pluginava'), new moodle_url("$CFG->httpswwwroot/coursereport/pluginava/index.php"));
$PAGE->navbar->add('pluginava');
//$PAGE->set_title(get_string('heading', 'report_baseplugin'));
$PAGE->set_title('PluginAva');
$PAGE->set_pagelayout('report');
//$PAGE->set_heading(get_string('heading', 'report_baseplugin'));
$PAGE->set_heading('PluginAva');
//imprimir cabeçalho
echo $OUTPUT->header();

echo "<h3>Desempenho dos estudantes do curso</h3><div class='row-fluid'>
  <div id='1' class='span3'>Começar</div>
  <div id='2' class='span3'>Visão Geral</div>
  <div id='3' class='span3'>Filtro</div>
  <div id='4' class='span3'>Dados do Aluno</div>
</div>";

$courseid = required_param('id', PARAM_INT);

$usersCount = $DB->get_records_sql("select id, username from public.mdl_user");
$bom = array();
$medio = array();
$ruim = array();

foreach ($usersCount as $user){            
    $teste = $DB->get_records_sql("select sum((gg.finalgrade :: bigint) * gi.aggregationcoef2)
                                    from public.mdl_grade_items as gi 
                                    join public.mdl_grade_grades as gg on
                                    gi.id = gg.itemid 
                                    join public.mdl_user as us
                                    on gg.userid = us.id
                                    join public.mdl_course as c on
                                    gi.courseid = c.id
                                    where gg.userid = us.id and c.id = $courseid 
                                    and gi.itemtype != 'course' and us.id = $user->id");        
    echo "Id: $user->id Nome do usuário: $user->username Desempenho: $teste->sum";
    
    foreach ($teste as $sum){
        switch ($sum->sum) {
            case NULL:
                
                break;
            case $sum->sum >= 7 :
                if($sum->sum != NULL)
                    $bom[] = $sum->sum;
                break;
            case $sum->sum < 7 :
                $ruim[] = $sum->sum;
                break;
            default:
                break;
        }
        }
    
    foreach($teste as $t){        
        echo ($t->sum * 10)."%";
    }
    echo "<br><br>";    
}

var_dump($bom);
var_dump($ruim);
$b = count($bom);
$c = count($ruim);

        echo "
            <html>
                <head>
                  <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
                  <script type='text/javascript'>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                      var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Ótimo', $b],
                        ['Bom',      $c],                        
                        ['Ruim', 1],                        
                      ]);

                      var options = {
                        title: 'Desempenho dos estudantes do Curso' $curse->name,
                            colors:['green','blue','yellow']
                      };

                      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                        
                            function selectHandler() {
                                 var selectedItem = chart.getSelection()[0];
                                if (selectedItem) {
                                    var value = data.getValue(selectedItem.row, 0);
                                    alert('O selecionou ver os alunos que tiveram o desempenho ' + value + '.');
//                                      console.log(selectedItem);
                            }
                          }

                      google.visualization.events.addListener(chart, 'select', selectHandler);
                      chart.draw(data, options);
                    }                                        
                    
                    function alteraGrid(){
                        document.getElementById('1').style.color = 'blue';
                    }
                        
                  </script>
                </head>
                <body>
                  <div id='piechart' style='width: 900px; height: 500px;'></div>
                </body>
        </html>";
    
        $um = profile_load_custom_fields($currentuser);
        var_dump($um);
            
echo $OUTPUT->footer();