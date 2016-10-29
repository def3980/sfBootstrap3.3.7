<?php

/**
 * Por Oswaldo Rojas
 * Empezado a modificar un Miércoles, 20 Enero 2016 21:13:20
 * Terminado un Miércoles, 20 Enero 2016 21:26:29
 */

function is_cli() { return !isset($_SERVER['HTTP_HOST']); }

/**
 * Revisando la configuracion.
 */
function check($boolean, $message, $help = '', $fatal = false) {
    echo $boolean 
         ? "   OK   " 
         : sprintf("[%s] ", $fatal ? 'ERROR' : 'ADVER');
    echo sprintf("$message%s\n", $boolean ? '' : ': FALLO');

    if (!$boolean) {
        echo "        [$help]".PHP_EOL;
        echo $fatal 
             ? die('Debe arreglar los problemas antes de pasar la revision.'.PHP_EOL) 
             : '';
    }
}

/**
 * Obtiene la ruta del archivo "php.ini" usado por el actual interprete
 * de PHP
 *
 * @return string ruta php.ini
 */
function get_ini_path() {
    if ($path = get_cfg_var('cfg_file_path')) {
        return $path;
    }

    return 'ADVERTENCIA: no esta usando un archivo php.ini o no esta instalado '
           . 'el lenguaje PHP';
}

if (!is_cli()) {
    echo '<html><body><pre>';
}

echo "+ --------------------------------------------------- +".PHP_EOL;
echo "|                                                     |".PHP_EOL;
echo "|    Revision de requerimientos de Symfony v1.4.20    |".PHP_EOL;
echo "|                                                     |".PHP_EOL;
echo "+ --------------------------------------------------- +".str_repeat(PHP_EOL, 2);

echo sprintf("PHP se basa en el archivo php.ini ubicado en: %s".str_repeat(PHP_EOL, 2), get_ini_path());

if (is_cli()) {
    echo "+ -- ADVERTENCIA ------------------------------------ +".PHP_EOL;
    echo "|                                                     |".PHP_EOL;
    echo "|  La linea de comandos (CLI) de PHP puede usar un    |".PHP_EOL;
    echo "|  archivo php.ini diferente del que usa su servidor  |".PHP_EOL;
    echo "|  web";
    if ('\\' == DIRECTORY_SEPARATOR) {
        echo " (especilamente en plataformas windows).        |".PHP_EOL;
    } else {
        echo ".                                               |".PHP_EOL;
    }
    echo "|  Si este es el caso, por favor correr esta utilidad |".PHP_EOL;
    echo "|  desde su servidor web.                             |".PHP_EOL;
    echo "|                                                     |".PHP_EOL;
    echo "+ --------------------------------------------------- +".str_repeat(PHP_EOL, 2);
}

// requerimientos obligatorios
echo "+ -- REQUERIMIENTOS OBLIGATORIOS -------------------- +".str_repeat(PHP_EOL, 2);
check(
    version_compare(phpversion(), '5.2.4', '>='), 
    sprintf('PHP >= v5.2.4 (Actual: v%s)', phpversion()), 
    'Version actual v'.phpversion(), 
    true
);
echo str_repeat(PHP_EOL, 1);
echo "+ --------------------------------------------------- +".str_repeat(PHP_EOL, 2);

// requerimientos opcionales
echo "+ -- REQUERIMIENTOS OPCIONALES ---------------------- +".str_repeat(PHP_EOL, 2);
check(
    class_exists('PDO'),
    'PDO instalado',
    'Instalar PDO (obligatorio para Doctrine & Propel)',
    false
);
if (class_exists('PDO')) {
    $drivers = PDO::getAvailableDrivers();
    check(
        count($drivers), 
        "PDO drivers: ".implode(', ', $drivers), 
        'Instalar PDO (obligatorio para Doctrine & Propel)'
    );
}
check(class_exists('DomDocument'), 'PHP-XML module instalado', 'Instalar y activar el modulo php-xml (requerido en Propel)', false);
check(class_exists('XSLTProcessor'), 'XSL module instalado', 'Instalar y activar el modulo XSL (recomendado para Propel)', false);
check(function_exists('token_get_all'), 'token_get_all() disponible', 'Instalar y activar la extension Tokenizer (altamente recomendado)', false);
check(function_exists('mb_strlen'), 'mb_strlen() disponible', 'Instalar y activar la extension mbstring', false);
check(function_exists('iconv'), 'iconv() disponible', 'Instalar y activar la extension iconv', false);
check(function_exists('utf8_decode'), 'utf8_decode() disponible', 'Instalar y activar la extension XML', false);
check(function_exists('posix_isatty'), 'posix_isatty() disponible', 'Instalar y activar la extension php_posix (colorea el CLI)', false);
$accelerator =  (function_exists('apc_store') && ini_get('apc.enabled'))
                || function_exists('eaccelerator_put') && ini_get('eaccelerator.enable')
                || function_exists('xcache_set');
check($accelerator, 'Acelerador PHP instalado', 'Instalar acelerador PHP, ejemplo: APC (muy recomendado)', false);
check(!ini_get('short_open_tag'), 'php.ini tiene short_open_tag en off', 'Cambie short_open_tag a off en php.ini', false);
check(!ini_get('magic_quotes_gpc'), 'php.ini tiene magic_quotes_gpc en off', 'Cambie magic_quotes_gpc a off en php.ini', false);
check(!ini_get('register_globals'), 'php.ini tiene register_globals en off', 'Cambie register_globals a off en php.ini', false);
check(!ini_get('session.auto_start'), 'php.ini tiene session.auto_start en off', 'Cambie session.auto_start a off en php.ini', false);
check(version_compare(phpversion(), '5.2.9', '!='), 'PHP version no es v5.2.9', 'PHP v5.2.9 tiene fallas en array_unique() y sfToolkit::arrayDeepMerge(). Usar PHP v5.2.10 [Ticket #6211]', false);
echo str_repeat(PHP_EOL, 1);
echo "+ --------------------------------------------------- +".str_repeat(PHP_EOL, 2);
if (!is_cli()) {
    echo '</pre></body></html>';
}