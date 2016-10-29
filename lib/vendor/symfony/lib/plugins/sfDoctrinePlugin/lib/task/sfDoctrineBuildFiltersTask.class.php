<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license informationation, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Viernes, 17 Octubre 2014 22:33:21
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Crea clases de formulario para el filtrado par el actual modelo.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineBuildFiltersTask.class.php 23927 2009-11-14 16:10:57Z fabien $
 */
class sfDoctrineBuildFiltersTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'El ambiente de desarrollo', 'dev'),
            new sfCommandOption('model-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombre de la carpeta modelo', 'model'),
            new sfCommandOption('filter-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombre de la carpeta formulario para el filtrado', 'filter'),
            new sfCommandOption('generator-class', null, sfCommandOption::PARAMETER_REQUIRED, 'La clase generador', 'sfDoctrineFormFilterGenerator'),
        ));

    $this->namespace           = 'doctrine';
    $this->name                = 'build-filters';
    $this->briefDescription    = '>> Crea la clase formulario para el filtrado del actual modelo';
    $this->detailedDescription = <<<EOF
La tarea [doctrine:build-filters|INFO] crea la clase formulario para el filtrado
desde el esquema (schema.yml):

  [./symfony doctrine:build-filters|INFO]

Esta tarea crea una clase formualrio para el filtrado basado en el modelo. Las
clases son readas en el directorio [lib/doctrine/filter|COMMENT].

Esta tarea nunca sobreescribe clases personalizadas en 
[lib/doctrine/filter|COMMENT], solo reemplaza las clases base generadas en
[lib/doctrine/filter/base|COMMENT].
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->logSection('doctrine', 'Generando clases formularios para el filtrado');

        $databaseManager = new sfDatabaseManager($this->configuration);
        $generatorManager = new sfGeneratorManager($this->configuration);
        $generatorManager->generate($options['generator-class'], array(
            'model_dir_name'  => $options['model-dir-name'],
            'filter_dir_name' => $options['filter-dir-name'],
        ));

        $properties = parse_ini_file(sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'properties.ini', true);

        $constants = array(
            'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
            'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Tu nombre aqui'
        );

        // customize php and yml files
        $finder = sfFinder::type('file')->name('*.php');
        $this->getFilesystem()->replaceTokens($finder->in(sfConfig::get('sf_lib_dir').'/filter/'), '##', '##', $constants);

        $this->reloadAutoload();
    }

}