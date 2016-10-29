<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * + ------------------------------------------------------------------- +
 * Añadiendo nuevas formas a lo ya optimizado. Por Oswaldo Rojas un
 * Jueves, 25 Septiembre 2014 11:57:43
 * + ------------------------------------------------------------------- +
 */

/**
 * sfMySQLDatabase provee conectividad para el motor de base de datos MySQL.
 *
 * <b>Parametros opcionales:</b>
 *
 * # <b>database</b>   - [none]      - El nombre de la base de datos.
 * # <b>host</b>       - [localhost] - El host de la base de datos.
 * # <b>username</b>   - [none]      - El nombre de usuario de la base de datos.
 * # <b>password</b>   - [none]      - La contraseña de la base de datos.
 * # <b>persistent</b> - [No]        - Indica que la conexion debe ser persistente.
 *
 * @package    symfony
 * @subpackage database
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 * @version    SVN: $Id: sfMySQLDatabase.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfMySQLDatabase extends sfDatabase {

    /**
     * Connects to the database.
     *
     * @throws <b>sfDatabaseException</b> If a connection could not be created
     */
    public function connect() {
        $database = $this->getParameter('database');
        $host     = $this->getParameter('host', 'localhost');
        $password = $this->getParameter('password');
        $username = $this->getParameter('username');
        $encoding = $this->getParameter('encoding');

        // Veamos si nosotros necesitamos una conexion persistente
        $connect = $this->getConnectMethod($this->getParameter('persistent', false));
        if ($password == null) {
            if ($username == null) {
                $this->connection = @$connect($host);
            } else {
                $this->connection = @$connect($host, $username);
            }
        } else {
            $this->connection = @$connect($host, $username, $password);
        }

        // Se asegura la conexion se realizo
        if ($this->connection === false) {
            // the connection's foobar'd
            // la conexion ???
            throw new sfDatabaseException('No se pudo crear la conexion con MySQLDatabase.');
        }

        // seleccionamos nuestra base de datos
        if ($this->selectDatabase($database)) {
            // no se puede seleccionar la base de datos
            throw new sfDatabaseException(sprintf('No se pudo seleccionar la MySQLDatabase "%s".', $database));
        }

        // Guardamos la codificacion si es que esta especificada
        if ($encoding) {
            @mysql_query("SET NAMES '".$encoding."'", $this->connection);
        }

        // copiamos la conexion a la fuente
        $this->resource = $this->connection;
    }

    /**
     * Retorna el metodo apropiado de conexion.
     *
     * @param bool $persistent wether persistent connections are use or not
     * @return string name of connect method.
     */
    protected function getConnectMethod($persistent) {
        return $persistent ? 'mysql_pconnect' : 'mysql_connect';
    }
  
    /**
     * Selecciona la base de datos a ser usada en esta conexion
     *
     * @param string $database Nombre de la base de datos a ser conectada
     *
     * @return bool true Si este fue satisfactorio
     */
    protected function selectDatabase($database) {
        return ($database != null && !@mysql_select_db($database, $this->connection));
    }

    /**
     * Execute the shutdown procedure
     * Ejecuta el procedimiento de apagado
     *
     * @return void
     *
     * @throws <b>sfDatabaseException</b> Si un error ocurre mientras se esta apagando la base de datos
     */
    public function shutdown() {
        if ($this->connection != null) {
            @mysql_close($this->connection);
        }
    }

}