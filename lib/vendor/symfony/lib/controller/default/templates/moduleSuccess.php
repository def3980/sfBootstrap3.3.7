<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTMessage"> 
                <?php echo image_tag('/sf/sf_default/images/icons/ok48.png', array('alt' => 'module created', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>M&oacute;dulo "<?php echo $sf_params->get('module') ?>" creado</h1>
                    <h5>Felicitaciones!! Has creado un nuevo m&oacute;dulo Symfony.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>Esta es un p&aacute;gina temporal</dt>
                <dd>Esta p&aacute;gina es parte del m&oacute;dulo <code>default</code> de Symfony. Desaparecer&aacute; cuando cambies o modifiques la acci&oacute;n <code>index</code> del nuevo m&oacute;dulo creado.</dd>
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                <dd>
                    <ul class="sfTIconList">
                        <li class="sfTDirectoryMessage">Navega hasta el directorio <code>apps/<?php echo sfContext::getInstance()->getConfiguration()->getApplication() ?>/modules/<?php echo $sf_params->get('module') ?>/</code></li>
                        <li class="sfTEditMessage">En <code>actions/actions.class.php</code>, edita el m&eacute;todo <code>executeIndex()</code> y quita dentro de ella <code>$this->forward()</code> con su contenido.</li>
                        <li class="sfTColorMessage">Personaliza la plantilla <code>templates/indexSuccess.php</code> a tu manera, aunque desde ya se encuentra un ejemplo de aplicaci&oacute;n Bootstrap2</li>
                        <li class="sfTLinkMessage"><?php echo link_to('Aprende mas desde la documentaci&oacute;n', 'http://www.symfony-project.org/doc') ?></li>
                    </ul>
                </dd>
            </dl>