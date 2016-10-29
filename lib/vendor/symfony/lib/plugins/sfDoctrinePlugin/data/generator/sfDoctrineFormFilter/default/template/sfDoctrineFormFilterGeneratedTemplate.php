[?php

/**
 * Fecha creacion : "<?=$this->_fechaYHora?>"
 * 
 * Acciones realizadas:
 * - Veces ejecutado doctrine:build-forms            : "<?php echo !$this->_actualizarFechaYHora
                                                                   ? str_repeat('0', 6) 
                                                                   : $this->_numeracion ?>"
 * - Ultima vez que se actualizo la clase formulario : "<?php echo !$this->_actualizarFechaYHora
                                                                   ? 'yyyy-mm-dd_hh:mm:ss'
                                                                   : date('Y-m-d H:i:s') ?>"
 */

/**
 * <?php echo $this->table->getOption('name') ?> clase base de formulario para el filtrado.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class Base<?php echo $this->table->getOption('name') ?>FormFilter extends <?php echo $this->getFormClassToExtend() ?> {

    public function setup() {
        $this->setWidgets(array(
<?php foreach ($this->getColumns() as $column): ?>
<?php if ($column->isPrimaryKey()) continue ?>
            '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>),
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
            '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>')),
<?php endforeach; ?>
        ));

        $this->setValidators(array(
<?php foreach ($this->getColumns() as $column): ?>
<?php if ($column->isPrimaryKey()) continue ?>
            '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => <?php echo $this->getValidatorForColumn($column) ?>,
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
            '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false)),
<?php endforeach; ?>
        ));

        $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>_filters[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->setupInheritance();

        parent::setup();
    }

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    public function add<?php echo sfInflector::camelize($relation['alias']) ?>ListColumnQuery(Doctrine_Query $query, $field, $values) {
        if (!is_array($values)) {
            $values = array($values);
        }

        if (!count($values)) {
            return;
        }

        $query->leftJoin($query->getRootAlias().'.<?php echo $relation['refTable']->getOption('name') ?> <?php echo $relation['refTable']->getOption('name') ?>')
              ->andWhereIn('<?php echo $relation['refTable']->getOption('name') ?>.<?php echo $relation->getForeignFieldName() ?>', $values);
    }

<?php endforeach; ?>
    public function getModelName() {
        return '<?php echo $this->modelName ?>';
    }

    public function getFields() {
        return array(
<?php foreach ($this->getColumns() as $column): ?>
                '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => '<?php echo $this->getType($column) ?>',
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
                '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => 'ManyKey',
<?php endforeach; ?>
               );
    }

}