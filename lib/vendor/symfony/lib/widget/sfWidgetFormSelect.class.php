<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSelect represents a select HTML tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSelect.class.php 30762 2010-08-25 12:33:33Z fabien $
 */
class sfWidgetFormSelect extends sfWidgetFormChoiceBase
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * choices:  An array of possible choices (required)
   *  * multiple: true if the select tag must allow multiple selections
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('multiple', false);
    
    $this->addOption('subtext', false);
    $this->addOption('models', null);
    $this->addOption('spaces', null);
    $this->addOption('field_to_show', null);
  }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value selected in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        if ($this->getOption('multiple')) {
            $attributes['multiple'] = 'multiple';
            if ('[]' != substr($name, -2)) {
                $name .= '[]';
            }
        }

        $choices = $this->getChoices();

        // acomodando las opciones subtext y espacios
        // para poder trabajar el html5 en los select
        if ($this->getOption('subtext')) {
            return $this->renderContentTag(
                        'select', 
                        PHP_EOL.str_repeat(' ', ($this->getOption('spaces') + 4)).implode(
                            PHP_EOL.str_repeat(' ', ($this->getOption('spaces') + 4)), 
                            $this->getOptionsForSelect($value, $choices)
                        ).PHP_EOL.str_repeat(' ', $this->getOption('spaces')), 
                        array_merge(array('name' => $name), $attributes)
                    );
        } else {
            return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value, $choices))."\n", array_merge(array('name' => $name), $attributes));
        }
    }

    /**
     * Returns an array of option tags for the given choices
     *
     * @param  string $value    The selected value
     * @param  array  $choices  An array of choices
     *
     * @return array  An array of option tags
     */
    protected function getOptionsForSelect($value, $choices) {
        $mainAttributes = $this->attributes;
        $this->attributes = array();

        if (!is_array($value)) {
          $value = array($value);
        }

        $value = array_map('strval', array_values($value));
        $value_set = array_flip($value);

        $options = array();
        foreach ($choices as $key => $option) {
            if (is_array($option)) {
              $options[] = $this->renderContentTag(
                                'optgroup', 
                                implode("\n", $this->getOptionsForSelect($value, $option)), 
                                array('label' => self::escapeOnce($key))
                           );
            } else {
                $attributes = array('value' => self::escapeOnce($key));
                if (isset($value_set[strval($key)])) {
                    $attributes['selected'] = 'selected';
                }
                if (null !== $this->getOption('models') && NULL !== $this->getOption('field_to_show')) {
                    // obteniendo la relacion con el padre
                    // OJO: field_to_show debe estar definido en el configure de cada clase de formulario
                    // en la ruta lib/form donde se requiere inscrustar el html5 y obviamente esta opcion
                    // debe contener el nombre del cambio en formato "CamelCase"
                    $clases = explode(',', $this->getOption('models'));
                    $clases = array_map('trim', $clases);
                    $base = substr(str_shuffle(str_repeat("abcDefghIjklmNopqrStuvWxyz", 3)), 0, 2);
                    $sql = Doctrine_Core::getTable(reset($clases))->createQuery($base);
                    $sql = $sql->where($base.'.id = '.$key);
                    foreach ($clases as $k => $v):
                        if (reset($clases) !== $v) {
                            $enTurno = substr(str_shuffle(str_repeat("abcDefghIjklmNopqrStuvWxyz", 3)), 0, 2);
                            $sql = $sql->innerJoin("{$base}.{$v} {$enTurno}");
                            $base = $enTurno;
                        }
                    endforeach;
                    $parent_field = $sql->execute(NULL, Doctrine_Core::HYDRATE_ARRAY_SHALLOW);
                    $parent_field = reset($parent_field);
                    $attributes['data-subtext'] = array_key_exists($this->getOption('field_to_show'), $parent_field)
                                                    ? $parent_field[$this->getOption('field_to_show')]
                                                    : '';
                }
                $options[] = $this->renderContentTag('option', self::escapeOnce($option), $attributes);
            }
        }

        $this->attributes = $mainAttributes;

        return $options;
    }

}
