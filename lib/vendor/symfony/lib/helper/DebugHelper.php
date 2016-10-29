<?php

/**
 * + ------------------------------------------------------------------- +
 * Por Oswaldo Rojas
 * Añadiendo nuevas formas a lo ya optimizado.
 * Domingo, 21 Agosto 2016 21:54:29
 * + ------------------------------------------------------------------- +
 */

function log_message($message, $priority = 'info') {
    if (sfConfig::get('sf_logging_enabled')) {
        sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent(null, 'application.log', array($message, 'priority' => constant('sfLogger::'.strtoupper($priority)))));
    }
}
