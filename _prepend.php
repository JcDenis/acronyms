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

Clearbricks::lib()->autoload([
    'dcAcronyms' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'inc', 'class.dc.acronyms.php']),
]);

dcCore::app()->url->register(
    basename(__DIR__),
    'acronyms',
    '^acronyms$',
    ['urlAcronyms', 'acronyms']
);

class urlAcronyms extends dcUrlHandlers
{
    public static function acronyms($args)
    {
        if (!dcCore::app()->blog->settings->get(basename(__DIR__))->get('public_enabled')) {
            self::p404();
            exit;
        }

        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->get('system')->get('theme'), 'tplset');
        if (!empty($tplset) && is_dir(implode(DIRECTORY_SEPARATOR, [__DIR__, 'default-templates', $tplset]))) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), implode(DIRECTORY_SEPARATOR, [__DIR__, 'default-templates', $tplset]));
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), implode(DIRECTORY_SEPARATOR, [__DIR__, 'default-templates', DC_DEFAULT_TPLSET]));
        }

        self::serveDocument(initAcronyms::TPL);
        exit;
    }
}
