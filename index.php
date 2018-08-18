<?php
   
   require_once '../../../config.php';
   require_once $CFG->libdir.'/gradelib.php';
   require_once $CFG->dirroot.'/user/lib.php';
   require_once $CFG->dirroot.'/user/profile/lib.php';
   require_once $CFG->dirroot.'/grade/lib.php';
   require_once $CFG->dirroot.'/course/report/pluginava/lib.php';

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

$header_ava = header_ava();

echo $header_ava;

$usersCourse = $DB->get_records_sql("select id, username from public.mdl_user");
$bom = array();
$medio = array();
$ruim = array();

foreach ($usersCourse as $user){            
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

if(isset($_GET['group'])){
    echo "<table style='width:100%; border: solid 1px black;'>";
    echo "<tr><th>Id</th><th>Nome do Usuário</th><th>Desempenho</th></tr>";    
//    echo "Id: $user->id Nome do usuário: $user->username Desempenho: $teste->sum";
    foreach($teste as $t){        
        $d = ($t->sum * 10)."%";
        echo "<tr><td>$user->id</td><td>$user->username</td><td>$d</td></tr>";        
    }
    echo "</table>";
}else{
    
    
    foreach ($teste as $std){
        switch ($std->sum) {
            case NULL:                
                break;
            case $std->sum >= 7 :
                if($std->sum != NULL)
                    $bom[] = $std->sum;
                break;
            case $std->sum < 5 :
                $ruim[] = $std->sum;
                break;
            case $std->sum < 7 :
                $medio[] = $std->sum;
                break;
            default:
                break;
        }
        }
    
    
    


$b = count($bom);
$c = count($medio);  

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
                                    document.getElementById('next').style.setProperty('visibility','visible');
                                    var parsedUrl = new URL(window.location.href);
                                    console.log(parsedUrl);                                    
                                    parsedUrl.searchParams.set('group',value);
                                    document.getElementById('next').href = parsedUrl;
                                    
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
    
        
                    
        echo "<a class='link' id='next' style='visibility: hidden' href = ''> link </a>";
      }            
}
      
echo $OUTPUT->footer();
