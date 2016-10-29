[?php slot('porcion_css') ?]
        <style type="text/css">
            .table thead th,
            .table tbody td:first-child {
                text-align: center;
            }
            .alert p {
                margin: 0;
            }
            .alert h4 {
                margin-bottom: 5px;
            }
            .opc {
                display: block;
                text-decoration: none;
                background-color: #eeeeee;
                border-color: #eeeeee #eeeeee #dddddd;
                color: #0088cc;
                padding-top: 8px;
                padding-bottom: 8px;
                margin-top: 2px;
                margin-bottom: 2px;
                padding-right: 12px;
                padding-left: 12px;
                margin-right: 2px;
                line-height: 14px;
                -webkit-border-radius: 5px;
                   -moz-border-radius: 5px;
                        border-radius: 5px;
            }
        </style>
[?php end_slot() ?]
<div class="container">
            <div class="row">
                <div class="span12">
                    <div class="row">
                        <div class="span6">
                            <h2>[?php echo link_to('<?=sfInflector::humanize($this->getModuleName())?>', '<?php echo $this->getModuleName() ?>/index') ?]</h2>
                        </div>
                        <div class="span6">
                            <ul class="nav nav-pills pull-right">
                                <li><span class="opc">Lista de registros</span></li>
                            </ul>
                        </div>
                    </div>
                    <hr style="margin: 0 0 20px">
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4 class="alert-info">Atenci&oacute;n!!</h4>
                        <p>Si te percatas de la falta de algunos campos en esta tabla y en el modelo si existen, la razon es simple; la plantilla solo visualiza por defecto <code>5 campos</code> independientemente de cuantos tenga el modelo que esta en base a la tabla indicada en el comando <code>>_ ./symfony doctrine:generate-module</code>. No te preocupes solo necesitas llamarlos de acuerdo a su nombre, planteado en el modelo. Suerte y hasta un proxima oportunidad. \m/</p>
                    </div>
                    <table class="table table-bordered table-striped table-hover responsive-utilities">
                        <thead>
                            <tr>
<?php $con = 0; foreach ($this->getColumns() as $columBDD => $column): $con += 1; ?>
                                <th><?php echo sfInflector::humanize(sfInflector::underscore($column->getPhpName())) ?><small><?=$columBDD?></small></th>
<?php if ($con >= 5): $con = 0; break; endif; endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>[?php if ($<?php echo $this->getPluralName() ?>->count()): foreach ($<?php echo $this->getPluralName() ?>->getResults() as $<?php echo $this->getSingularName() ?>): echo PHP_EOL; ?]
                            <tr>
<?php foreach ($this->getColumns() as $column): $con += 1; ?>
<?php   if ($column->isPrimaryKey()): ?>
<?php       if (isset($this->params['route_prefix']) && $this->params['route_prefix']): ?>
                                <td><a href="[?php echo url_for('<?php echo $this->getUrlForAction(isset($this->params['with_show']) && $this->params['with_show'] ? 'show' : 'edit') ?>', $<?php echo $this->getSingularName() ?>) ?]">[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getPhpName()) ?>() ?]</a></td>
<?php       else: ?>
<?php /*<td><a href="[?php echo url_for('<?php echo $this->getModuleName() ?>/<?php echo isset($this->params['with_show']) && $this->params['with_show'] ? 'show' : 'edit' ?>?<?php echo $this->getPrimaryKeyUrlParams() ?>) ?]">[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getPhpName()) ?>() ?]</a></td> */?>
                                <td>[?php echo link_to($<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getPhpName()) ?>(), '<?php echo $this->getModuleName() ?>/<?php echo isset($this->params['with_show']) && $this->params['with_show'] ? 'show' : 'edit' ?>?<?php echo $this->getPrimaryKeyUrlParams() ?>) ?]</td>
<?php       endif; ?>
<?php   else: ?>
                                <td>[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getPhpName()) ?>() ?]</td>
<?php   endif; ?>
<?php if ($con >= 5): $con = 0; break; endif; endforeach; ?>
                            </tr>[?php endforeach; ?]
[?php else: echo PHP_EOL; ?]
                            <tr>
<?php foreach ($this->getColumns() as $column): $con += 1; ?>
                                <td>-</td>
<?php if ($con >= 5): $con = 0; break; endif; endforeach; ?>
                            </tr>[?php endif; echo PHP_EOL; ?]
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="span6">&nbsp;</div>
                        <div class="span6" style="text-align: right">
                            [?php echo link_to('Nuevo', '<?php echo $this->getModuleName() ?>/new', array('class' => 'btn btn-link')) ?]
                        </div>
                    </div>
[?php if ($<?php echo $this->getPluralName() ?>->count()): if ($<?php echo $this->getPluralName() ?>->haveToPaginate()): ?]
                    <hr>
                    <div class="pagination pagination-centered">
                        <ul>
                            <li[?php echo 1 == $<?php echo $this->getPluralName() ?>->getPage() ? ' class="active"' : '' ?]>
                                <a[?php echo 1 == $<?php echo $this->getPluralName() ?>->getPage() ? ' href="javascript:void(0)"' : ' href="'.url_for('<?php echo $this->getModuleName() ?>/index?pagina=1').'"' ?]>&laquo;</a>
                            </li>[?php foreach ($<?php echo $this->getPluralName() ?>->getLinks() as $pag): echo PHP_EOL; ?]
                            <li[?php echo $pag == $<?php echo $this->getPluralName() ?>->getPage() ? ' class="active"' : '' ?]>
                                <a[?php echo $pag == $<?php echo $this->getPluralName() ?>->getPage() ? ' href="javascript:void(0)"' : ' href="'.url_for('<?php echo $this->getModuleName() ?>/index?pagina='.$pag).'"' ?]>[?=$pag?]</a>
                            </li>[?php endforeach; echo PHP_EOL; ?]
                            <li[?php echo $<?php echo $this->getPluralName() ?>->getLastPage() == $<?php echo $this->getPluralName() ?>->getPage() ? ' class="active"' : '' ?]>
                                <a[?php echo $<?php echo $this->getPluralName() ?>->getLastPage() == $<?php echo $this->getPluralName() ?>->getPage() ? ' href="javascript:void(0)"' : ' href="'.url_for('<?php echo $this->getModuleName() ?>/index?pagina='.$<?php echo $this->getPluralName() ?>->getNextPage()).'"' ?]>&raquo;</a>
                            </li>
                        </ul>
                    </div>
[?php else: ?]
                    <hr>
                    <div class="pagination pagination-centered">
                        <ul>
                            <li class="active"><a href="javascript:void(0)">&laquo;</a></li>
                            <li class="active"><a href="javascript:void(0)">1</a></li>
                            <li class="active"><a href="javascript:void(0)">&raquo;</a></li>
                        </ul>
                    </div>
[?php endif; else: echo PHP_EOL; ?]
                    <hr>
                    <div class="pagination pagination-centered">
                        <ul>
                            <li class="active"><a href="javascript:void(0)">&laquo;</a></li>
                            <li class="active"><a href="javascript:void(0)">1</a></li>
                            <li class="active"><a href="javascript:void(0)">&raquo;</a></li>
                        </ul>
                    </div>
[?php endif; ?]
                </div>
            </div>
        </div><!-- /container -->
<?php echo str_repeat('        <br />'.PHP_EOL, 6) ?>
[?php include_partial('footer') ?]