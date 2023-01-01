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

$this->registerModule(
    'acronyms',
    'Add, remove and modify acronyms for the wiki syntax',
    'Vincent Garnier, Pierre Van Glabeke, Bernard Le Roux',
    '1.7.7',
    [
        'requires'    => [['core', '2.24.1']],
        'permissions' => dcCore::app()->auth->makePermissions([
            initAcronyms::PERMISSION,
        ]),
        'type'        => 'plugin',
        'support'     => 'http://forum.dotclear.org/viewtopic.php?id=323174',
        'details'     => 'https://github.com/JcDenis/' . basename(__DIR__),
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . basename(__DIR__) . '/master/dcstore.xml',
    ]
);
