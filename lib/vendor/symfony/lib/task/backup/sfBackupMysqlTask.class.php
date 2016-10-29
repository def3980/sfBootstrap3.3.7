<?php

/**
 * + ------------------------------------------------------------------- +
 * Agregando una nueva tarea para crear respaldo de base de datos solo   |
 * para el motor de base de datos MySQL.  Que pena por los demaas        |
 * Por Oswaldo Rojas un                                                  |
 * Miércoles, 12 Noviembre 2014 17:33:49                                 |
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
class sfBackupMysqlTask extends sfGeneratorBaseTask {
    
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
            new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'El nombre del archivo a crear'),
        ));
        $this->namespace           = 'backup';
        $this->name                = 'mysql';
        $this->briefDescription    = '>> Genera respaldo de base de datos';
        $this->detailedDescription = <<<EOF
La tarea [backup:mysql|INFO] crea un respaldo de base de datos con
extension *.sql a la vez que comprimime el mismo para mejor
transporte:

  [./symfony backup:mysql nombre_archivo|INFO]

Adicionalmente la instruccion sabe que extension crear para el archivo
comprimido (nombre_archivo.zip), ademas de incluir dentro del archivo 
comprimido el *.sql del respaldo con fecha y hora del sistema donde 
se encuentre alojado.

  backup.zip

El directorio por defecto para la ubicacion del respaldo de base de datos 
es la carpeta docs.
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $contenedor = array();
        $filename = $arguments['name']."_".date('Y-m-d')."_".date('His').".sql";
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
        $command = "mysqldump "
                 . "-h ".$contenedor['hst']." "
                 . "-u ".$contenedor['usr']." "
                 . "-p".$contenedor['pwd']." "
                 . (array_key_exists('prt', $contenedor) ? "--port=".$contenedor['prt']." " : '')
                 . "--skip-extended-insert "
                 . "--single-transaction "
                 . "--quick "
                 . $contenedor['dbn']." > "
                 . sfConfig::get('sf_docs_dir')."/"
                 . $filename;
        exec($command);

        $file_compress = "7z "
                       . "a "
                       . "-t7z "
                       . sfConfig::get('sf_docs_dir')."/backup.7z "
                       . sfConfig::get('sf_docs_dir')."/"
                       . $filename
//                       . "-m0=BCJ2 -m1=LZMA2:d=1024m -aoa";
//                       . "-mx9 -aoa";
                       . " -mx9";

        exec($file_compress);
        $this->logSection('backup:mysql', sprintf('"%s" :: creado satisfactoriamente...', $contenedor['dbn']));
        unlink(sfConfig::get('sf_docs_dir')."/".$filename);
    }

}