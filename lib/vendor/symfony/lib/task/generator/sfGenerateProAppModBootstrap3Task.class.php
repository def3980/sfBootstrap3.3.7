<?php

/**
 * Requiero de esta clase abstracta para usar sus funciones y despues esa clase
 * sfGeneratorBaseTask.class.php heredara de sfBaseTask.class.php.
 */
require_once(dirname(__FILE__).'/sfGeneratorBaseTask.class.php');

/**
 * Descripcion de la clase sfGenerateProAppModBootstrap2
 *
 * @author  Oswaldo
 * @fecha   Mar, 20 Sep 2014 16:11:57
 */
class sfGenerateProAppModBootstrap3Task extends sfGeneratorBaseTask {
    
    private static $_propietario = "Oswaldo Rojas <def.3980@gmail.com>";

    /**
     * Me permite ejecutar la tarea, saltandose la validacion que dice
     * "debo estar dentro de un directorio de proyecto symfony"
     * 
     * @see sfTask
     */
    protected function doRun(sfCommandManager $commandManager, $options) {
        $this->process($commandManager, $options);
        return $this->execute($commandManager->getArgumentValues(), $commandManager->getOptionValues());
    }

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array( // 1 == sfCommandArgument::REQUIRED, 2 == sfCommandArgument::OPTIONAL
            new sfCommandArgument('pro', 1, 'El nombre del proyecto'),
            new sfCommandArgument('app', 2, 'El nombre de la aplicacion', 'frontend'),
            new sfCommandArgument('mod', 2, 'El nombre del modulo', 'bootstrap3')
        ));

        $this->namespace = 'generate';
        $this->name = 'pro-app-mod';
        $this->briefDescription = '>> Genera un proyecto symfony - bootstrap3';
        $this->detailedDescription = <<<EOF
La tarea [generate:pro-app-mod|INFO] crea un proyecto completo con todas las 
librerias, modulos, controladores, orm, swiftmailer, plugins de doctrine y 
bootstrap3 con el framework de symfony 1.4.20.
Requiere de tres parametros obligatorios:
 - nombre_proyecto
 - nombre_de_la_aplicacion (Default: frontend)
 - nombre_del_modulo (Default: bootstrap3)

[./symfony generate:pro-app-mod nombre_proyecto|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        /*$this->force_rmdir(sfConfig::get('sf_root_dir'));die();*/

        $argOpc = array(
            'generate:project' => array(
                'arguments' => array($arguments['pro'].' "'.self::$_propietario.'"')
            ),
            'generate:app' => array(
                'arguments' => array($arguments['app'].' --routing-bootstrap3=true')
            ),
            'generate:module-full-bootstrap3' => array(
                'arguments' => array($arguments['app'], $arguments['mod'])
            )
        );
        foreach ($argOpc as $k => $v):
            $this->runTask($k, $v['arguments']);
        endforeach;
        $this->logSection(
            'symfony v1.4.20 - bootstrap3 :', 
            sprintf('Proyecto "%s" creado satisfactoriamente :: '.$this->getDateAndTimeInEs(date('Y-m-d H:i:s')), $arguments['pro'])
        );
    }
    
    protected function force_rmdir($path) {
        if (is_dir($path)) {
            $path = rtrim($path, "\\")."\\";
            $result = true;
            $dir = new DirectoryIterator($path);
            foreach ($dir as $file) {
                if (!$file->isDot()) {
                    switch ($file->getFilename()) {
                        case ".git":
                        case ".idea":
                        case ".gitignore":
                        case "lib":
                        case "nbeans":
                        break;
                        default:                            
                            /*if (is_dir($path.$file->getFilename())):
                                exec("RD /S /Q ".$path.$file->getFilename());
                                echo ">> ".$file->getFilename()." <~ Directorio eliminado...".PHP_EOL;
                            else:
                                exec("DEL \"".$path.$file->getFilename()."\"");
                                echo ">> ".$file->getFilename()." <~ Archivo eliminado...".PHP_EOL;
                            endif;*/
                        break;
                    }
                }
            }
        }
    }

}