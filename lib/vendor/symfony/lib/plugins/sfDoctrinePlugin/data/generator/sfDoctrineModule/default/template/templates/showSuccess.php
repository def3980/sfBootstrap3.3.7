[?php slot('porcion_css') ?]
        <style type="text/css">
            .table thead th,
            .table tbody td:first-child {
                text-align: center;
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
                                <li><span class="opc">Detalle de un registro</span></li>
                            </ul>
                        </div>
                    </div>
                    <hr style="margin: 0 0 20px">
                    <div class="row">
                        <div class="span6">
                            <table class="table table-bordered table-striped table-hover responsive-utilities">
                                <thead>
                                    <tr>
                                        <th>Campos<small>BDD</small></th>
                                        <th>Valores<small>Contenido de cada campo</small></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php $con = 1; foreach ($this->getColumns() as $column): ?>
                                    <tr>
                                        <td><code><?php echo sfInflector::humanize(sfInflector::underscore($column->getPhpName())) ?></code></td>
                                        <td>[?php echo $<?php echo $this->getSingularName() ?>->get<?php echo sfInflector::camelize($column->getPhpName()) ?>() ?]</td>
                                    </tr>
<?php   if ($con == ceil(count($this->getColumns()) / 2)): ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="span6">
                            <table class="table table-bordered table-striped table-hover responsive-utilities">
                                <thead>
                                    <tr>
                                        <th>Campos<small>BDD</small></th>
                                        <th>Valores<small>Contenido de cada campo</small></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php   endif; $con += 1; ?>
<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="span6">&nbsp;</div>
                        <div class="span6" style="text-align: right">
                            <a class="btn btn-small btn-success" href="[?php echo url_for('<?php echo $this->getModuleName() ?>/edit?<?php echo $this->getPrimaryKeyUrlParams() ?>) ?]">Editar registro</a>
                            |&nbsp;<a class="btn btn-small" href="[?php echo url_for('<?php echo $this->getModuleName() ?>/index') ?]">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /container -->
<?php echo str_repeat('        <br />'.PHP_EOL, 6) ?>
[?php include_partial('footer') ?]