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
 * Jueves, 16 Octubre 2014 16:50:15
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Create form classes for the current model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineBuildFormsTask.class.php 23927 2009-11-14 16:10:57Z fabien $
 */
class sfDoctrineBuildFormsTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'EL entorno de desarrollo', 'dev'),
            new sfCommandOption('model-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombre de la carpeta modelo', 'model'),
            new sfCommandOption('form-dir-name', null, sfCommandOption::PARAMETER_REQUIRED, 'El nombre de la carpeta formulario', 'form'),
            new sfCommandOption('generator-class', null, sfCommandOption::PARAMETER_REQUIRED, 'La clase generador', 'sfDoctrineFormGenerator'),
        ));

        $this->namespace           = 'doctrine';
        $this->name                = 'build-forms';
        $this->briefDescription    = '>> Crea clases formulario para el actual modelo de datos';
        $this->detailedDescription = <<<EOF
La tarea [doctrine:build-forms|INFO] crea clases de formularios desde el 
esquema:

  [./symfony doctrine:build-forms|INFO]

Esta tarea crea clases de forlumarios basados en el modelo de datos, estas
son creadas en el directorio [lib/doctrine/form|COMMENT].

Esta tarea nunca sobreescribe clases personalizadas en 
[lib/doctrine/form|COMMENT], solo reemplaza las clases base generadas
en [lib/doctrine/form/base|COMMENT].
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->logSection('doctrine', 'Generando clases de formularios');

        $databaseManager  = new sfDatabaseManager($this->configuration);
        $generatorManager = new sfGeneratorManager($this->configuration);
        $generatorManager->generate($options['generator-class'], array(
            'model_dir_name' => $options['model-dir-name'],
            'form_dir_name'  => $options['form-dir-name'],
        ));

        $properties = parse_ini_file(sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'properties.ini', true);

        $constants = array(
            'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
            'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Aqui tu nombre'
        );

        // personaliza los archivos php y yml
        $finder = sfFinder::type('file')->name('*.php');
        $this->getFilesystem()->replaceTokens($finder->in(sfConfig::get('sf_lib_dir').'/form/'), '##', '##', $constants);

        // revisamos si la clase base form esta definida
        if (!class_exists('BaseForm')) {
            $file = sfConfig::get('sf_lib_dir').'/'.$options['form-dir-name'].'/BaseForm.class.php';
            $this->getFilesystem()->copy(
                sfConfig::get('sf_symfony_lib_dir').'/task/generator/skeleton/project/lib/form/BaseForm.class.php', 
                $file
            );
            $this->getFilesystem()->replaceTokens($file, '##', '##', $constants);
        }

        $this->reloadAutoload();
    }

}