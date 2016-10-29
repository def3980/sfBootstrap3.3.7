    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
<?php if (isset($this->params['with_doctrine_route']) && $this->params['with_doctrine_route']): ?>
        $this->getRoute()->getObject()->delete();
<?php else: ?>
        $<?php echo $this->getSingularName() ?> = Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->find(array(
            <?php echo $this->getRetrieveByPkParamsForAction(43).PHP_EOL ?>
        ));
        $this->forward404Unless(
            $<?php echo $this->getSingularName() ?>,
            sprintf('El objecto <?php echo $this->getSingularName() ?> con el parametro (%s), no existe.', <?php echo $this->getRetrieveByPkParamsForAction(43) ?>)
        );
        $<?php echo $this->getSingularName() ?>->delete();
<?php endif; ?>
<?php if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
        $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
<?php else: ?>
        $this->redirect('<?php echo $this->getModuleName() ?>/index');
<?php endif; ?>
    }
