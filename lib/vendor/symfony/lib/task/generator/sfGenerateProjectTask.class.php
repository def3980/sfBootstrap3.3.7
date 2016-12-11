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
 * Jueves, 25 Septiembre 2014 14:39:25
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfGeneratorBaseTask.class.php');

/**
 * Genera un nuevo proyecto.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGenerateProjectTask.class.php 30530 2010-08-04 16:38:41Z fabien $
 */
class sfGenerateProjectTask extends sfGeneratorBaseTask {

    private static $_registro = array();

    /**
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
        $this->addArguments(array(
            new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'El nombre del proyecto'),
            new sfCommandArgument('author', sfCommandArgument::OPTIONAL, 'El author del proyecto', 'Tu nombre aqui'),
        ));

        $this->addOptions(array(
            new sfCommandOption('orm', null, sfCommandOption::PARAMETER_REQUIRED, 'El ORM a usar por defecto', 'Doctrine'),
            new sfCommandOption('installer', null, sfCommandOption::PARAMETER_REQUIRED, 'An installer script to execute', null),
        ));

        $this->namespace           = 'generate';
        $this->name                = 'project';
        $this->briefDescription    = '>> Genera un nuevo proyecto';
        $this->detailedDescription = <<<EOF
La tarea [generate:project|INFO] crea la estructura de archivos basica 
para el nuevo proyecto symfony en el actual directorio:

  [./symfony generate:project sitioweb|INFO]

Si el actual directorio ya contiene un proyecto symfony, enviara una
excepcion [sfCommandException|COMMENT].

Por defecto, la tarea configura Doctrine como ORM principal. Si quiere
usar Propel, se tiene que indicar en la opcion [--orm|COMMENT]:

  [./symfony generate:project sitioweb --orm=Propel|INFO]

Si no quiere usar un ORM, puede poner "[none|COMMENT]" (sin comillas) en la 
opcion [--orm|COMMENT]:

  [./symfony generate:project sitioweb --orm=none|INFO]

Se puede tambien ingresar la opcion [--installer|COMMENT] para personalizar 
aun mas el proyecto:

  [./symfony generate:project sitioweb --installer=./installer.php|INFO]

Puede incluir opcionalmente un segundo argumento "[author|COMMENT]" para 
especificar que nombre utilizar como "author" del proyecto symfony
cuando se generan las clases:

  [./symfony generate:project sitioweb "Oswaldo Rojas"|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        // cargando los datos para el registro
        
        self::$_registro['PROJECT_NAME'] = $arguments['name'];
        self::$_registro['AUTHOR_NAME']  = $arguments['author'];

        if (file_exists('symfony')) {
            throw new sfCommandException(sprintf('Un proyecto symfony ya existe en este directorio (%s).', getcwd()));
        }
        if (!in_array(strtolower($options['orm']), array('propel', 'doctrine', 'none'))) {
            throw new InvalidArgumentException(sprintf('Nombre ORM invalido "%s".', $options['orm']));
        }
        if ($options['installer'] && $this->commandApplication && !file_exists($options['installer'])) {
            throw new InvalidArgumentException(sprintf('El instalador "%s" no existe.', $options['installer']));
        }

        // limpiando la opcion orm
        $options['orm'] = ucfirst(strtolower(trim($options['orm'])));

        $this->arguments = $arguments;
        $this->options   = $options;

        // creando la estructura basica del proyecto
        $this->installDir(dirname(__FILE__).'/skeleton/project');

        // actualiza la clase ProjectConfiguration (usa una ruta relativa cuando 
        // el nucleo de symfony esta anidado dentro del proyecto)
        $symfonyCoreAutoload = 0 === strpos(sfConfig::get('sf_symfony_lib_dir'), sfConfig::get('sf_root_dir')) 
                               ? sprintf('dirname(__FILE__).\'/..%s/autoload/sfCoreAutoload.class.php\'', 
                                          str_replace(sfConfig::get('sf_root_dir'), '', sfConfig::get('sf_symfony_lib_dir'))) 
                               : var_export(sfConfig::get('sf_symfony_lib_dir').'/autoload/sfCoreAutoload.class.php', true);

        self::$_registro['SYMFONY_CORE_AUTOLOAD'] = str_replace('\\', '/', $symfonyCoreAutoload);
        $this->replaceTokens(array(sfConfig::get('sf_config_dir')), self::$_registro);
        unset(self::$_registro['SYMFONY_CORE_AUTOLOAD']);
        
        self::$_registro['FECHA_Y_HORA'] = $this->getDateAndTimeInEs(date('Y-m-d H:i:s'));
        $this->replaceTokens(array(sfConfig::get('sf_data_dir')), self::$_registro);
        $this->replaceTokens(array(sfConfig::get('sf_root_dir')), self::$_registro);

        $this->tokens = array(
            'ORM'          => $this->options['orm'],
            'PROJECT_NAME' => $this->arguments['name'],
            'AUTHOR_NAME'  => $this->arguments['author'],
            'PROJECT_DIR'  => sfConfig::get('sf_root_dir'),
            'FECHA_Y_HORA' => self::$_registro['FECHA_Y_HORA']
        );

        $this->replaceTokens();

        // Ejecuta el script de instalacion ORM elejido, es decir instala el ORM
        // a la vez que instala el proyecto de symfony siempre y cuando este 
        // indicado en la linea de comandos aunque ya viene por defecto asi que
        // el usuario elije. Por defecto (Doctrine).
        if (in_array($options['orm'], array('Doctrine', 'Propel'))) {
            include dirname(__FILE__)."/../../plugins/sf{$options['orm']}Plugin/config/installer.php";
        }

        // para generar una tarea ejemplo
        $this->getFilesystem()->replaceTokens(
            sfConfig::get('sf_lib_dir')."/task/tarea", 
            '##', '##', 
            array(
                'PROJECT_NAME' => $this->arguments['name'],
                'AUTHOR_NAME'  => $this->arguments['author'],
                'FECHA_Y_HORA' => self::$_registro['FECHA_Y_HORA']
            )
        );
        $this->getFilesystem()->rename(
            sfConfig::get('sf_lib_dir').'/task/tarea', 
            sfConfig::get('sf_lib_dir')."/task/{$this->arguments['name']}SaludosTask.class.php"
        );

        // Ejecuta un instalador personalizado
        if ($options['installer'] && $this->commandApplication) {
            if ($this->canRunInstaller($options['installer'])) {
                $this->reloadTasks();
                include $options['installer'];
            }
        }

        // fijar permisos para los directorio comunes
        $fixPerms = new sfProjectPermissionsTask($this->dispatcher, $this->formatter);
        $fixPerms->setCommandApplication($this->commandApplication);
        $fixPerms->setConfiguration($this->configuration);
        $fixPerms->run();

        $this->replaceTokens();
    }

    protected function canRunInstaller($installer) {
        if (preg_match('#^(https?|ftps?)://#', $installer)) {
            if (ini_get('allow_url_fopen') === false) {
                $this->logSection(
                    'generate', 
                    sprintf('No puede correr un instalador remoto "%s" debido a que "allow_url_fopen" esta deshabilitado '
                            . 'en el servidor web (apache || php || ngix) donde corre esta aplicacion', $installer)
                );
            }
            if (ini_get('allow_url_include') === false) {
                $this->logSection(
                    'generate', 
                    sprintf('No puede correr un instalador remoto "%s" debido a que "allow_url_include" esta deshabilitado '
                            . 'en el servidor web (apache || php || ngix) donde corre esta aplicacion', $installer)
                );
            }
            return ini_get('allow_url_fopen') && ini_get('allow_url_include');
        }
        return true;
    }

}