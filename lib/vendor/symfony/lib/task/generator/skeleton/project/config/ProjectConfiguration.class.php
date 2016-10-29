<?php

require_once ##SYMFONY_CORE_AUTOLOAD##;

sfCoreAutoload::register();

/**
 * Proyecto generado, configurado e instalado con el nombre:
 * 
 * ##PROJECT_NAME##
 *
 * @author  ##AUTHOR_NAME##
 * @fecha   ##FECHA_Y_HORA##
 */
class ProjectConfiguration extends sfProjectConfiguration {

    public function setup() {
        // Debido a que este proyecto de modificacion de symfony se realiza en
        // Ecuador se va a poner por default el timezone correspondiente, pero
        // sientete libre de cambiarlo a tu gusto (manualmente) ;-|
        date_default_timezone_set('America/Guayaquil');
        
        // Aqui se ubicaran todos los plugins instalados de forma automatica
        // (desde la linea de comandos) o de manera manual (ctrl + c && ctrl + v)
    }

}