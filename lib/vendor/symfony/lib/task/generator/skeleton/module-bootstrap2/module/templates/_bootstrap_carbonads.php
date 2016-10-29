<?php
    $avatars = array(
        'venom_avatar_260x120',
        'captain_america_avatar_260x120',
        'ironman_avatar_260x120',
        'hulk_avatar_260x120'
    );
    /* randomico, sin repeticion */
    $rnd = range(0, 3);
    shuffle($rnd);
    $rnd = array_slice($rnd, 0, 4);
    /* ------------------------- */
?>
                <div id="carbonads-container">
                    <div class="carbonad"<?php echo $home ? ' style="text-align: center !important"' : '' ?>>
                        <div id="azcarbon">
                            <?php
                                if (!$home):
                                    echo image_tag($avatars[reset($rnd)], array('class' => 'img-rounded')).PHP_EOL;
                                else:
                                    foreach ( $avatars as $k => $v ):
                                        echo $k === 0
                                             ? image_tag($avatars[$rnd[$k]], array('class' => 'img-rounded')).PHP_EOL
                                             : "\t\t\t    ".image_tag($avatars[$rnd[$k]], array('class' => 'img-rounded')).PHP_EOL;
                                    endforeach;
                                endif;
                            ?>
                        </div>
                    </div>
                </div>
