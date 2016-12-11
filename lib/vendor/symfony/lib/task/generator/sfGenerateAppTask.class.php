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
 * Jueves, 25 Septiembre 2014 22:39:12
 * + ------------------------------------------------------------------- +
 */

require_once(dirname(__FILE__).'/sfGeneratorBaseTask.class.php');

/**
 * Generates a new application.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGenerateAppTask.class.php 24039 2009-11-16 17:52:14Z Kris.Wallsmith $
 */
class sfGenerateAppTask extends sfGeneratorBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('app', sfCommandArgument::REQUIRED, 'El nombre de la aplicacion'),
        ));

        $this->addOptions(array(
            new sfCommandOption('escaping-strategy', null, sfCommandOption::PARAMETER_REQUIRED, 'Estrategia salida de escape de caracteres', true),
            new sfCommandOption('csrf-secret', null, sfCommandOption::PARAMETER_REQUIRED, 'Clave secreta para proteccion CSRF', true),
            new sfCommandOption('routing-bootstrap3', null, sfCommandOption::PARAMETER_REQUIRED, 'Genera rutas personalizadas de Bootstrap3 en la aplicacion', false),
        ));

        $this->namespace        = 'generate';
        $this->name             = 'app';
        $this->briefDescription = '>> Genera una nueva aplicacion';
        $this->detailedDescription = <<<EOF
La tarea [generate:app|INFO] crea la estructura de directorio basica para
una nueva aplicacion en el actual proyecto:

  [./symfony generate:app frontend|INFO]

Esta tarea tambien crea dos scripts de controladores frontales en el
directorio [web/|COMMENT]:

  [web/%application%.php|INFO]     para el ambiente de produccion
  [web/%application%_dev.php|INFO] para el ambiente de desarrollo

Para la primera aplicacion, el script de ambiente de produccion es
nombrado [index.php|COMMENT].

Si una aplicacion ya contiene un nombre existente enviara una
excepcion [sfCommandException|COMMENT].

Por defecto, la salida de escape esta activada (para prevenir ataques XSS)
y una clave secreta random es tambien creada para combatir CSRF.

Puedes deshabilitar la salida de escape usando la opcion 
[escaping-strategy|COMMENT]:

  [./symfony generate:app frontend --escaping-strategy=false|INFO]

Puedes activar la sesion token en los formularios (para prevenir CSRF)
definiendo una clave secreta con la opcion [csrf-secret|COMMENT]:

  [./symfony generate:app frontend --csrf-secret=UniqueSecret|INFO]

Adicional puedes personalizar el esqueleto por defecto usado por la tarea 
en el directorio [%sf_data_dir%/skeleton/app|COMMENT].

Finalmente, puedes agregar rutas por defecto para Bootstrap3. Nota: no olvides
crear el modulo Bootstrap3 tambien y obviamente este parametro viene 
desactivado (false) por defecto. El ejemplo de abajo indica que se va a activar
las rutas en el routing.yml

  [./symfony generate:app frontend --generar-rutas-bootstrap3=true|INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $app = $arguments['app'];

        // Validar el nombre de la aplicacion
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $app)) {
            throw new sfCommandException(sprintf('El nombre de la aplicacion "%s" is invalido.', $app));
        }

        $appDir = sfConfig::get('sf_apps_dir').'/'.$app;

        if (is_dir($appDir)) {
            throw new sfCommandException(sprintf('La aplicacion "%s" ya existe.', $appDir));
        }

        if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/app')) {
            $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/app';
        } else {
            $skeletonDir = dirname(__FILE__).'/skeleton/app';
        }

        // Crear una estructura basica de la aplicacion
        $finder = sfFinder::type('any')->discard('.sf');
        $this->getFilesystem()->mirror($skeletonDir.'/app', $appDir, $finder);

        // Crea $app.php o index.php si es nuestra primera aplicacion        
        $indexName = !(!file_exists(sfConfig::get('sf_web_dir').'/index.php')) ? $app : 'index';

        if (true === $options['csrf-secret']) {
            $options['csrf-secret'] = sha1(rand(111111111, 99999999).getmypid());
        }

        // Guarda el valor no_script_name en settings.yml para el ambiente de produccion
        $finder = sfFinder::type('file')->name('settings.yml');
        $this->getFilesystem()->replaceTokens($finder->in($appDir.'/config'), '##', '##', array(
            'NO_SCRIPT_NAME'    => !file_exists(sfConfig::get('sf_web_dir').'/index.php') ? 'true' : 'false',
            'CSRF_SECRET'       => sfYamlInline::dump(sfYamlInline::parseScalar($options['csrf-secret'])),
            'ESCAPING_STRATEGY' => sfYamlInline::dump((boolean) sfYamlInline::parseScalar($options['escaping-strategy'])),
            'USE_DATABASE'      => sfConfig::has('sf_orm') ? 'true' : 'false',
        ));
        
        /* Eligiendo o no rutas Bootstrap3 */
        if (false === $options['routing-bootstrap3']) {
            $this->getFilesystem()->copy(
                $appDir.'/config/routing_sin_bootstrap3.yml',
                $appDir.'/config/routing.yml'
            );
        } else {
            $this->getFilesystem()->copy(
                $appDir.'/config/routing_con_bootstrap3.yml',
                $appDir.'/config/routing.yml'
            );
        }
        
        // Borrando routings no necesarios
        $this->getFilesystem()->remove($appDir.'/config/routing_con_bootstrap3.yml');
        $this->getFilesystem()->remove($appDir.'/config/routing_sin_bootstrap3.yml');

        $this->getFilesystem()->copy($skeletonDir.'/web/index.php', sfConfig::get('sf_web_dir').'/'.$indexName.'.php');
        $this->getFilesystem()->copy($skeletonDir.'/web/index.php', sfConfig::get('sf_web_dir').'/'.$app.'_dev.php');

        $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);
        $reem = array(
            'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
            'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Tu nombre aqui',
            'FECHA_Y_HORA' => $this->getDateAndTimeInEs(date('Y-m-d H:i:s')),
            'APP_NAME'     => $app,
            'ENVIRONMENT'  => 'prod',
            'IS_DEBUG'     => 'false',
            'IP_CHECK'     => '',
        );
        $this->getFilesystem()->replaceTokens(sfConfig::get('sf_web_dir').'/'.$indexName.'.php', '##', '##', $reem);

        $reem['ENVIRONMENT'] = 'dev';
        $reem['IS_DEBUG']    = 'true';
        $reem['IP_CHECK']    = '// Esta comprobacion impide el acceso a los controladores frontales de depuracion que se '.PHP_EOL.
                               '// despliegan por accidente a los servidores de produccion.'.PHP_EOL.
                               '// Sientete libre de suprimir esta comprobacion, extenderlo o hacer algo mas sofisticado.'.PHP_EOL.
                               'if (!in_array(@$_SERVER[\'REMOTE_ADDR\'], array(\'127.0.0.1\', \'::1\'))) {'.PHP_EOL.
                               '    die(\'No esta permitido acceder a este archivo. Revisa el archivo \'.basename(__FILE__).\' para mas informacion.\');'.PHP_EOL.
                               '}'.PHP_EOL;
        $this->getFilesystem()->replaceTokens(sfConfig::get('sf_web_dir').'/'.$app.'_dev.php', '##', '##', $reem);

        $this->getFilesystem()->rename($appDir.'/config/ApplicationConfiguration.class.php', $appDir.'/config/'.$app.'Configuration.class.php');
        $this->getFilesystem()->replaceTokens($appDir.'/config/'.$app.'Configuration.class.php', '##', '##', array('APP_NAME' => $app));

        $fixPerms = new sfProjectPermissionsTask($this->dispatcher, $this->formatter);
        $fixPerms->setCommandApplication($this->commandApplication);
        $fixPerms->setConfiguration($this->configuration);
        $fixPerms->run();

        // Crea el directorio test
        //$this->getFilesystem()->mkdirs(sfConfig::get('sf_test_dir').'/functional/'.$app);
    }

}