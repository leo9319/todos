<?php

require 'vendor/autoload.php';

use App\AuthModel;

$authModel = new AuthModel();

$authModel->logout();

?>
