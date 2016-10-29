<?php

/**
 * + ------------------------------------------------------------------- +
 * Por Oswaldo Rojas
 * Añadiendo nuevas formas a lo ya optimizado.
 * Viernes, 21 Octubre 2016 21:14:22
 * + ------------------------------------------------------------------- +
 */

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Doctrine filter form generator.
 *
 * This class generates a Doctrine filter forms.
 *
 * @package    symfony
 * @subpackage generator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGenerator.class.php 27842 2010-02-10 19:42:03Z Kris.Wallsmith $
 */
class sfDoctrineFormFilterGenerator extends sfDoctrineFormGenerator {

    /**
     * Para el calculo de fechas en español
     *
     * @var array
     */
    public
        $_dias = array(
            'domingo', 
            'lunes', 
            'martes', 
            'miercoles', 
            'jueves', 
            'viernes', 
            'sabado'
        ),
        $_diasAbreviados = array(),
        $_meses = array(
            'enero', 
            'febrero', 
            'marzo', 
            'abril', 
            'mayo', 
            'junio',
            'julio', 
            'agosto', 
            'septiembre', 
            'octubre', 
            'noviembre', 
            'diciembre'
        ),
        $_mesesAbreviados = array();

    /**
     * Initializes the current sfGenerator instance.
     *
     * @param sfGeneratorManager $generatorManager A sfGeneratorManager instance
     */
    public function initialize(sfGeneratorManager $generatorManager) {
        parent::initialize($generatorManager);

        $this->setGeneratorClass('sfDoctrineFormFilter');
    }

    /**
     * Generates classes and templates in cache.
     *
     * @param array $params The parameters
     *
     * @return string The data to put in configuration cache
     */
    public function generate($params = array()) {
        $this->params = $params;

        if (!isset($this->params['model_dir_name'])) {
            $this->params['model_dir_name'] = 'model';
        }

        if (!isset($this->params['filter_dir_name'])) {
            $this->params['filter_dir_name'] = 'filter';
        }

        $models = $this->loadModels();

        // create the project base class for all forms
        $file = sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php';
        if (!file_exists($file)) {
            if (!is_dir($directory = dirname($file))) {
                mkdir($directory, 0777, true);
            }
              $this->_fechaYHora = $this->obtenerFechaYHoraEnEsp(date('Y-m-d H:i:s'));
            file_put_contents($file, $this->evalTemplate('sfDoctrineFormFilterBaseTemplate.php'));
        }

        $pluginPaths = $this->generatorManager->getConfiguration()->getAllPluginPaths();

        // create a form class for every Doctrine class
        foreach ($models as $model) {
            $this->table = Doctrine_Core::getTable($model);
            $this->modelName = $model;

            $baseDir = sfConfig::get('sf_lib_dir') . '/filter/doctrine';

            $isPluginModel = $this->isPluginModel($model);
            if ($isPluginModel) {
                $pluginName = $this->getPluginNameForModel($model);
                $baseDir .= '/' . $pluginName;
            }

            if (!is_dir($baseDir.'/base')) {
                mkdir($baseDir.'/base', 0777, true);
            }

            /**
             * Verificando y cargando variables para cuando el archivo Base*FormFilter.class.php
             * existe y necesito su fecha y hora de creacion, para luego ser utilizado en
             * la plantilla 
             * sfDoctrinePlugin/data/generator/sfDoctrineFormFilter/default/template/sfDoctrineFormFilterGeneratedTemplate.php
             * 
             * Siempre y cuando el archivo en cuestion exista
             */
            $this->_actualizarFechaYHora = false; $cont = 0;
            if (is_file($baseDir."/base/Base{$model}FormFilter.class.php")) {
                $this->_fechaYHora = "";
                foreach (file($baseDir."/base/Base{$model}FormFilter.class.php") as $k => $v) {
                    if (!empty($this->ubicarEntre($v, '"', '"'))) {
                        if (($cont += 1) > 1) {
                            $this->_numeracion = $this->numeroDAcceso(($this->ubicarEntre($v, '"', '"') * 1) + 1);
                            $this->_actualizarFechaYHora = true;
                            $cont = 0;
                            break;
                        } else {
                            $this->_fechaYHora = $this->ubicarEntre($v, '"', '"');
                        }
                    }
                }
            }
            /* + ------------------------------------------------------------------------------------------ + */

            file_put_contents($baseDir.'/base/Base'.$model.'FormFilter.class.php', $this->evalTemplate(null === $this->getParentModel() ? 'sfDoctrineFormFilterGeneratedTemplate.php' : 'sfDoctrineFormFilterGeneratedInheritanceTemplate.php'));

            if ($isPluginModel) {
                $pluginBaseDir = $pluginPaths[$pluginName].'/lib/filter/doctrine';
                if (!file_exists($classFile = $pluginBaseDir.'/Plugin'.$model.'FormFilter.class.php')) {
                    if (!is_dir($pluginBaseDir)) {
                        mkdir($pluginBaseDir, 0777, true);
                    }
                    file_put_contents($classFile, $this->evalTemplate('sfDoctrineFormFilterPluginTemplate.php'));
                }
            }
            if (!file_exists($classFile = $baseDir.'/'.$model.'FormFilter.class.php')) {
                if ($isPluginModel) {
                    file_put_contents($classFile, $this->evalTemplate('sfDoctrinePluginFormFilterTemplate.php'));
                } else {
                    /**
                     * Verificando y cargando variables para cuando el archivo Base*FormFilter.class.php
                     * existe y necesito su fecha y hora de creacion, para luego ser utilizado en
                     * la plantilla 
                     * sfDoctrinePlugin/data/generator/sfDoctrineFormFilter/default/template/sfDoctrineFormFilterTemplate.php
                     * 
                     * Siempre y cuando el archivo en cuestion exista
                     */
                    $this->_actualizarFechaYHora = false; // me sirve para numeracion y hora
                    if (is_file($classFile)) {
                        $this->_existeArchivo = false;
                        $this->_fechaYHora = "";
                        $cont = 0;
                        foreach (file($classFile) as $k => $v) {
                            if (!empty($this->ubicarEntre($v, '"', '"'))) {
                                if (($cont += 1) > 1) {
                                    $this->_numeracion = $this->numeroDAcceso(($this->ubicarEntre($v, '"', '"') * 1) + 1);
                                    $this->_actualizarFechaYHora = true;
                                    $cont = 0;
                                    break;
                                } else {
                                    $this->_existeArchivo = true;
                                    $this->_fechaYHora = $this->ubicarEntre($v, '"', '"');
                                }
                            }
                        }
                    } // si es false utilizo $this->_fechaYHora de la condicion que esta mas arriba
                    /* + ------------------------------------------------------------------------------------------ + */
                    file_put_contents($classFile, $this->evalTemplate('sfDoctrineFormFilterTemplate.php'));
                }
            }
        }
    }

    /**
     * Returns a sfWidgetForm class name for a given column.
     *
     * @param  sfDoctrineColumn $column
     * @return string    The name of a subclass of sfWidgetForm
     */
    public function getWidgetClassForColumn($column) {
        switch ($column->getDoctrineType()) {
            case 'boolean':
                $name = 'Choice';
            break;
            case 'date':
            case 'datetime':
            case 'timestamp':
                $name = 'FilterDate';
            break;
            case 'enum':
                $name = 'Choice';
            break;
            default:
                $name = 'FilterInput';
        }

        if ($column->isForeignKey()) {
            $name = 'DoctrineChoice';
        }

        return sprintf('sfWidgetForm%s', $name);
    }

    /**
     * Returns a PHP string representing options to pass to a widget for a given column.
     *
     * @param  sfDoctrineColumn $column
     * @return string    The options to pass to the widget as a PHP string
     */
    public function getWidgetOptionsForColumn($column) {
        $options = array();

        $withEmpty = $column->isNotNull() && !$column->isForeignKey() ? array("'with_empty' => false") : array();
        switch ($column->getDoctrineType()) {
            case 'boolean':
                $options[] = "'choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no')";
            break;
            case 'date':
            case 'datetime':
            case 'timestamp':
                $options[] = "'from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate()";
                $options = array_merge($options, $withEmpty);
            break;
            case 'enum':
                $values = array('' => '');
                $values = array_merge($values, $column['values']);
                $values = array_combine($values, $values);
                $options[] = "'choices' => ".$this->arrayExport($values);
            break;
            default:
                $options = array_merge($options, $withEmpty);
        }

        if ($column->isForeignKey()) {
            $options[] = sprintf('\'model\' => $this->getRelatedModelName(\'%s\'), \'add_empty\' => true', $column->getRelationKey('alias'));
        }

        return count($options) ? sprintf('array(%s)', implode(', ', $options)) : '';
    }

    /**
     * Returns a sfValidator class name for a given column.
     *
     * @param  sfDoctrineColumn $column
     * @return string    The name of a subclass of sfValidator
     */
    public function getValidatorClassForColumn($column) {
        switch ($column->getDoctrineType()) {
            case 'boolean':
                $name = 'Choice';
            break;
            case 'float':
            case 'decimal':
                $name = 'Number';
            break;
            case 'integer':
                $name = 'Integer';
            break;
            case 'date':
            case 'datetime':
            case 'timestamp':
                $name = 'DateRange';
            break;
            case 'enum':
                $name = 'Choice';
            break;
            default:
                $name = 'Pass';
        }

        if ($column->isPrimarykey() || $column->isForeignKey()) {
            $name = 'DoctrineChoice';
        }

        return sprintf('sfValidator%s', $name);
    }

    /**
     * Returns a PHP string representing options to pass to a validator for a given column.
     *
     * @param  sfDoctrineColumn $column
     * @return string    The options to pass to the validator as a PHP string
     */
    public function getValidatorOptionsForColumn($column) {
        $options = array('\'required\' => false');

        if ($column->isForeignKey()) {
            $columns = $column->getForeignTable()->getColumns();
            foreach ($columns as $name => $col) {
                if (isset($col['primary']) && $col['primary']) {
                    break;
                }
            }

            $options[] = sprintf('\'model\' => $this->getRelatedModelName(\'%s\'), \'column\' => \'%s\'', $column->getRelationKey('alias'), $column->getForeignTable()->getFieldName($name));
        } else if ($column->isPrimaryKey()) {
            $options[] = sprintf('\'model\' => \'%s\', \'column\' => \'%s\'', $this->table->getOption('name'), $column->getFieldName());
        } else {
            switch ($column->getDoctrineType()) {
                case 'boolean':
                    $options[] = "'choices' => array('', 1, 0)";
                break;
                case 'date':
                    $options[] = "'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false))";
                break;
                case 'datetime':
                case 'timestamp':
                    $options[] = "'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59'))";
                break;
                case 'enum':
                    $values = array_combine($column['values'], $column['values']);
                    $options[] = "'choices' => ".$this->arrayExport($values);
                break;
            }
        }

        return count($options) ? sprintf('array(%s)', implode(', ', $options)) : '';
    }

    public function getValidatorForColumn($column) {
        $format = 'new %s(%s)';

        if (in_array($class = $this->getValidatorClassForColumn($column), array('sfValidatorInteger', 'sfValidatorNumber'))) {
            $format = 'new sfValidatorSchemaFilter(\'text\', new %s(%s))';
        }

        return sprintf($format, $class, $this->getValidatorOptionsForColumn($column));
    }

    public function getType($column) {
        if ($column->isForeignKey()) {
            return 'ForeignKey';
        }

        switch ($column->getDoctrineType()) {
            case 'enum':
                return 'Enum';
            case 'boolean':
                return 'Boolean';
            case 'date':
            case 'datetime':
            case 'timestamp':
                return 'Date';
            case 'integer':
            case 'decimal':
            case 'float':
                return 'Number';
            default:
                return 'Text';
        }
    }

    /**
     * Array export. Export array to formatted php code
     *
     * @param array $values
     * @return string $php
     */
    protected function arrayExport($values) {
        $php = var_export($values, true);
        $php = str_replace("\n", '', $php);
        $php = str_replace('array (  ', 'array(', $php);
        $php = str_replace(',)', ')', $php);
        $php = str_replace('  ', ' ', $php);

        return $php;
    }

    /**
     * Filter out models that have disabled generation of form classes
     *
     * @return array $models Array of models to generate forms for
     */
    protected function filterModels($models) {
        foreach ($models as $key => $model) {
            $table = Doctrine_Core::getTable($model);
            $symfonyOptions = (array) $table->getOption('symfony');

            if ($table->isGenerator()) {
                $symfonyOptions = array_merge((array) $table->getParentGenerator()->getOption('table')->getOption('symfony'), $symfonyOptions);
            }

            if (isset($symfonyOptions['filter']) && !$symfonyOptions['filter']) {
                unset($models[$key]);
            }
        }

        return $models;
    }

    /**
     * Get the name of the form class to extend based on the inheritance of the model
     *
     * @return string
     */
    public function getFormClassToExtend() {
        return null === ($model = $this->getParentModel()) ? 'BaseFormFilterDoctrine' : sprintf('%sFormFilter', $model);
    }

    /**
     * Ayuda a traducir la fecha y hora actual del sistema en formato español.
     *
     * Por Oswaldo Rojas ~ Sáb, 27 Sep 2014 13:53:12
     *
     * @param  date $date Recibe la fecha y hora actual ('Y-m-d H:i:s')
     * @param  boolean $complete Indica si los nombre de las fechas son
     * completas o abreviadas
     * @param  boolean $capital Indica los nombres de las fechas con letra 
     * capital
     * @return string Ej.: Lun, 01 Ene 1970 00:00:01
     */
    public function obtenerFechaYHoraEnEsp($date, $complete = true, $capital = true) {
       // Debido a que este proyecto de modificacion de symfony se realiza en
       // Ecuador se va a poner por default el timezone correspondiente, pero
       // sientete libre de cambiarlo a tu gusto (manualmente) ;-|
       date_default_timezone_set('America/Guayaquil');

       foreach ($this->_dias as $k => $v) { $this->_diasAbreviados[$k] = substr($v, 0, 3); }        
       array_unshift($this->_meses, '');
       foreach ($this->_meses as $k => $v) { $this->_mesesAbreviados[$k] = substr($v, 0, 3); }
       array_unshift($this->_mesesAbreviados, '');
       $dia    = explode('-', $date, 3);
       $year   = reset($dia);
       $month  = (string)(int)$dia[1];
       $day    = (string)(int)$dia[2];
       $hms    = explode(' ', $dia[2], 2);
       $time   = (string) $hms[1];
       $dias   = $this->_dias;
       $dAbr   = $this->_diasAbreviados;
       $tdia   = $dias[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
       $tAbr   = $dAbr[intval((date('w', mktime(0, 0, 0, $month, $day, $year))))];
       $meses  = $this->_meses;
       $mesAbr = $this->_mesesAbreviados;

       return $complete 
              ? ($capital 
                 ? ucfirst($tdia) 
                 : $tdia).", {$day} ".($capital 
                                       ? ucfirst($meses[$month]) 
                                       : $meses[$month])." {$year} {$time}"
              : ($capital 
                 ? ucfirst($tAbr) 
                 : $tAbr).", {$day} ".($capital 
                                       ? ucfirst($mesAbr[$month]) 
                                       : $mesAbr[$month])." {$year} {$time}";
    }

    /**
    * Funciones pequeñas para el registro de modificacion por parte del usuario
    * hacia el archivo ubicado en config/doctrine/schema.yml guardando fecha
    * y hora de actualizacion. Esta es mi personalizacion.
    */
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
           case $valor < 10: $no = '00000'.$valor; break;
           case $valor < 100: $no = '0000'.$valor; break;
           case $valor < 1000: $no = '000'.$valor; break;
           case $valor < 10000: $no = '00'.$valor; break;
           case $valor < 100000: $no = '0'.$valor; break;
           case $valor < 1000000: $no = ''.$valor; break;
       endswitch;
       /**
        * Exageradooo!!! 
        * mmm talvez, pero puede suceder.
        */
       return $no;
    }    
    /* ---------------------------------------------------------------------- */

}