<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Martes, 07 Octubre 2014 15:45:55
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Creates a schema.yml from an existing database.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineBuildSchemaTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class sfDoctrineBuildSchemaTask extends sfDoctrineBaseTask {
    
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'El ambiente de desarrollo', 'dev'),
        ));

        $this->namespace        = 'doctrine';
        $this->name             = 'build-schema';
        $this->briefDescription = '>> Crea un esquema (*.yml) de una base de datos existente.';

    $this->detailedDescription = <<<EOF
La tarea [doctrine:build-schema|INFO] hace un ingenieria inversa 
desde la base de datos hasta crear el esquema yml:

  [./symfony doctrine:build-schema|INFO]

La tarea crea un archivo schema.yml en [config/doctrine|COMMENT]
EOF;
  }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->logSection('doctrine', 'Generando un esquema yaml desde una base de datos...');

        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->callDoctrineCli('generate-yaml-db');
    }

}