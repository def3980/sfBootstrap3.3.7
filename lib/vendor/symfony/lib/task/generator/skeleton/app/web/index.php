<?php

/**
 * Proyecto generado, configurado e instalado con el nombre:
 * 
 * ##PROJECT_NAME##
 *
 * @author  ##AUTHOR_NAME##
 * @fecha   ##FECHA_Y_HORA##
 */

##IP_CHECK##
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('##APP_NAME##', '##ENVIRONMENT##', ##IS_DEBUG##);
sfContext::createInstance($configuration)->dispatch();