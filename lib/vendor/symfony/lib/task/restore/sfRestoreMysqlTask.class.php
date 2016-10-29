<?php

/**
 * + ------------------------------------------------------------------- +
 * Agregando una nueva tarea para restaurar un respaldo de base de datos |
 * solo para el motor de base de datos MySQL.                            |
 * Por Oswaldo Rojas un                                                  |
 * Viernes, 06 Febrero 2015 11:37:55                                     |
 * + ------------------------------------------------------------------- +
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
class sfRestoreMysqlTask extends sfGeneratorBaseTask {
    
    protected 
        $findDsn = "dsn:",
        $findHst = "host=",
        $findPrt = "port=",
        $findDbn = "dbname=",
        $findUsr = "username:",
        $findPwd = "password:";

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'El nombre del archivo a restaurar'),
        ));
        $this->namespace           = 'restore';
        $this->name                = 'mysql';
        $this->briefDescription    = '>> Restaura un respaldo de base de datos';
        $this->detailedDescription = <<<EOF
La tarea [restore:mysql|INFO] restaura un respaldo de base de datos con
extension *.sql hacia una instancia de base de datos creada en el motor
MySQL:

  [./symfony restore:mysql nombre_archivo|INFO]

El directorio por defecto para la ubicacion del respaldo de base de datos 
es la carpeta docs.
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $contenedor = array();
        if ($file = file(sfConfig::get('sf_config_dir').'/databases.yml')) {
            foreach ($file as $k => $v):
                if (false !== strpos($v, $this->findDsn)):
                    $contenedor['hst'] = trim(reset(split(';', end(split($this->findHst, trim(end(split(':', $v))))))));
                    $contenedor['dbn'] = trim(rtrim(end(split(
                                            $this->findDbn, 
                                            (end(split(';', end(split($this->findHst, trim(end(split(':', $v))))))))
                                         )), "'"));
                    foreach (split(';', rtrim(trim(end(split(':', $v))), "'")) as $k => $v) {
                        if (false !== strpos($v, $this->findPrt)) {
                            $contenedor['prt'] = trim(end(split('=', $v)));
                        }
                    }
                elseif (false !== strpos($v, $this->findUsr)):
                    $contenedor['usr'] = trim(end(split(':', $v)));
                elseif (false !== strpos($v, $this->findPwd)):
                    $contenedor['pwd'] = ltrim(rtrim(trim(end(split(':', $v))), "'"), "'");
                endif;
            endforeach;
        }
        $this->runTask('doctrine:drop-db');
        $this->runTask('doctrine:build-db');
        $command = "mysql "
                 . "-h ".$contenedor['hst']." "
                 . "-u ".$contenedor['usr']." "
                 . "-p".$contenedor['pwd']." "
                 . (array_key_exists('prt', $contenedor) ? "--port=".$contenedor['prt']." " : '')
                 . $contenedor['dbn']." < "
                 . sfConfig::get('sf_docs_dir')."/"
                 . $arguments['name'];
        exec($command);
        $this->logSection('restore:mysql', sprintf('"%s" :: restaurado satisfactoriamente...', $contenedor['dbn']));
    }

}