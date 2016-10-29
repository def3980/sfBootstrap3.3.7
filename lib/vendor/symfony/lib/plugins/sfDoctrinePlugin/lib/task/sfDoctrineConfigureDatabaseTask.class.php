<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Martes, 07 Octubre 2014 15:59:59
 * + ------------------------------------------------------------------- +
 */

/**
 * Configura la conexion de la base de datos.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineConfigureDatabaseTask.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfDoctrineConfigureDatabaseTask extends sfBaseTask {

    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('dsn', sfCommandArgument::REQUIRED, 'La base de datos dsn (nombre fuente de base de datos)'),
            new sfCommandArgument('username', sfCommandArgument::OPTIONAL, 'El nombre de usuario de la base de datos', 'root'),
            new sfCommandArgument('password', sfCommandArgument::OPTIONAL, 'La contrasenia de la base de datos'),
        ));

        $this->addOptions(array(
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'El ambiente de desarrollo', 'all'),
            new sfCommandOption('name', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la conexion', 'doctrine'),
            new sfCommandOption('class', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la clase de la base de datos', 'sfDoctrineDatabase'),
            new sfCommandOption('app', null, sfCommandOption::PARAMETER_OPTIONAL, 'El nombre de la aplicacion', null),
        ));

        $this->namespace           = 'configure';
        $this->name                = 'database';
        $this->briefDescription    = '>> Configura DSN (nombre fuente de base de datos) de la base de datos';
        $this->detailedDescription = <<<EOF
La tarea [configure:database|INFO] configura el DSN de la base de datos 
para el proyecto:

  [./symfony configure:database mysql:host=localhost;dbname=example root P4ssW0rd_difici1isim4|INFO]

o como dice en la documentacion de symfony-legacy
                
  [./symfony configure:database "mysql:host=localhost;dbname=example root P4ssW0rd_difici1isim4"|INFO]

Por defecto, la tarea cambia la configuracion para todos los ambientes. 
Si quieres cambiar de DSN para un ambiente especifico, usa la opcion 
[env|COMMENT]:

  [./symfony configure:database --env=dev mysql:host=localhost;dbname=example_dev root P4ssW0rd_difici1isim4|INFO]

Para cambiar la configuracion para una aplicacion especifica, usa la opcion 
[app|COMMENT]:

  [./symfony configure:database --app=frontend mysql:host=localhost;dbname=example root P4ssW0rd_difici1isim4|INFO]

Incluso puedes especificar el nombre de la conexion y el nombre de la 
clase de la base de datos:

  [./symfony configure:database --name=main --class=ProjectDatabase mysql:host=localhost;dbname=example root P4ssW0rd_difici1isim4|INFO]
EOF;
  }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        // actualiza el archivo databases.yml
        if (null !== $options['app']) {
            $file = sfConfig::get('sf_apps_dir').'/'.$options['app'].'/config/databases.yml';
        } else {
            $file = sfConfig::get('sf_config_dir').'/databases.yml';
        }

        $config[$options['env']][$options['name']] = array(
            'class' => $options['class'],
            'param' => array_merge(
                isset($config[$options['env']][$options['name']]['param']) 
                ? $config[$options['env']][$options['name']]['param'] 
                : array(), 
                array(
                    'dsn' => $arguments['dsn'], 
                    'username' => $arguments['username'], 
                    'password' => $arguments['password']
                )
            ),
        );

        $acc = $pos = $fecha = $res = ""; $cont = 0; $reem = array();
        foreach (file($file) as $k => $v):
            if ("" !== $this->ubicarEntre($v, '"', '"')) {
                $acc = $this->ubicarEntre($v, '"', '"');
                break; // encuentra y sale del bucle
            }
        endforeach;
        if ("" !== $acc) {
            foreach (file($file) as $cont_k => $cont_v):
                if ("" !== $this->ubicarEntre($cont_v, '"', '"')) {
                    $cont += 1;
                    if ($cont > 1) {
                        $fecha = $this->ubicarEntre($cont_v, '"', '"');
                        $cont = 0;
                        break; // encuentra y sale del bucle                    
                    }
                }
            endforeach;
            foreach (file($file) as $pos_k => $pos_v):
                if ("" !== $this->ubicarEntre($pos_v, 'all', 'all')) { // aunque sea me encuentra "all:", pero me SIRVE
                    $pos = $pos_k;
                    break; // encuentra y sale del bucle
                }
            endforeach;

            foreach (file($file) as $reem_k => $reem_v):
                if (false !== strpos($reem_v, '"'.$acc.'"')) {
                    $reem[] = str_replace('"'.$acc.'"', '"'.$this->numeroDAcceso(($acc * 1) + 1).'"', $reem_v);
                } elseif (false !== strpos($reem_v, '"'.$fecha.'"')) {
                    $reem[] = str_replace('"'.$fecha.'"', '"'.date('Y-m-d H:i:s').'"', $reem_v);
                } else {
                    $reem[] = $reem_v;
                }
            endforeach;

            for ( $i = 0 ; $i <= ($pos_k - 1) ; $i += 1 ):
                $res .= $reem[$i];
            endfor;

            file_put_contents($file, $res.sfYaml::dump($config, 4));

            $this->logSection('doctrine DSN', sprintf('Esquema (schema) yaml, actualizado...'));
        } else {
            file_put_contents($file, sfYaml::dump($config, 4));
        }
    }
    
    protected function ubicarEntre($contenido, $inicio, $fin) {
        $cadena = explode($inicio, $contenido);
        if (isset($cadena[1])) {
            $cadena = explode($fin, $cadena[1]);
            return reset($cadena);
        }

        return '';
    }
    
    protected function numeroDAcceso($valor) {
        $no = 0;
        switch (true):
            case $valor < 10: $no = '00'.$valor; break;
            case $valor < 100: $no = '0'.$valor; break;
            case $valor < 1000: $no = ''.$valor; break;
        endswitch;
        return $no;
    }

}