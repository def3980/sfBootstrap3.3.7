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
 * Viernes, 06 Febrero 2015 11:52:24
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Borra la base de datos para el actual modelo.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineDropDbTask.class.php 24341 2009-11-24 15:01:58Z Kris.Wallsmith $
 */
class sfDoctrineDropDbTask extends sfDoctrineBaseTask {

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
            new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Forzar el borrado de la base de datos')
        ));
        $this->namespace = 'doctrine';
        $this->name = 'drop-db';
        $this->briefDescription = '>> Borra la base de datos para el modelo actual';
        $this->detailedDescription = <<<EOF
La tarea [doctrine:drop-db|INFO] elimina una o mas bases de datos basados en la
configuracion del archivo [config/databases.yml|COMMENT]:

  [./symfony doctrine:drop-db|INFO]

Se te preguntara antes de eliminar cualquier base de datos a menos que tu
indiques la opcion [--no-confirmation|COMMENT]:

  [./symfony doctrine:drop-db --no-confirmation|INFO]

Puedes especificar cual base de datos eliminar indicando sus nombres:

  [./symfony doctrine:drop-db ejemplo1 produccion2|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $databases = $this->getDoctrineDatabases($databaseManager, count($arguments['database']) ? $arguments['database'] : null);
        $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : 'all';

        if (!$options['no-confirmation'] && !$this->askConfirmation(array_merge(
                array(sprintf('Este comando va a borrar todos los datos de la(s) siguiente(s) conexion(es) "%s":', $environment), ''),
                array_map(create_function('$v', 'return \' - \'.$v;'), array_keys($databases)),
                array('', 'Estas seguro de continuar? (y/N)')
            ), 'QUESTION_LARGE', false)) {
            $this->logSection('doctrine', 'tarea cancelada');

            return 1;
        }

        foreach ($databases as $name => $database) {
            $this->logSection('doctrine', sprintf('Borrando la base de datos "%s"', $name));
            try {
                $database->getDoctrineConnection()->dropDatabase();
            } catch (Exception $e) {
                $this->logSection('doctrine', $e->getMessage(), null, 'ERROR');
            }
        }
    }
}
