<?php
   $coursereport_pluginava_capabilities = array(
       'gradereport/[newreport]:view' => array(
           'riskbitmask' => RISK_PERSONAL,
           'captype' => 'read',
           'contextlevel' => CONTEXT_COURSE,
           'legacy' => array(
               'student' => CAP_ALLOW,
               'teacher' => CAP_ALLOW,
               'editingteacher' => CAP_ALLOW,
               'admin' => CAP_ALLOW
           )
       ),
   );
   ?>