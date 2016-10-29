<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTLock">
                <?php echo image_tag('/sf/sf_default/images/icons/lock48.png', array('alt' => 'credentials required', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>Credenciales Requeridas</h1>
                    <h5>Esta p&aacute;gina est&aacute; en una &aacute;rea reestringida.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>No tienes las credenciales apropiadas para acceder a esta p&aacute;gina</dt>
                <dd>Incluso si estas logueago en el sitio, esta p&aacute;gina requiere de credenciales especiales que tu no tienes.</dd>
                <dt>C&oacute;mo accedo a esta p&aacute;gina?</dt>
                <dd>Debes preguntar al administrador del sitio web para concederte credenciales especiales sobre esta p&aacute;gina.</dd>
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                <dd>
                    <ul class="sfTIconList">
                        <li class="sfTLinkMessage"><a href="javascript:history.go(-1)">Regresar a la p&aacute;gina anterior</a></li>
                    </ul>
                </dd>
            </dl>