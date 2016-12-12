<!DOCTYPE html>
<html lang="en">
    <head>
<?php //include_http_metas() ?>
<?php include_metas() ?>

        <title><?php include_slot('titulo', 'Bootstrap') ?></title>

<?php include_stylesheets() ?>
<?php if (has_slot('porcion_css')): ?>
<?php include_slot('porcion_css') ?>
<?php endif; ?>

        <!--[if lt IE 9]>
        <?php echo javascript_include_tag('ie8-responsive-file-warning', array('inside' => true)) ?>
        <![endif]-->
        <?php echo javascript_include_tag('ie-emulation-modes-warning', array('inside' => true)) ?>
        <!--[if lt IE 9]>
        <?php echo javascript_include_tag('html5shiv.min.js', array('inside' => true)) ?>
        <?php echo javascript_include_tag('respond.min.js', array('inside' => true)) ?>
        <![endif]-->

<?php
    echo str_repeat("\t", 2).stylesheet_tag(image_path('apple-touch-icon.png'), array(
        'rel'   => 'apple-touch-icon',
        'icons' => true
    ));
    echo str_repeat("\t", 2).stylesheet_tag(image_path('favicon.ico'), array(
        'rel'   => 'icon',
        'icons' => true
    ));
?>
    </head>
    <body<?php if (has_slot('add_body')): " ".include_slot('add_body'); endif; ?>>
        <?php echo $sf_content."\n" ?>
        <!-- Librerias javascript/jQuery ============================== -->
<?php include_javascripts() ?>
<?php if (has_slot('porcion_js')): ?>
<?php include_slot('porcion_js') ?>
<?php endif; ?>
        <!-- Los ubicamos al final del layout asi se cargaran mas rapido -->
    </body>
</html>