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
class sfGenerateProAppModBootstrap2Task extends sfGeneratorBaseTask {
    
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
            new sfCommandArgument('mod', 2, 'El nombre del modulo', 'bootstrap2')
        ));

        $this->namespace = 'generate';
        $this->name = 'pro-app-mod';
        $this->briefDescription = '>> Genera un proyecto symfony - bootstrap2';
        $this->detailedDescription = <<<EOF
La tarea [generate:pro-app-mod|INFO] crea un proyecto completo con todas las 
librerias, modulos, controladores, orm, swiftmailer y plugins de doctrine, 
bootstrap2 con el framework de symfony 1.4.20. 
Requiere de tres parametros obligatorios:
 - nombre_proyecto
 - nombre_de_la_aplicacion (Default: frontend)
 - nombre_del_modulo (Default: bootstrap2)

[./symfony generate:pro-app-mod nombre_proyecto|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->force_rmdir(sfConfig::get('sf_root_dir'));
        $argOpc = array(
            'generate:project' => array(
                'arguments' => array($arguments['pro'].' "'.self::$_propietario.'"')
            ),
            'generate:app' => array(
                'arguments' => array($arguments['app'].' --routing-bootstrap2=true')
            ),
            'generate:module-full-bootstrap2' => array(
                'arguments' => array($arguments['app'], $arguments['mod'])
            )
        );
        foreach ($argOpc as $k => $v):
            $this->runTask($k, $v['arguments']);
        endforeach;
        $this->logSection(
            'symfony - bootstrap2 :', 
            sprintf('Proyecto "%s" creado '.$this->getDateAndTimeInEs(date('Y-m-d H:i:s')), $arguments['pro'])
        );
    }
    
    protected function force_rmdir($path) {
        if (!file_exists($path)) { return false; }
        if (is_file($path) || is_link($path)) { return unlink($path); }
        if (is_dir($path)) {
            $path = rtrim($path, "\\")."\\";
            $result = true;
            $dir = new DirectoryIterator($path);
            foreach ($dir as $file) {
                if (!$file->isDot()) {
                    $result &= $this->force_rmdir($path.$file->getFilename());
                }
            }
            $result &= rmdir($path);
            return $result;
        }
    }

}