<?php

/**
 * Bootstrap2.3.2 - Todos los ejemplos y tutoriales de implementacion
 */

require_once(dirname(__FILE__).'/sfGeneratorBaseTask.class.php');

/**
 * Genera un modulo completo sobre Bootstrap2 y sus funcionalidades.
 *
 * @package    symfony
 * @subpackage task
 * @author     Oswaldo Rojas
 * @version    SVN: $Id: sfGenerateModuleTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class sfGenerateModuleFullBootstrap2Task extends sfGeneratorBaseTask {
    
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array( // 1 == sfCommandArgument::REQUIRED, 2 == sfCommandArgument::OPTIONAL
            new sfCommandArgument('application', 1, 'El nombre de la aplicacion'),
            new sfCommandArgument('module', 2, 'El nombre del modulo', 'bootstrap2'),
        ));
        $this->namespace           = 'generate';
        $this->name                = 'module-full-bootstrap2';
        $this->briefDescription    = '>> Genera un modulo completo sobre Bootstrap2';
        $this->detailedDescription = <<<EOF
La tarea [generate:module-full-bootstrap2|INFO] crea una estructura de 
directorio completa sobre Bootstrap2 en una aplicacion existente:

  [./symfony generate:module-full-bootstrap2 frontend nombre_modulo|INFO]

El nombre del modulo se puede omitir ya que "bootstrap2" esta por defecto
aunque se puede cambiar a otro.
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

        if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/module-bootstrap2')) {
            $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/module-bootstrap2';
        } else {
            $skeletonDir = dirname(__FILE__).'/skeleton/module-bootstrap2';
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