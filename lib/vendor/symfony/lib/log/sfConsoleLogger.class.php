<?php

/**
 * + ------------------------------------------------------------------- +
 * Por Oswaldo Rojas
 * Añadiendo nuevas formas a lo ya optimizado.
 * Domingo, 27 Agosto 2016 12:32:35
 * + ------------------------------------------------------------------- +
 */

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfConsoleLogger logs messages to the console.
 *
 * @package    symfony
 * @subpackage log
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfConsoleLogger.class.php 10952 2008-08-19 15:04:33Z fabien $
 */
class sfConsoleLogger extends sfStreamLogger {

    /**
     * @see sfStreamLogger
     */
    public function initialize(sfEventDispatcher $dispatcher, $options = array()) {
        $options['stream'] = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');

        return parent::initialize($dispatcher, $options);
    }

}