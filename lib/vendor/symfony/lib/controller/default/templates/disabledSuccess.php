<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTAlert"> 
                <?php echo image_tag('/sf/sf_default/images/icons/disabled48.png', array('alt' => 'module disabled', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>Este m&oacute;dulo no est&aacute; disponible</h1>
                    <h5>Este m&oacute;dulo ha sido deshabilitado.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                    <dd>
                    <ul class="sfTIconList">
                        <li class="sfTLinkMessage"><a href="javascript:history.go(-1)">Regresar a la p&aacute;gina anterior</a></li>
                        <li class="sfTLinkMessage"><?php echo link_to('Ir a la p&aacute;gina inicial', '@homepage') ?></li>
                    </ul>
                </dd>
            </dl>