<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTLock"> 
                <?php echo image_tag('/sf/sf_default/images/icons/lock48.png', array('alt' => 'login required', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>Se requiere de logueo</h1>
                    <h5>Esta p&aacute;gina no es p&uacute;blica.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>C&oacute;mo accedo a esta p&aacute;gina?</dt>
                <dd>Debes entrar a la p&aacute;gina de login, ingresar usuario y contraseña y regresar despu&eacute;s.</dd>
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                <dd>
                    <ul class="sfTIconList">
                        <li class="sfTLinkMessage"><?php echo link_to('Proceso de logueo', sfConfig::get('sf_login_module').'/'.sfConfig::get('sf_login_action')) ?></li>
                        <li class="sfTLinkMessage"><a href="javascript:history.go(-1)">Back to previous page</a></li>
                    </ul>
                </dd>
            </dl>