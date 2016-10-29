<?php
    $objTabla = Doctrine_Core::getTable($this->getModelClass());
    $columns = $arrayBlob = array();
    foreach (array_diff(array_keys($objTabla->getColumns()), array()) as $name) {
        $columns[] = new sfDoctrineColumn($name, $objTabla);
    }
    foreach ($columns as $column) {
        if ('blob' == $column->getDoctrineType()) {
            $arrayBlob[] = $column->getDoctrineType();
        }
    }
?>
    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
<?php if (!empty($arrayBlob)): ?>
            // necesito guardar los datos para despues actualizar el objeto form
            $values = $form->getValues();
            $<?php echo $this->getSingularName() ?> = $form->save();
<?php else: ?>
            $<?php echo $this->getSingularName() ?> = $form->save();
<?php endif; ?>
<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
            $this->redirect('@<?php echo $this->getUrlForAction('edit') ?>?<?php echo $this->getPrimaryKeyUrlParams() ?>);
<?php else: ?>
<?php   if (!empty($arrayBlob)): ?>
            // actualizo el objeto despues de guardar el formulario para poder
            // poner la imagen en la BDD como contenido binario, ya que por 
            // defecto symfony 1.4 no lo hace...
            foreach ($values as $key => $value)
                if (is_object($value))
                    $<?php echo $this->getSingularName() ?>->$key = file_get_contents($value->getTempName());
            $<?php echo $this->getSingularName() ?>->save();
<?php   endif; ?>
            $this->redirect('<?php echo $this->getModuleName() ?>/edit?<?php echo $this->getPrimaryKeyUrlParams() ?>);
<?php endif; ?>
        }
    }
