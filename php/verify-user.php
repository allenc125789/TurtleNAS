<?php

$_POST['pword'];

print_r($_POST);

echo hash('sha256', $_POST['pword']);


