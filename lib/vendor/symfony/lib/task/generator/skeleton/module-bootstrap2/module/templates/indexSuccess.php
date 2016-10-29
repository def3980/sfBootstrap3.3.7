<?php include_partial('bootstrap_navbar', array('ruta' => '@bootstrap2_index')) ?>
        <div class="jumbotron masthead">
            <div class="container">
                <h1>Bootstrap</h1>
                <p>Sleek, intuitive, and powerful front-end framework for faster and easier web development.</p>
                <p>
                    <a href="assets/bootstrap.zip" class="btn btn-primary btn-large" onclick="">Download Bootstrap</a>
                </p>
                <ul class="masthead-links">
                    <li>
                        <?php echo link_to('GitHub project', 'https://github.com/twbs/bootstrap', array('onclick' => null)).PHP_EOL ?>
                    </li>
                    <li>
                        <a href="./getting-started.html#examples" onclick="">Examples</a>
                    </li>
                    <li>
                        <a href="./extend.html" onclick="">Extend</a>
                    </li>
                    <li>Version 2.3.2</li>
                </ul>
<?php include_partial('bootstrap_carbonads', array('home' => true)) ?>
            </div>
        </div>
        <div class="container">
<?php include_partial('bootstrap_heads-up') ?>
            <div class="marketing">
                <h1>Introducing Bootstrap.</h1>
                <p class="marketing-byline">Need reasons to love Bootstrap? Look no further.</p>
                <div class="row-fluid">
                    <div class="span4">
                        <?php echo image_tag('bs-docs-twitter-github', array('class' => 'marketing-img')).PHP_EOL ?>
                        <h2>By nerds, for nerds.</h2>
                        <p>Built at Twitter by <?=link_to('@mdo', 'http://twitter.com/mdo')?> and <?=link_to('@fat', 'http://twitter.com/fat')?>, Bootstrap utilizes <?=link_to('LESS CSS', 'http://lesscss.org')?>, is compiled via <?=link_to('Node', 'http://nodejs.org') ?>, and is managed through <?=link_to('GitHub', 'http://github.com')?> to help nerds do awesome stuff on the web.</p>
                    </div>
                    <div class="span4">
                        <?php echo image_tag('bs-docs-responsive-illustrations', array('class' => 'marketing-img')).PHP_EOL ?>
                        <h2>Made for everyone.</h2>
                        <p>Bootstrap was made to not only look and behave great in the latest desktop browsers (as well as IE7!), but in tablet and smartphone browsers via <a href="./scaffolding.html#responsive">responsive CSS</a> as well.</p>
                    </div>
                    <div class="span4">
                        <?php echo image_tag('bs-docs-bootstrap-features', array('class' => 'marketing-img')).PHP_EOL ?>
                        <h2>Packed with features.</h2>
                        <p>A 12-column responsive <a href="./scaffolding.html#gridSystem">grid</a>, dozens of components, <a href="./javascript.html">JavaScript plugins</a>, typography, form controls, and even a <a href="./customize.html">web-based Customizer</a> to make Bootstrap your own.</p>
                    </div>
                </div>
                <hr class="soften">
                <h1>Built with Bootstrap.</h1>
                <p class="marketing-byline">For even more sites built with Bootstrap, <a href="http://builtwithbootstrap.tumblr.com/" target="_blank">visit the unofficial Tumblr</a> or <a href="./getting-started.html#examples">browse the examples</a>.</p>
                <div class="row-fluid">
                    <ul class="thumbnails example-sites">
                        <li class="span3">
                            <?php echo link_to(
                                        image_tag('example-sites/soundready', array('alt' => 'SoundReady.fm')),
                                        'http://soundready.fm/',
                                        array('class' => 'thumbnail', 'target' => '_blank')
                                       ).PHP_EOL ?>
                        </li>
                        <li class="span3">
                            <a class="thumbnail" href="http://kippt.com/" target="_blank">
                                <?php echo image_tag('example-sites/kippt', array('alt' => 'Kippt')).PHP_EOL ?>
                            </a>
                        </li>
                        <li class="span3">
                            <?php echo link_to(
                                        image_tag('example-sites/gathercontent', array('alt' => 'Gather Content')),
                                        'http://www.gathercontent.com/',
                                        array('class' => 'thumbnail', 'target' => '_blank')
                                       ).PHP_EOL ?>
                        </li>
                        <li class="span3">
                            <?php echo link_to(
                                        image_tag('example-sites/jshint', array('alt' => 'JS Hint')),
                                        'http://www.jshint.com/',
                                        array('class' => 'thumbnail', 'target' => '_blank')
                                       ).PHP_EOL ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
<?php include_partial('bootstrap_footer') ?>