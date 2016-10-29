<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all symfony generator tasks.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGeneratorBaseTask.class.php 6931 2008-01-04 06:31:12Z fabien $
 */
abstract class sfGeneratorBaseTask extends sfBaseTask {
    
    protected $_dias = array(
                        'domingo', 
                        'lunes', 
                        'martes', 
                        'miercoles', 
                        'jueves', 
                        'viernes', 
                        'sabado'
                       ),
              $_diasAbreviados = array(),
              $_meses = array(
                        'enero', 
                        'febrero', 
                        'marzo', 
                        'abril', 
                        'mayo', 
                        'junio',
                        'julio', 
                        'agosto', 
                        'septiembre', 
                        'octubre', 
                        'noviembre', 
                        'diciembre'
                       ),
              $_mesesAbreviados = array();

    /**
     * Ayuda a traducir la fecha y hora actual del sistema en formato español.
     *
     * Por Oswaldo Rojas ~ Sáb, 27 Sep 2014 13:53:12
     * 
     * @param  date $date Recibe la fecha y hora actual ('Y-m-d H:i:s')
     * @param  boolean $complete Indica si los nombre de las fechas son
     * completas o abreviadas
     * @param  boolean $capital Indica los nombres de las fechas con letra 
     * capital
     * @return string Ej.: Lun, 01 Ene 1970 00:00:01
     */
    public function getDateAndTimeInEs($date, $complete = true, $capital = true) {
        // Debido a que este proyecto de modificacion de symfony se realiza en
        // Ecuador se va a poner por default el timezone correspondiente, pero
        // sientete libre de cambiarlo a tu gusto (manualmente) ;-|
        date_default_timezone_set('America/Guayaquil');

        foreach ($this->_dias as $k => $v) { $this->_diasAbreviados[$k] = substr($v, 0, 3); }        
        array_unshift($this->_meses, '');
        foreach ($this->_meses as $k => $v) { $this->_mesesAbreviados[$k] = substr($v, 0, 3); }
        array_unshift($this->_mesesAbreviados, '');
        $dia    = explode('-', $date, 3);
        $year   = reset($dia);
        $month  = (string)(int)$dia[1];
        $day    = (string)(int)$dia[2];
        $hms    = explode(' ', $dia[2], 2);
        $time   = (string) $hms[1];
        $dias   = $this->_dias;
        $dAbr   = $this->_diasAbreviados;
        $tdia   = $dias[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
        $tAbr   = $dAbr[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
        $meses  = $this->_meses;
        $mesAbr = $this->_mesesAbreviados;

        return $complete 
               ? ($capital 
                  ? ucfirst($tdia) 
                  : $tdia).", {$day} ".($capital 
                                        ? ucfirst($meses[$month]) 
                                        : $meses[$month])." {$year} {$time}"
               : ($capital 
                  ? ucfirst($tAbr) 
                  : $tAbr).", {$day} ".($capital 
                                        ? ucfirst($mesAbr[$month]) 
                                        : $mesAbr[$month])." {$year} {$time}";
    }

}