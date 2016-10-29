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
 * Sabado, 18 Octubre 2014 15:48:56
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Crea el script SQL para el actual modelo.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfDoctrineBuildSqlTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class sfDoctrineBuildSqlTask extends sfDoctrineBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', true),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'El ambiente de desarrollo', 'dev'),
        ));

    $this->namespace           = 'doctrine';
    $this->name                = 'build-sql';
    $this->briefDescription    = '>> Crea el script SQL para el actual modelo';
    $this->detailedDescription = <<<EOF
La tarea [doctrine:build-sql|INFO] crea declaraciones SQL para la creacion de 
tablas:

  [./symfony doctrine:build-sql|INFO]

El SQL generado esta optimizado por la configuracion de la conexion de 
base de datos en [config/databases.yml|COMMENT]:

  [doctrine.database = mysql|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $this->logSection('doctrine', 'Generando script SQL para la base de datos');

        $path = sfConfig::get('sf_data_dir').'/sql';
        if (!is_dir($path)) {
            $this->getFilesystem()->mkdirs($path);
        }

        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->callDoctrineCli('generate-sql');
    }

}