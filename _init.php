<?php
/**
 * @brief acronyms, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Vincent Garnier, Pierre Van Glabeke, Bernard Le Roux
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return null;
}

class initAcronyms
{
    public const PERMISSION = 'acronyms';
    public const DIR        = 'acronyms';
    public const FILE       = 'wiki-acronyms.txt';
    public const TPL        = 'acronyms.html';
}
