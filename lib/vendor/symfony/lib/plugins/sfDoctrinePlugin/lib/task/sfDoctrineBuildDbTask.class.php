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
 * Viernes, 06 Febrero 2015 12:11:53
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Crea una base de datos para el actual modelo.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineBuildDbTask.class.php 24341 2009-11-24 15:01:58Z Kris.Wallsmith $
 */
class sfDoctrineBuildDbTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('database', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY, 'Una base de datos especifica'),
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'El ambiente de desarrollo', 'dev'),
        ));
        $this->aliases = array('doctrine:create-db');
        $this->namespace = 'doctrine';
        $this->name = 'build-db';
        $this->briefDescription = '>> Crea una base de datos para el modelo actual';
        $this->detailedDescription = <<<EOF
La tarea [doctrine:build-db|INFO] crea uno o mas bases de datos basados en la
configuracion del archivo [config/databases.yml|COMMENT]:

  [./symfony doctrine:build-db|INFO]

Puedes especificar cual base de datos crear indicando sus nombres:

  [./symfony doctrine:build-db desarrollo1 pruebas2|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $databases = $this->getDoctrineDatabases($databaseManager, count($arguments['database']) ? $arguments['database'] : null);
        $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';

        foreach ($databases as $name => $database) {
            $this->logSection('doctrine', sprintf('Creando base de datos "%s" en el ambiente "%s"', $name, $environment));
            try {
                $database->getDoctrineConnection()->createDatabase();
            } catch (Exception $e) {
                $this->logSection('doctrine', $e->getMessage(), null, 'ERROR');
            }
        }
    }
}
