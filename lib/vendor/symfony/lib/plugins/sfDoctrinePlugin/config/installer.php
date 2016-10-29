<?php

$this->installDir(dirname(__FILE__).'/skeleton');
$this->enablePlugin('sfDoctrinePlugin'); // Apunta a sfBaseTask.clas.php ~ funcion protegido protected function enablePlugin($plugin)
$this->reloadTasks();