<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Martes, 07 Octubre 2014 09:27:54
 * + ------------------------------------------------------------------- +
 */

/**
 * defaultActions module.
 *
 * @package    symfony
 * @subpackage action
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions {

    /**
     * Felicitaciones pagina para la creacion de una nueva aplicacion
     *
     */
    public function executeIndex() {}

    /**
     * Felicitaciones pagina para la creacion de un nuevo modulo
     *
     */
    public function executeModule() {}

    /**
     * Pagina de error para pagina no encontrada Error (404)
     *
     */
    public function executeError404() {}

    /**
     * Advertencia!! pagina para area reestringida - requiere login
     *
     */
    public function executeSecure() {}

    /**
     * Advertencia!! pagina para area reestringida - requere credenciales
     *
     */
    public function executeLogin() {}

    /**
     * Modulo deshabilitado en settings.yml
     *
     */
    public function executeDisabled() {}

}