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
 * Miércoles, 01 Octubre 2014 12:31:33
 * + ------------------------------------------------------------------- +
 */

/**
 * Crea el esqueleto de una tarea
 *
 * @package    symfony
 * @subpackage task
 * @author     Francois Zaninotto <francois.zaninotto@symfony-project.com>
 */
class sfGenerateTaskTask extends sfBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('task_name', sfCommandArgument::REQUIRED, 'El nombre de la tarea (puede contener namespace)'),
        ));

        $this->addOptions(array(
            new sfCommandOption('dir', null, sfCommandOption::PARAMETER_REQUIRED, 'El directorio donde sera creada la tarea', 'lib/task'),
            new sfCommandOption('use-database', null, sfCommandOption::PARAMETER_REQUIRED, 'Si es necesario que la tarea necesite acceso a la bdd del proyecto', sfConfig::get('sf_orm')),
            new sfCommandOption('brief-description', null, sfCommandOption::PARAMETER_REQUIRED, 'Una breve descripcion de la tarea (aparecera en la lista de tareas)'),
        ));

        $this->namespace           = 'generate';
        $this->name                = 'task';
        $this->briefDescription    = '>> Crea una clase esqueleto para la nueva tarea';
        $this->detailedDescription = <<<EOF
El comando [generate:task|INFO] crea una nueva clase sfTask basado en el nombre 
pasado como argumento:

  [./symfony generate:task namespace:name|INFO]

El esqueleto de la tarea [namespaceNameTask.class.php|COMMENT] es creada bajo 
el directorio [lib/task/|COMMENT]. Nota: el namespace es opcional.

Si desea crear un archivo en otro directorio 
(relativo a la carpeta raiz proyecto) indicarlo en la opcion [--dir|COMMENT].
Este directorio indicado se creara de ser necesario.

  [./symfony generate:task namespace:name --dir=plugins/myPlugin/lib/task|INFO]

Si desea que la tarea por defecto tenga otra conexion a base de datos indicar 
el nombre de esta conexion con la opcion [--use-database|COMMENT]:

  [./symfony generate:task namespace:name --use-database=main|INFO]

La opcion [--use-database|COMMENT] puede tambien ser usado para deshabilitar 
la acceso a la base de datos del proyecto:

  [./symfony generate:task namespace:name --use-database=false|INFO]

Se puede adicionalmente especificar una descripcion:

  [./symfony generate:task namespace:name --brief-description="hace cosas interesantes"|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $taskName           = $arguments['task_name'];
        $taskNameComponents = explode(':', $taskName);
        $namespace          = isset($taskNameComponents[1]) ? $taskNameComponents[0] : '';
        $name               = isset($taskNameComponents[1]) ? $taskNameComponents[1] : $taskNameComponents[0];
        $taskClassName      = str_replace('-', '', ($namespace ? $namespace.ucfirst($name) : $name)).'Task';

        // Validar el nombre de la clase
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $taskClassName)) {
            throw new sfCommandException(sprintf('El nombre de la clase "%s" es invalido.', $taskClassName));
        }

        $briefDescription    = $options['brief-description'];
        $detailedDescription = <<<HED
La tarea [$taskName|INFO] hace cosas interesantes.
Llamalo con:

  [php symfony $taskName|INFO]
HED;

        $useDatabase       = sfToolkit::literalize($options['use-database']);
        $defaultConnection = is_string($useDatabase) ? $useDatabase : sfConfig::get('sf_orm');

        if ($useDatabase) {
            $content = <<<HED
<?php

class $taskClassName extends sfBaseTask {

    protected function configure() {
        // agregar tus propios argumentos aqui
        // \$this->addArguments(array(
        //    new sfCommandArgument('mi_arg', sfCommandArgument::REQUIRED, 'Mi argumento'),
        //));

        \$this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombre de la aplicacion'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'El ambiente', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombred de la conexion', '$defaultConnection'),
            // agregar tus propias opciones aqui
        ));

        \$this->namespace        = '$namespace';
        \$this->name             = '$name';
        \$this->briefDescription = '$briefDescription';
        \$this->detailedDescription = <<<EOF
$detailedDescription
EOF;
    }

    protected function execute(\$arguments = array(), \$options = array()) {
        // inicializa la conexion con la base de datos
        \$databaseManager = new sfDatabaseManager(\$this->configuration);
        \$connection      = \$databaseManager->getDatabase(\$options['connection'])->getConnection();

        // agregar tu codigo aqui
    }

}
HED;
        } else {
            $content = <<<HED
<?php

class $taskClassName extends sfBaseTask {

    protected function configure() {
        // agregar tus propios argumentos aqui
        // \$this->addArguments(array(
        //    new sfCommandArgument('mi_arg', sfCommandArgument::REQUIRED, 'Mi argumento'),
        //));

        // agregar tus propias opciones aqui
        // \$this->addOptions(array(
        //    new sfCommandOption('mi_opcion', null, sfCommandOption::PARAMETER_REQUIRED, 'Mi opcion'),
        //));

        \$this->namespace           = '$namespace';
        \$this->name                = '$name';
        \$this->briefDescription    = '$briefDescription';
        \$this->detailedDescription = <<<EOF
$detailedDescription
EOF;
    }

    protected function execute(\$arguments = array(), \$options = array()) 
        // agregar tu codigo aqui
    }

}
HED;
        }

        // Revisa que el directorio de la tarea existe y que el archivo de la tarea
        // no existe
        if (!is_readable(sfConfig::get('sf_root_dir').'/'.$options['dir'])) {
            $this->getFilesystem()->mkdirs($options['dir']);
        }

        $taskFile = sfConfig::get('sf_root_dir').'/'.$options['dir'].'/'.$taskClassName.'.class.php';
        if (is_readable($taskFile)) {
            throw new sfCommandException(sprintf('La tarea "%s" ya existe en "%s".', $taskName, $taskFile));
        }

        $this->logSection('task', sprintf('Creado la tarea archivo "%s"', $taskFile));
        file_put_contents($taskFile, $content);
    }

}