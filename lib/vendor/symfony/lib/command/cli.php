<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Jueves, 25 Septiembre 2014 11:33:48
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/../autoload/sfCoreAutoload.class.php');
sfCoreAutoload::register();

try {
    $dispatcher = new sfEventDispatcher();
    $logger = new sfCommandLogger($dispatcher);

    $application = new sfSymfonyCommandApplication($dispatcher, null, array('symfony_lib_dir' => realpath(dirname(__FILE__).'/..')));
    $statusCode = $application->run();
} catch (Exception $e) {
    if (!isset($application)) {
        throw $e;
    }

    $application->renderException($e);
    $statusCode = $e->getCode();

    exit(is_numeric($statusCode) && $statusCode ? $statusCode : 1);
}

exit(is_numeric($statusCode) ? $statusCode : 0);