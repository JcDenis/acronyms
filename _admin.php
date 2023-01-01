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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

require __DIR__ . '/_widgets.php';

dcCore::app()->auth->setPermissionType(initAcronyms::PERMISSION, __('manage acronyms'));

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('Acronyms'),
    dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)),
    urldecode(dcPage::getPF(basename(__DIR__) . '/icon.png')),
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__))) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([initAcronyms::PERMISSION]), dcCore::app()->blog->id)
);

dcCore::app()->addBehaviors([
    'adminDashboardFavoritesV2' => function (dcFavorites $favs) {
        $favs->register(basename(__DIR__), [
            'title'       => __('Acronyms'),
            'url'         => dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)),
            'small-icon'  => urldecode(dcPage::getPF(basename(__DIR__) . '/icon.png')),
            'large-icon'  => urldecode(dcPage::getPF(basename(__DIR__) . '/icon-big.png')),
            'permissions' => dcCore::app()->auth->makePermissions([initAcronyms::PERMISSION]),
        ]);
    },
    'coreInitWikiPost'          => function ($wiki2xhtml) {
        dcAcronyms::init();

        $wiki2xhtml->setOpt('acronyms_file', dcAcronyms::file());
        $wiki2xhtml->acro_table = dcAcronyms::read();
    },
    'ckeditorExtraPlugins'      => function (ArrayObject $extraPlugins): void {
        if (!dcCore::app()->blog->settings->get(basename(__DIR__))->get('button_enabled')) {
            return;
        }

        $extraPlugins[] = [
            'name'   => 'acronym',
            'button' => 'Acronym',
            'url'    => DC_ADMIN_URL . urldecode(dcPage::getPF(basename(__DIR__) . '/cke-addon/plugin.js')),
        ];
    },
    'adminPostEditor'           => function (string $editor = ''): string {
        if (!dcCore::app()->blog->settings->get(basename(__DIR__))->get('button_enabled')) {
            return '';
        }

        if ($editor == 'dcLegacyEditor') {
            return dcPage::jsJson('editor_acronyms', [
                'title'     => __('Acronym'),
                'msg_title' => __('Title?'),
                'msg_lang'  => __('Lang?'),
                'icon_url'  => DC_ADMIN_URL . urldecode(dcPage::getPF(basename(__DIR__) . '/icon.png')),
            ]) . dcPage::jsModuleLoad(basename(__DIR__) . '/js/post.js');
        } elseif ($editor == 'dcCKEditor') {
            return dcPage::jsJson('editor_acronyms', [
                'title'     => __('Acronym'),
                'msg_title' => __('Title?'),
                'msg_lang'  => __('Lang?'),
            ]);
        }

        return '';
    },
]);
