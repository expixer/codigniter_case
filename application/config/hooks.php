<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'AccountVerified',
    'function' => 'verify',
    'filename' => 'AccountVerified.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
	'class'    => 'IsTeacher',
	'function' => 'verify',
	'filename' => 'IsTeacher.php',
	'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
	'class'    => 'IsStudent',
	'function' => 'verify',
	'filename' => 'IsStudent.php',
	'filepath' => 'hooks'
);
