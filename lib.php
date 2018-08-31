<?php

$bom = array();
$medio = array();
$ruim = array();
$nulos = array();

function pluginava_report_extend_navigation($reportnav, $course, $context) {
    $url = new moodle_url('/course/report/pluginava/index.php', array('id' => $course->id));
    $reportnav->add(get_string('pluginname', 'coursereport_pluginava'), $url);
}

function get_index_course($courseid) {          
    global $DB;
    $usersCourse = search_users($courseid);    
    foreach ($usersCourse as $user){                            
        $grade_progress = gradeProgress($courseid, $user->id);        
                
        foreach ($grade_progress as $std){
            $average += $std->sum;            
            switch ($std->sum) {
                case NULL:                
                    $nulos[] = $user;
                    break;
                case $std->sum > 7 :
                    if($std->sum != NULL)
                        $bom[] = $user;
//                        var_dump($bom);
                    break;
                case $std->sum < 5 :
                    $ruim[] = $user;
//                    var_dump($ruim);
                    break;
                case $std->sum > 4 && $std->sum < 8:
                    $medio[] = $user;
//                    var_dump($medio);
                    break;
                default:   
                    break;
                }
            }
        }       

        echo "<div class='container_index'>";            
        
        echo "<div id='piechart' >";          
        //echo graf(count($bom) , count($medio) , count($ruim), count($nulos)); 
        echo graf_chartjs(count($bom) , count($medio) , count($ruim), count($nulos)); 
        echo "</div>";        
        echo "<div class='info_course'>";
        echo "<div class='card_info'>";
        echo "<h4>Número de usuários no curso</h4><p>".count($usersCourse)."</p>";
        echo "</div>";        
        echo "<div class='card_info'>";
        echo "<h4>Média dos alunos do curso</h4><p>".(($average/count($usersCourse))*10)."%</p>";
        echo "</div>";
        echo "<div class='card_info'>";
        echo "<h4>Início das atividades no curso</h4><p>28/05/2018</p>";
        echo "</div>";        
        echo "</div>";        
        echo "</div>";        
}


function header_ava($nav, $coursename){
    
    switch ($nav) {
			case 1:
                            $opt = 1;
                            $opt2 = 0.2;
                            $opt3 = 0.2;
                            $opt4 = 0.2;
				break;
			case 2:
                            $opt = 0.2;
                            $opt2 = 1;
                            $opt3 = 0.2;
                            $opt4 = 0.2;
				break;
			case 3:
                            $opt = 0.2;
                            $opt2 = 0.2;
                            $opt3 = 1;
                            $opt4 = 0.2;
				break;
			case 4:
                            $opt = 0.2;
                            $opt2 = 0.2;
                            $opt3 = 0.2;
                            $opt4 = 1;
				break;
			default:
                            $opt = 1;
                            $opt2 = 0.2;
                            $opt3 = 0.2;
                            $opt4 = 0.2;
				break;
		}
                
    return "<h3 id='plugin-title'>Desempenho dos estudantes do curso $coursename</h3><div style='display: flex;' class='row-fluid'>
            <div id='nav-icon1' class='span3' style='opacity:$opt; padding: 10px; width: 25%; height: fit-content; text-align: center; background-color: gray; border-radius: 10px;'>
            <img class='bread' src='img/global.png' width='100' height='100' alt='global'/>
            </div>
            <div id='nav-icon2' class='span3' style='opacity:$opt2; padding: 10px; width: 25%; height: fit-content;text-align: center; background-color: gray; border-radius: 10px;'>
              <img class='bread' src='img/groupMoodle.png' width='100' height='100' alt='group'/>
            </div>
            <div id='nav-icon3' class='span3' style='opacity:$opt3; padding: 10px; width: 25%; height: fit-content;text-align: center; background-color: gray; border-radius: 10px;'>
              <img class='bread' src='img/userMoodle.png' width='100' height='100' alt='user'/>      
            </div>
            <div id='nav-icon4' class='span3' style='opacity:$opt4; width: 25%; padding: 10px; height: fit-content; text-align: center; background-color: gray; border-radius: 10px;'>
              <img class='bread' src='img/messageMoodle.png' width='100' height='100' alt='message'/>
            </div>
          </div><br><br>";
}

function graf( $a , $b , $c, $d){
    return "
            <html>
                <head>
                  <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
                  <script type='text/javascript'>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                      var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Ótimo', $a],
                        ['Bom',      $b],                        
                        ['Ruim', $c],  
                        ['Nulos', $d], 
                      ]);

                      var options = {
                            width: 300,
                            heigth: 300,                             
                            chartArea:{left:20,top:0,width:'100%',height:'75%'},                            
                            title: 'Desempenho dos estudantes do Curso',
                            colors:['green','yellow','red', 'gray']
                      };

                      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                        
                            function selectHandler() {
                                 var selectedItem = chart.getSelection()[0];
                                if (selectedItem) {
                                    var value = data.getValue(selectedItem.row, 0);                                    
                                    document.getElementById('next').style.setProperty('visibility','visible');
                                    var parsedUrl = new URL(window.location.href);
                                    console.log(parsedUrl);                                    
                                    parsedUrl.searchParams.set('group',value);                                    
                                    window.location.href = parsedUrl;
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
                  <div id='piechart' style='width: 900px; height: 500px; margin: auto;'></div>
                </body>
        </html>";
}

function print_group($courseid, $group){
    $usersCourse = search_users($courseid);
    $selectedGroup = array();
    
    if($group == "Ótimo"){
        foreach ($usersCourse as $user) {
            $grade_progress = gradeProgress($courseid, $user->id);
            foreach ($grade_progress as $grade) {
                if($grade->sum >= 7)
                    $selectGroup[] = $user;
            }
        }
    }else{
        if($group == "Bom"){
            foreach ($usersCourse as $user) {
            $grade_progress = gradeProgress($courseid, $user->id);
            foreach ($grade_progress as $grade) {
                if(($grade->sum >= 5)&&($grade->sum < 7))
                    $selectGroup[] = $user;
            }
        }
        }else{
            if($group == "Ruim"){
                foreach ($usersCourse as $user) {
                $grade_progress = gradeProgress($courseid, $user->id);
                foreach ($grade_progress as $grade) {
                    if($grade->sum < 5 && $grade->sum)
                        $selectGroup[] = $user;
                    }
                }
            }else {
                foreach ($usersCourse as $user) {
                $grade_progress = gradeProgress($courseid, $user->id);
                foreach ($grade_progress as $grade) {
                    if(!$grade->sum)
                        $selectGroup[] = $user;
                    }
                }
            }
        }
    }
    return $selectGroup;
}

function gradeProgress($courseid,$userid) {
    global $DB;
    $grade_progress = $DB->get_records_sql("select sum((gg.finalgrade :: bigint) * gi.aggregationcoef2)
                                    from public.mdl_grade_items as gi 
                                    join public.mdl_grade_grades as gg on
                                    gi.id = gg.itemid 
                                    join public.mdl_user as us
                                    on gg.userid = us.id
                                    join public.mdl_course as c on
                                    gi.courseid = c.id
                                    where gg.userid = us.id and c.id = $courseid 
                                    and gi.itemtype != 'course' and us.id = $userid");       

    return $grade_progress;
}

//function filterUsers($users){
//    if(strpos($strTeste, "tes") === 0){
//    return 
//}

function userGradeInfo($courseid, $userid) {
    global $DB;
    $grade_user = $DB->get_records_sql(
            "select gi.itemname as atividade,
                gi.grademin :: numeric(10,2)as notaMinima, 
                gi.grademax :: numeric(10,2) as notaMaxima, 
                gg.finalgrade :: numeric(10,2) as notaObtida,
                ((gg.finalgrade ::numeric(10,2) * gi.aggregationcoef2)*10):: numeric(10,2) as contribuicao
                from public.mdl_grade_items as gi 
                join public.mdl_grade_grades as gg on
                gi.id = gg.itemid 
                join public.mdl_user as us
                on gg.userid = us.id
                join public.mdl_course as c on
                gi.courseid = c.id
                where gg.userid = us.id and c.id = $courseid 
                and gi.itemtype != 'course' and us.id = $userid"
            );
    
            echo "<table style='border: solid 1px black; margin: auto;'>"
                    . "<thead>"
                    . "<th>Atividade</th>"
                    . "<th>Nota Miníma da Atividade</th>"
                    . "<th>Nota Máxima da Atividade</th>"
                    . "<th>Nota Máxima Obtida</th>"
                    . "<th>Contribuição para o curso</th>"
                    . "</thead><tbody>";								
            foreach ($grade_user as $grade) {                
//                var_dump($grade);
                if(!$grade->notaobtida)
                    $notaobtida = 0;
                else
                    $notaobtida = $grade->notaobtida;
                
                echo "<tr>";
                echo "<td>$grade->atividade</td>";
                echo "<td>$grade->notaminima</td>";
                echo "<td>$grade->notamaxima</td>";
                echo "<td>$notaobtida</td>";
                echo "<td>$grade->contribuicao%</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
}

    function graf_chartjs($a,$b,$c,$d){
    echo "<canvas id='myChartPie' width='400' height='400'></canvas>";
    
    echo "<script src='js/Chart.min.js'></script>";
    
    echo "<script>
        var ctx = document.getElementById('myChartPie').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Ótimo', 'Bom', 'Ruim', 'Nulos'],
                datasets: [{
                    label: '# of Votes',
                    data: [$a, $b, $c, $d ],
                    backgroundColor: [
                        'rgba(0, 232, 0, 1)',
                        'rgba(255, 235 , 59, 1)',
                        'rgba(200, 0, 0, 1)',
                        'rgba(128, 128, 128, 1)'                        
                    ],
                    borderColor: [
                        'rgba(255, 255, 255,1)',
                        'rgba(255, 255, 255, 1)',
                        'rgba(255, 255, 255, 1)',
                        'rgba(255, 255, 255, 1)'                        
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                events: ['click','mousemove']                                      
            }
        });                
        
        document.getElementById('myChartPie').onclick = function(evt){                        
            var activeElements = myChart.getElementsAtEvent(evt);            
                
        if(activeElements.length > 0)
            {
            var clickedElementindex = activeElements[0]['_index'];      
            
            var label = myChart.data.labels[clickedElementindex];            
//            var value = myChart.data.datasets[0].data[clickedElementindex];      
//            document.getElementById('next').style.setProperty('visibility','visible');
                                    var parsedUrl = new URL(window.location.href);
                                    console.log(parsedUrl);                                    
                                    parsedUrl.searchParams.set('group',label);                                    
                                    window.location.href = parsedUrl;
                console.log(label);
            }
        };
        </script>";
    
}