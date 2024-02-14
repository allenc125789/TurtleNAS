<?php

$_POST['pword'];

print_r($_POST);

echo hash('sha512', $_POST['pword']);


