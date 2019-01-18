<?php

use Tuzk\Core\App;

require __DIR__."/helpers.php";

App::bind('config', require __DIR__."/../config.php");
App::bind('force', false);

require __DIR__."/commandos.php";
require __DIR__."/routes.php";
