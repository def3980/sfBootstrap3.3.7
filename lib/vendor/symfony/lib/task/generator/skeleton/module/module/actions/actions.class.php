<?php

/**
 * ##MODULE_NAME## actions.
 * 
 * Ejecutado y creado ~ ##FECHA_y_HORA##
 *
 * @package    ##PROJECT_NAME##
 * @subpackage ##MODULE_NAME##
 * @author     ##AUTHOR_NAME##
 * @version    Symfony 1.4.20
 */
class ##MODULE_NAME##Actions extends sfActions {

    /**
     * Ejecuta una accion en el indice(nombre) del controlador
     *
     * @param sfWebRequest $request Recibe un objecto de la peticion
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

}