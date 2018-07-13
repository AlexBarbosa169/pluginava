<?php
   
   require_once '../../../config.php';
   require_once $CFG->libdir.'/gradelib.php';
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

echo 'Seja Bem Vindo!';
echo "<h3>Consulta de usuários existentes no banco</h3>";

echo $OUTPUT->footer();