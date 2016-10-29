<?php

/**
 * + ------------------------------------------------------------------- +
 * Por Oswaldo Rojas
 * Añadiendo nuevas formas a lo ya optimizado.
 * Domingo, 27 Agosto 2016 09:26:24
 * + ------------------------------------------------------------------- +
 */

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage i18n
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfI18nExtractorInterface.class.php 9128 2008-05-21 00:58:19Z Carl.Vondrick $
 */
interface sfI18nExtractorInterface {
    /**
     * Extract i18n strings for the given content.
     *
     * @param  string $content The content
     *
     * @return array An array of i18n strings
     */
    public function extract($content);
}