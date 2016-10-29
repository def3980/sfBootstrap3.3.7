    public function executeIndex(sfWebRequest $request) {
<?php if (isset($this->params['with_doctrine_route']) && $this->params['with_doctrine_route']): ?>
        $this-><?php echo $this->getPluralName() ?> = $this->getRoute()->getObjects();
<?php else: ?>
        //$this-><?php echo $this->getPluralName() ?> = Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')
        //                        ->createQuery('a')
        //                            ->execute();
        $sql = <?=$this->getModelClass()?>Table::getInstance()->createQuery('a');
        $this-><?=$this->getPluralName()?> = new sfDoctrinePager('<?=$this->getModelClass()?>', 5);
        $this-><?=$this->getPluralName()?>->setQuery($sql);
        $this-><?=$this->getPluralName()?>->setPage($request->getParameter('pagina', 1));
        $this-><?=$this->getPluralName()?>->init();
<?php endif; ?>
    }
