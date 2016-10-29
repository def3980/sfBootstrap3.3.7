<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTAlert"> 
                <?php echo image_tag('/sf/sf_default/images/icons/cancel48.png', array('alt' => 'page not found', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>Oohh, palmeras!! P&aacute;gina no encontrada</h1>
                    <h5>El servidor ha retornado una respuesta 404.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>Escribiste bien la URL?</dt>
                <dd>Talvez tecleaste la (URL) de manera incorrecta. Revisa que este completa, que no tenga letra capital, etc.</dd>
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                    <dd>
                    <ul class="sfTIconList">
                        <li class="sfTLinkMessage"><a href="javascript:history.go(-1)">Regresar a la p&aacute;gina anterior</a></li>
                        <li class="sfTLinkMessage"><?php echo link_to('Ir a la p&aacute;gina inicial', '@homepage') ?></li>
                    </ul>
                </dd>
            </dl>