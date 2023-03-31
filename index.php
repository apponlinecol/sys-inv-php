<?php

require_once 'controllers/template.controller.php';
require_once 'controllers/general.controller.php';
require_once 'controllers/action.controller.php';
require_once 'controllers/users.controller.php';

require_once 'models/general.model.php';
require_once 'models/routes.php';

$template = new ControllerTemplate();
$template -> ctrTemplate();