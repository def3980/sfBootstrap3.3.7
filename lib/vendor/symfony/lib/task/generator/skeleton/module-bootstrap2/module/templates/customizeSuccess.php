<?php slot('titulo', 'Customize &middot; Bootstrap') ?>
<?php include_partial('bootstrap_navbar', array('ruta' => '@bootstrap2_customize')) ?>
        <!-- Masthead ================================================== -->
        <header class="jumbotron subhead" id="overview">
            <div class="container">
                <h1>Customize and download</h1>
                <p class="lead"><a href="https://github.com/twbs/bootstrap/archive/v2.3.2.zip">Download Bootstrap</a> or customize variables, components, JavaScript plugins, and more.</p>
<?php include_partial('bootstrap_carbonads', array('home' => false)) ?>
            </div>
        </header>
        <div class="container">
<?php include_partial('bootstrap_heads-up') ?>
            <!-- Docs nav ================================================== -->
            <div class="row">
                <div class="span12">
                    <h2>Sorry, the Bootstrap v2.3.2 Customizer is no longer available.</h2>
                    <p>As of May 2014, we've discontinued operation of Bootstrap v2.3.2's Customizer. It's been nearly a year since Bootstrap v2.3.2 was released. <?=link_to('Bootstrap v3', 'http://getbootstrap.com')?> was released soon after, and is now mature. We continue to encourage new projects to use Bootstrap v3.</p>
                    <p>As always, you can of course still build Bootstrap v2.3.2 from source yourself. See <?=link_to('the Getting started docs', '@bootstrap2_getting_started')?> and <?=link_to('Bootstrap v2.3.2\'s README', 'https://github.com/twbs/bootstrap/blob/v2.3.2/README.md#compiling-css-and-javascript')?> for instructions.</p>
                </div>
            </div>
        </div>
<?php include_partial('bootstrap_footer') ?>