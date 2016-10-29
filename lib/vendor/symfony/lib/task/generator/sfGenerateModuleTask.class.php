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
 * Jueves, 27 Septiembre 2014 20:01:57
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfGeneratorBaseTask.class.php');

/**
 * Genera un nuevo modulo.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGenerateModuleTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class sfGenerateModuleTask extends sfGeneratorBaseTask {
    
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'El nombre de la aplicacion'),
            new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'El nombre del modulo'),
        ));
        $this->namespace           = 'generate';
        $this->name                = 'module';
        $this->briefDescription    = '>> Genera un nuevo modulo';
        $this->detailedDescription = <<<EOF
La tarea [generate:module|INFO] crea una estructura de directorio basica
para un modulo en una aplicacion existente:

  [./symfony generate:module frontend nombre_modulo|INFO]

La tarea tambien puede cambiar el nombre del autor que se encuentra en la 
clase antes generada [actions.class.php|COMMENT] para lo cual debes 
tener configurado el archivo ubicado en [config/properties.ini|COMMENT]:

  [[symfony]
    name=sitioweb
    author=Oswaldo Rojas <def.3980@gmail.com>|INFO]

Adicional se puede personalizar el esqueleto usado por defecto en esta tarea
creando uno en el directorio [%sf_data_dir%/skeleton/module|COMMENT].

Tambien se puede crear un nombre de tarea funcional que no esta pasado o 
realizado por defecto en 
[%sf_test_dir%/functional/%application%/%module%ActionsTest.class.php|COMMENT]

Si un modulo el cual contiene un nombre ya existente en la aplicacion, 
la misma tarea lanzara una excepcion [sfCommandException|COMMENT].
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $app    = $arguments['application'];
        $module = $arguments['module'];

        // Se valida el nombre del modulo
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $module)) {
            throw new sfCommandException(sprintf('El nombre del modulo "%s" es invalido.', $module));
        }

        $moduleDir = sfConfig::get('sf_app_module_dir').'/'.$module;

        if (is_dir($moduleDir)) {
            throw new sfCommandException(sprintf('El modulo "%s" ya existe en la aplicacion "%s".', $moduleDir, $app));
        }

        $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

        $constants = array(
            'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
            'APP_NAME'     => $app,
            'MODULE_NAME'  => $module,
            'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Aqui tu nombre',
            'FECHA_y_HORA' => $this->getDateAndTimeInEs(date('Y-m-d H:i:s')),
        );

        if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/module')) {
            $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/module';
        } else {
            $skeletonDir = dirname(__FILE__).'/skeleton/module';
        }

        // crea una estructura basica para la aplicacion
        $finder = sfFinder::type('any')->discard('.sf');
        $this->getFilesystem()->mirror($skeletonDir.'/module', $moduleDir, $finder);

        // crea un test basico
        $this->getFilesystem()->copy($skeletonDir.'/test/actionsTest.php', sfConfig::get('sf_test_dir').'/functional/'.$app.'/'.$module.'ActionsTest.php');

        // personaliza el archivo test
        $this->getFilesystem()->replaceTokens(
            sfConfig::get('sf_test_dir').'/functional/'.$app.DIRECTORY_SEPARATOR.$module.'ActionsTest.php', '##', '##', $constants
        );

        // customize php and yml files
        $finder = sfFinder::type('file')->name('*.php', '*.yml');
        $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $constants);
    }

}