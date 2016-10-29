<div class="container">
            <div class="row">
                <div class="span12">
                    <div class="row">
                        <div class="span6">
                            <h2>[?php echo link_to('<?=sfInflector::humanize($this->getModuleName())?>', '<?php echo $this->getModuleName() ?>/index') ?]</h2>
                        </div>
                        <div class="span6">
                            <ul class="nav nav-pills pull-right">
                                <li><span class="opc">Nuevo registro</a></li>
                            </ul>
                        </div>
                    </div>
                    <hr>
[?php include_partial('form', array('form' => $form)) ?]
                </div>
            </div>
        </div><!-- /container -->
<?php echo str_repeat('        <br />'.PHP_EOL, 6) ?>
[?php include_partial('footer') ?]