<?php

$bom = array();
$medio = array();
$ruim = array();

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
//            var_dump($std);
            switch ($std->sum) {
                case NULL:                
                    $ruim[] = $user;
                    break;
                case $std->sum >= 7 :
                    if($std->sum != NULL)
                        $bom[] = $user;
//                        var_dump($bom);
                    break;
                case $std->sum < 5 :
                    $ruim[] = $user;
//                    var_dump($ruim);
                    break;
                case $std->sum < 7 :
                    $medio[] = $user;
//                    var_dump($medio);
                    break;
                default:                    
                    break;
                }
            }
        }       
        echo "<div style='width: 100%; min-height: 450px;'>";
            echo graf(count($bom) , count($medio) , count($ruim));             
        echo "</div>";
}

function header_ava($nav){
    
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
    return "<h3>Desempenho dos estudantes do curso</h3><div style='display: flex;' class='row-fluid'>
            <div id='1' class='span3' style='opacity:$opt; padding: 10px; width: 25%; text-align: center; background-color: gray; border-radius: 10px;'>
            <img style='border-radius: 50%;' src='img/global.png' width='100' height='100' alt='global'/>
            </div>
            <div id='2' class='span3' style='opacity:$opt2; padding: 10px; width: 25%; text-align: center; background-color: gray; border-radius: 10px;'>
              <img style='border-radius: 50%;' src='img/groupMoodle.png' width='100' height='100' alt='group'/>
            </div>
            <div id='3' class='span3' style='opacity:$opt3; padding: 10px; width: 25%; text-align: center; background-color: gray; border-radius: 10px;'>
              <img style='border-radius: 50%;' src='img/userMoodle.png' width='100' height='100' alt='user'/>      
            </div>
            <div id='4' class='span3' style='opacity:$opt4; width: 25%; padding: 10px; text-align: center; background-color: gray; border-radius: 10px;'>
              <img style='border-radius: 50%;' src='img/messageMoodle.png' width='100' height='100' alt='message'/>
            </div>
          </div>";
}

function graf( $a , $b , $c){
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
                      ]);

                      var options = {
                            width: 500,
                            heigth: 300,
                            title: 'Desempenho dos estudantes do Curso',
                            colors:['green','blue','yellow']
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
            foreach ($usersCourse as $user) {
            $grade_progress = gradeProgress($courseid, $user->id);
            foreach ($grade_progress as $grade) {
                if($grade->sum < 5)
                    $selectGroup[] = $user;
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

function userGradeInfo($courseid, $userid) {
    global $DB;
    $grade_progress = $DB->get_records_sql(
            ""
            );
}