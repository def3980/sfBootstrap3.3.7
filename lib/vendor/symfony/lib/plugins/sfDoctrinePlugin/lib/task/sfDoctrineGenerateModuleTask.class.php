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
 * Sabado, 18 Octubre 2014 18:32:11
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Generates a Doctrine module.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineGenerateModuleTask.class.php 24637 2009-12-01 05:06:21Z Kris.Wallsmith $
 */
class sfDoctrineGenerateModuleTask extends sfDoctrineBaseTask {
    
    protected
        $_dias = array(
            'domingo', 
            'lunes', 
            'martes', 
            'miercoles', 
            'jueves', 
            'viernes', 
            'sabado'
        ),
        $_diasAbreviados = array(),
        $_meses = array(
            'enero', 
            'febrero', 
            'marzo', 
            'abril', 
            'mayo', 
            'junio',
            'julio', 
            'agosto', 
            'septiembre', 
            'octubre', 
            'noviembre', 
            'diciembre'
        ),
        $_mesesAbreviados = array();

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'El nombre de la aplicacion'),
            new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'El nombre del modulo'),
            new sfCommandArgument('model', sfCommandArgument::REQUIRED, 'El nombre de la clase del modelo'),
        ));

        $this->addOptions(array(
            new sfCommandOption('theme', null, sfCommandOption::PARAMETER_REQUIRED, 'The theme name', 'default'),
            new sfCommandOption('generate-in-cache', null, sfCommandOption::PARAMETER_NONE, 'Generate the module in cache'),
            new sfCommandOption('non-verbose-templates', null, sfCommandOption::PARAMETER_NONE, 'Generate non verbose templates'),
            new sfCommandOption('with-show', null, sfCommandOption::PARAMETER_NONE, 'Generate a show method'),
            new sfCommandOption('singular', null, sfCommandOption::PARAMETER_REQUIRED, 'The singular name', null),
            new sfCommandOption('plural', null, sfCommandOption::PARAMETER_REQUIRED, 'The plural name', null),
            new sfCommandOption('route-prefix', null, sfCommandOption::PARAMETER_REQUIRED, 'The route prefix', null),
            new sfCommandOption('with-doctrine-route', null, sfCommandOption::PARAMETER_NONE, 'Whether you will use a Doctrine route'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('actions-base-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The base class for the actions', 'sfActions'),
        ));

        $this->namespace           = 'doctrine';
        $this->name                = 'generate-module';
        $this->briefDescription    = '>> Genera un modulo doctrine';
        $this->detailedDescription = <<<EOF
La tarea [doctrine:generate-module|INFO] genera un modulo Doctrine:

  [./symfony doctrine:generate-module frontend article Article|INFO]

La tarea crea un modulo [%module%|COMMENT] en el directorio de la aplicacion
[%application%|COMMENT] en base al nombre del modelo de la clase.

Puedes crear tambien un modulo vacio que hereda las acciones y plantilas 
del directorio [%sf_app_cache_dir%/modules/auto%module%|COMMENT] usando
la opcion [--generate-in-cache|COMMENT] en tiempo de ejecucion:

  [./symfony doctrine:generate-module --generate-in-cache frontend article Article|INFO]

El generador puede referir un tema personalizado usando la opcion [--theme|COMMENT]:

  [./symfony doctrine:generate-module --theme="custom" frontend article Article|INFO]

De esta manera, puedes crear tu propio generador del modulos personalizado.

Adicional puedes cambiar las acciones por defecto de la clase base 
(default to sfActions) de los modulos generados:

  [./symfony doctrine:generate-module --actions-base-class="ProjectActions" frontend article Article|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
    
        $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);

        $this->constants = array(
            'PROJECT_NAME'   => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
            'APP_NAME'       => $arguments['application'],
            'MODULE_NAME'    => $arguments['module'],
            'UC_MODULE_NAME' => ucfirst($arguments['module']),
            'MODEL_CLASS'    => $arguments['model'],
            'AUTHOR_NAME'    => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Tu nombre aqui',
            'FECHA_Y_HORA'   => $this->getDateAndTimeInEs(date('Y-m-d H:i:s'))
        );

        $method = $options['generate-in-cache'] ? 'executeInit' : 'executeGenerate';

        $this->$method($arguments, $options);
    }

    protected function executeGenerate($arguments = array(), $options = array()) {
        // generate module
        $tmpDir = sfConfig::get('sf_cache_dir').DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.md5(uniqid(rand(), true));
        $generatorManager = new sfGeneratorManager($this->configuration, $tmpDir);
        $generatorManager->generate('sfDoctrineGenerator', array(
            'model_class'           => $arguments['model'],
            'moduleName'            => $arguments['module'],
            'theme'                 => $options['theme'],
            'non_verbose_templates' => $options['non-verbose-templates'],
            'with_show'             => $options['with-show'],
            'singular'              => $options['singular'] ? $options['singular'] : sfInflector::underscore($arguments['model']),
            'plural'                => $options['plural'] ? $options['plural'] : sfInflector::underscore($arguments['model'].'s'),
            'route_prefix'          => $options['route-prefix'],
            'with_doctrine_route'   => $options['with-doctrine-route'],
            'actions_base_class'    => $options['actions-base-class'],
        ));

        $moduleDir = sfConfig::get('sf_app_module_dir').'/'.$arguments['module'];

        // copy our generated module
        $this->getFilesystem()->mirror($tmpDir.DIRECTORY_SEPARATOR.'auto'.ucfirst($arguments['module']), $moduleDir, sfFinder::type('any'));

        if (!$options['with-show']) {
            $this->getFilesystem()->remove($moduleDir.'/templates/showSuccess.php');
        }

        // change module name
        $finder = sfFinder::type('file')->name('*.php');
        $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '', '', array('auto'.ucfirst($arguments['module']) => $arguments['module']));

        // customize php and yml files
        $finder = sfFinder::type('file')->name('*.php', '*.yml');
        $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $this->constants);

        // create basic test
        $this->getFilesystem()->copy(sfConfig::get('sf_symfony_lib_dir').DIRECTORY_SEPARATOR.'task'.DIRECTORY_SEPARATOR.'generator'.DIRECTORY_SEPARATOR.'skeleton'.DIRECTORY_SEPARATOR.'module'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'actionsTest.php', sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'functional'.DIRECTORY_SEPARATOR.$arguments['application'].DIRECTORY_SEPARATOR.$arguments['module'].'ActionsTest.php');

        // customize test file
        $this->getFilesystem()->replaceTokens(sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'functional'.DIRECTORY_SEPARATOR.$arguments['application'].DIRECTORY_SEPARATOR.$arguments['module'].'ActionsTest.php', '##', '##', $this->constants);

        // delete temp files
        $this->getFilesystem()->remove(sfFinder::type('any')->in($tmpDir));
    }

    protected function executeInit($arguments = array(), $options = array()) {
        $moduleDir = sfConfig::get('sf_app_module_dir').'/'.$arguments['module'];

        // create basic application structure
        $finder = sfFinder::type('any')->discard('.sf');
        $dirs = $this->configuration->getGeneratorSkeletonDirs('sfDoctrineModule', $options['theme']);

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                $this->getFilesystem()->mirror($dir, $moduleDir, $finder);
                break;
            }
        }

        // move configuration file
        if (file_exists($config = $moduleDir.'/lib/configuration.php')) {
            if (file_exists($target = $moduleDir.'/lib/'.$arguments['module'].'GeneratorConfiguration.class.php')) {
                $this->getFilesystem()->remove($config);
            } else {
                $this->getFilesystem()->rename($config, $target);
            }
        }

        // move helper file
        if (file_exists($config = $moduleDir.'/lib/helper.php')) {
            if (file_exists($target = $moduleDir.'/lib/'.$arguments['module'].'GeneratorHelper.class.php')) {
                $this->getFilesystem()->remove($config);
            } else {
                $this->getFilesystem()->rename($config, $target);
            }
        }

        // create basic test
        $this->getFilesystem()->copy(sfConfig::get('sf_symfony_lib_dir').DIRECTORY_SEPARATOR.'task'.DIRECTORY_SEPARATOR.'generator'.DIRECTORY_SEPARATOR.'skeleton'.DIRECTORY_SEPARATOR.'module'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'actionsTest.php', sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'functional'.DIRECTORY_SEPARATOR.$arguments['application'].DIRECTORY_SEPARATOR.$arguments['module'].'ActionsTest.php');

        // customize test file
        $this->getFilesystem()->replaceTokens(sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'functional'.DIRECTORY_SEPARATOR.$arguments['application'].DIRECTORY_SEPARATOR.$arguments['module'].'ActionsTest.php', '##', '##', $this->constants);

        // customize php and yml files
        $finder = sfFinder::type('file')->name('*.php', '*.yml');
        $this->constants['CONFIG'] = sprintf(<<<EOF
    model_class:           %s
    theme:                 %s
    non_verbose_templates: %s
    with_show:             %s
    singular:              %s
    plural:                %s
    route_prefix:          %s
    with_doctrine_route:   %s
    actions_base_class:    %s
EOF
            ,
            $arguments['model'],
            $options['theme'],
            $options['non-verbose-templates'] ? 'true' : 'false',
            $options['with-show'] ? 'true' : 'false',
            $options['singular'] ? $options['singular'] : '~',
            $options['plural'] ? $options['plural'] : '~',
            $options['route-prefix'] ? $options['route-prefix'] : '~',
            $options['with-doctrine-route'] ? 'true' : 'false',
            $options['actions-base-class']
        );
        $this->getFilesystem()->replaceTokens($finder->in($moduleDir), '##', '##', $this->constants);
    }

    /**
     * Ayuda a traducir la fecha y hora actual del sistema en formato español.
     *
     * Por Oswaldo Rojas ~ Sáb, 27 Sep 2014 13:53:12
     * 
     * @param  date $date Recibe la fecha y hora actual ('Y-m-d H:i:s')
     * @param  boolean $complete Indica si los nombre de las fechas son
     * completas o abreviadas
     * @param  boolean $capital Indica los nombres de las fechas con letra 
     * capital
     * @return string Ej.: Lun, 01 Ene 1970 00:00:01
     */
    protected function getDateAndTimeInEs($date, $complete = true, $capital = true) {
        // Debido a que este proyecto de modificacion de symfony se realiza en
        // Ecuador se va a poner por default el timezone correspondiente, pero
        // sientete libre de cambiarlo a tu gusto (manualmente) ;-|
        date_default_timezone_set('America/Guayaquil');

        foreach ($this->_dias as $k => $v) { $this->_diasAbreviados[$k] = substr($v, 0, 3); }        
        array_unshift($this->_meses, '');
        foreach ($this->_meses as $k => $v) { $this->_mesesAbreviados[$k] = substr($v, 0, 3); }
        array_unshift($this->_mesesAbreviados, '');
        $dia    = explode('-', $date, 3);
        $year   = reset($dia);
        $month  = (string)(int)$dia[1];
        $day    = (string)(int)$dia[2];
        $hms    = explode(' ', $dia[2], 2);
        $time   = (string) $hms[1];
        $dias   = $this->_dias;
        $dAbr   = $this->_diasAbreviados;
        $tdia   = $dias[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
        $tAbr   = $dAbr[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
        $meses  = $this->_meses;
        $mesAbr = $this->_mesesAbreviados;

        return $complete 
               ? ($capital 
                  ? ucfirst($tdia) 
                  : $tdia).", {$day} ".($capital 
                                        ? ucfirst($meses[$month]) 
                                        : $meses[$month])." {$year} {$time}"
               : ($capital 
                  ? ucfirst($tAbr) 
                  : $tAbr).", {$day} ".($capital 
                                        ? ucfirst($mesAbr[$month]) 
                                        : $mesAbr[$month])." {$year} {$time}";
    }

}