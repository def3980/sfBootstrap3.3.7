<?php slot('add_body') ?> class="bs-docs-home"<?php end_slot() ?>
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
                    <a href="../" class="navbar-brand">Bootstrap</a>
                </div>
                <nav class="collapse navbar-collapse" id="bs-navbar">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="../getting-started/">Getting started</a>
                        </li>
                        <li>
                            <a href="../css/">CSS</a>
                        </li>
                        <li>
                            <a href="../components/">Components</a>
                        </li>
                        <li>
                            <a href="../javascript/">JavaScript</a>
                        </li>
                        <li>
                            <a href="../customize/">Customize</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="http://themes.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Themes")'>Themes</a>
                        </li>
                        <li>
                            <a href="http://expo.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Expo")'>Expo</a>
                        </li>
                        <li>
                            <a href="http://blog.getbootstrap.com" onclick='ga("send","event","Navbar","Community links","Blog")'>Blog</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <main class="bs-docs-masthead" id="content" tabindex="-1">
            <div class="container">
                <span class="bs-docs-booticon bs-docs-booticon-lg bs-docs-booticon-outline">B</span>
                <p class="lead">Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.</p>
                <p class="lead">
                    <a href="getting-started#download" class="btn btn-outline-inverse btn-lg" onclick='ga("send","event","Jumbotron actions","Download","Download 3.3.7")'>Download Bootstrap</a>
                </p>
                <p class="version">Currently v3.3.7</p>
                <div id="carbonads-container">
                    <div class="carbonad">
                        <div id="azcarbon"></div>
                    </div>
                </div>
            </div>
        </main>
        <div class="bs-docs-featurette">
            <div class="container">
                <h2 class="bs-docs-featurette-title">Designed for everyone, everywhere.</h2>
                <p class="lead">Bootstrap makes front-end web development faster and easier. It's made for folks of all skill levels, devices of all shapes, and projects of all sizes.</p>
                <hr class="half-rule">
                <div class="row">
                    <div class="col-sm-4">
                        <?php echo image_tag('sass-less', array('alt' => 'Sass and Less support', 'class' => 'img-responsive')).PHP_EOL ?>
                        <h3>Preprocessors</h3>
                        <p>Bootstrap ships with vanilla CSS, but its source code utilizes the two most popular CSS preprocessors,
                            <a href="../css/#less">Less</a> and
                            <a href="../css/#sass">Sass</a>. Quickly get started with precompiled CSS or build on the source.
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <?php echo image_tag('devices', array('alt' => 'Responsive across devices', 'class' => 'img-responsive')).PHP_EOL ?>
                        <h3>One framework, every device.</h3>
                        <p>Bootstrap easily and efficiently scales your websites and applications with a single code base, from phones to tablets to desktops with CSS media queries.</p>
                    </div>
                    <div class="col-sm-4">
                        <?php echo image_tag('components', array('alt' => 'Components', 'class' => 'img-responsive')).PHP_EOL ?>
                        <h3>Full of features</h3>
                        <p>With Bootstrap, you get extensive and beautiful documentation for common HTML elements, dozens of custom HTML and CSS components, and awesome jQuery plugins.</p>
                    </div>
                </div>
                <hr class="half-rule">
                <p class="lead">Bootstrap is open source. It's hosted, developed, and maintained on GitHub.</p>
                <a href="https://github.com/twbs/bootstrap" class="btn btn-outline btn-lg">View the GitHub project</a>
            </div>
        </div>
        <div class="bs-docs-featurette">
            <div class="container">
                <h2 class="bs-docs-featurette-title">Built with Bootstrap.</h2>
                <p class="lead">Millions of amazing sites across the web are being built with Bootstrap. Get started on your own with our growing
                    <a href="../getting-started/#examples">collection of examples</a> or by exploring some of our favorites.
                </p>
                <hr class="half-rule">
                <div class="row bs-docs-featured-sites">
                    <div class="col-xs-6 col-sm-3">
                        <?php echo link_to(
                                    image_tag('expo-lyft.jpg', array('alt' => 'Lyft', 'class' => 'img-responsive'))
                                    , 'http://expo.getbootstrap.com/2014/10/29/lyft/'
                                    , array('target' => '_blank', 'title' => 'Lyft')
                                   ).PHP_EOL ?>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <?php echo link_to(
                                    image_tag('expo-vogue.jpg', array('alt' => 'Vogue', 'class' => 'img-responsive'))
                                    , 'http://expo.getbootstrap.com/2014/09/30/vogue/'
                                    , array('target' => '_blank', 'title' => 'Vogue')
                                   ).PHP_EOL ?>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <?php echo link_to(
                                    image_tag('expo-riot.jpg', array('alt' => 'Riot Design', 'class' => 'img-responsive'))
                                    , 'http://expo.getbootstrap.com/2014/03/13/riot-design/'
                                    , array('target' => '_blank', 'title' => 'Riot Design')
                                   ).PHP_EOL ?>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <?php echo link_to(
                                    image_tag('expo-newsweek.jpg', array('alt' => 'Newsweek', 'class' => 'img-responsive'))
                                    , 'http://expo.getbootstrap.com/2014/03/13/riot-design/'
                                    , array('target' => '_blank', 'title' => 'Newsweek')
                                   ).PHP_EOL ?>
                    </div>
                </div>
                <hr class="half-rule">
                <p class="lead">We showcase dozens of inspiring projects built with Bootstrap on the Bootstrap Expo.</p>
                <a href="http://expo.getbootstrap.com" class="btn btn-outline btn-lg">Explore the Expo</a>
            </div>
        </div>
        <footer class="bs-docs-footer">
            <div class="container">
                <ul class="bs-docs-footer-links">
                    <li>
                        <a href="https://github.com/twbs/bootstrap">GitHub</a>
                    </li>
                    <li>
                        <a href="https://twitter.com/getbootstrap">Twitter</a>
                    </li>
                    <li>
                        <a href="../getting-started/#examples">Examples</a>
                    </li>
                    <li>
                        <a href="../about/">About</a>
                    </li>
                </ul>
                <p>Designed and built with all the love in the world by
                    <a href="https://twitter.com/mdo" target="_blank">@mdo</a> and
                    <a href="https://twitter.com/fat" target="_blank">@fat</a>. Maintained by the
                    <a href="https://github.com/orgs/twbs/people">core team</a> with the help of
                    <a href="https://github.com/twbs/bootstrap/graphs/contributors">our contributors</a>.
                </p>
                <p>Code licensed
                    <a href="https://github.com/twbs/bootstrap/blob/master/LICENSE" target="_blank" rel="license">MIT</a>, docs
                    <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank" rel="license">CC BY 3.0</a>.
                </p>
            </div>
        </footer>