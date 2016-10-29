<?php

/**
 * + ---------------------------------------------------------------------- +
 * Agregando una nueva tarea para cargar datos desde archivos YML de manera |
 * mas resumida.                                                            |
 * Por Oswaldo Rojas un                                                     |
 * Viernes, 06 Febrero 2015 12:33:14                                        |
 * + ---------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/../generator/sfGeneratorBaseTask.class.php');

/**
 * Genera respaldo de base de datos.
 *
 * @package    symfony
 * @subpackage backup
 * @author     Oswaldo Rojas <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGenerateModuleTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class sfLoadCustomTask extends sfGeneratorBaseTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->namespace           = 'load';
        $this->name                = 'custom';
        $this->briefDescription    = '>> Recarga datos en la base de datos de forma resumida';
        $this->detailedDescription = <<<EOF
La tarea [load:custom|INFO] recarga datos en la base de datos de manera resumida
es decir sin usar los tres comandos que son necesarios para esta actividad:

  [./symfony load:custom|INFO]

Tomara como referencia todos los archivos YML que se encuentren en el
directorio data/fixtures.

Nota: como adicional indico que esta tarea borrara la base de datos para
recargar los datos y no tener un autoincremental aumentado.
EOF;
    }
    
    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->runTask('doctrine:drop-db', '--no-confirmation');
        $this->runTask('doctrine:build-db');
        $this->runTask('doctrine:insert-sql');
        $this->runTask('doctrine:data-load');
        $this->logSection('load:custom', sprintf('recarga de datos terminada...'));
    }
}