<?php decorate_with(dirname(__FILE__).'/defaultLayout.php') ?>
            <div class="sfTMessageContainer sfTMessage"> 
                <?php echo image_tag('/sf/sf_default/images/icons/ok48.png', array('alt' => 'ok', 'class' => 'sfTMessageIcon', 'size' => '48x48')).PHP_EOL ?>
                <div class="sfTMessageWrap">
                    <h1>Proyecto Symfony 1.4.20 Creado</h1>
                    <h5>Felicitaciones!! Has creado un nuevo proyecto Symfony.</h5>
                </div>
            </div>
            <dl class="sfTMessageInfo">
                <dt>Instalaci&oacute;n del proyecto, correcta.</dt>
                <dd>Este proyecto actualmente usa las librerias de Bootstrap2.3.2, asi que esta p&aacute;gina de ejemplo sera la &uacute;nica que use otras librerias en este caso las de <code>symfony 1.4.20</code></dd>
                <dt>Esta es un p&aacute;gina temporal</dt>
                <dd>Esta p&aacute;gina es parte del m&oacute;dulo por defeto <code>default</code> que se encuentra en <code>config/routing.yml</code>. Se puede quitar tan pronto definas una ruta inicial <code>homepage</code> en el archivo <code>routing.yml</code>, dentro de la carpeta de configuracion.</dd>
                <dt>Ahora, cu&aacute;l es el siguiente paso?</dt>
                <dd>
                    <ul class="sfTIconList">
                        <li class="sfTDatabaseMessage">Crear un modelo de datos</li>
                        <li class="sfTColorMessage">Personaliza un layout(capa) para los modulos generados</li>
                        <li class="sfTLinkMessage"><?php echo link_to('Aprende mas desde la documentaci&oacute;n', 'http://www.symfony-project.org/doc') ?></li>
                    </ul>
                </dd>
            </dl>
