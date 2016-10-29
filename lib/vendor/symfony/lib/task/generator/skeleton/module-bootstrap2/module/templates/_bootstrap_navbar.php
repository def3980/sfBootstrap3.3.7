<?php
    $routing = array(
        'Home'        => '@bootstrap2_index',
        'Get started' => '@bootstrap2_getting_started',
        'Scaffolding' => '@bootstrap2_scaffolding',
        'Base CSS'    => '@bootstrap2_base_css',
        'Components'  => '@bootstrap2_components',
        'JavaScript'  => '@bootstrap2_javascript',
        'Customize'   => '@bootstrap2_customize',
    );
?>
<!-- Navbar ================================================== -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo link_to('Bootstrap', '@bootstrap2_index', array('class' => 'brand')).PHP_EOL ?>
                    <div class="nav-collapse collapse">
                        <ul class="nav"><?php foreach ($routing as $k => $v): echo PHP_EOL; ?>
                            <li<?php echo $ruta == $v
                                          ? ' class="active"' 
                                          : '' ?>><?=link_to($k, $v)?></li><?php endforeach; echo PHP_EOL; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
