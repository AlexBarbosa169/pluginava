<?php

function pluginava_report_extend_navigation($reportnav, $course, $context) {
    $url = new moodle_url('/course/report/pluginava/index.php', array('id' => $course->id));
    $reportnav->add(get_string('pluginname', 'coursereport_pluginava'), $url);
}

function header_ava(){
    return "<h3>Desempenho dos estudantes do curso</h3><div class='row-fluid'>
            <div id='1' class='span3' style='opacity:$opt;'>
            <img src='img/global.png' width='100' height='100' alt='global'/>
            </div>
            <div id='2' class='span3' style='opacity:$opt2;'>
              <img src='img/groupMoodle.png' width='100' height='100' alt='group'/>
            </div>
            <div id='3' class='span3' style='opacity:$opt3;'>
              <img src='img/userMoodle.png' width='100' height='100' alt='user'/>      
            </div>
            <div id='4' class='span3' style='opacity:$opt4;'>
              <img src='img/messageMoodle.png' width='100' height='100' alt='message'/>
            </div>
          </div>";
}

function graf(){
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
}