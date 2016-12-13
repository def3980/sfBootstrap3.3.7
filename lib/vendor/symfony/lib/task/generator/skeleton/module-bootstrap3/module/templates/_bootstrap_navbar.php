<?php
    $routing = array(
        'Getting started' => '@bootstrap3_getting_started'
        , 'CSS'           => '@bootstrap3_css'
        , 'Components'    => '@bootstrap3_components'
        , 'JavaScript'    => '@bootstrap3_javascript'
        , 'Customize'     => '@bootstrap3_customize'
    );
?>
<a href="#content" class="sr-only sr-only-focusable" id="skippy">
            <div class="container">
                <span class="skiplink-text">Skip to main content</span>
            </div>
        </a>
        <header class="bs-docs-nav navbar navbar-static-top" id="top">
            <div class="container">
                <div class="navbar-header">
                    <button aria-controls="bs-navbar" aria-expanded="false" class="collapsed navbar-toggle" data-target="#bs-navbar" data-toggle="collapse" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo link_to('Bootstrap', '@bootstrap3_index', array('class' => 'navbar-brand')).PHP_EOL ?>
                </div>
                <nav class="collapse navbar-collapse" id="bs-navbar">
                    <ul class="nav navbar-nav"><?php foreach ($routing as $k => $v): echo PHP_EOL; ?>
                        <li<?php echo $ruta == $v
                                      ? ' class="active"' 
                                      : '' ?>><?=link_to($k, $v)?></li><?php endforeach; echo PHP_EOL; ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="http://themes.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Themes")'>Themes</a></li>
                        <li><a href="http://expo.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Expo")'>Expo</a></li>
                        <li><a href="http://blog.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Blog")'>Blog</a></li>
                    </ul>
                </nav>
            </div>
        </header>
