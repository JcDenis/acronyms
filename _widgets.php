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

dcCore::app()->addBehavior('initWidgets', ['widgetsAcronyms','initWidgets']);

class widgetsAcronyms
{
    public static function initWidgets($w)
    {
        $w->create(
            'acronyms',
            __('List of Acronyms'),
            ['widgetsAcronyms', 'acronymsWidgets'],
            null,
            __('Link to the page of acronyms')
        )
        ->addTitle(__('List of Acronyms'))
        ->addHomeOnly()
        ->addContentOnly()
        ->addClass()
        ->addOffline();
    }

    public static function acronymsWidgets($w)
    {
        if ($w->offline
          || !$w->checkHomeOnly(dcCore::app()->url->type)
          || !dcCore::app()->blog->settings->get(basename(__DIR__))->get('public_enabled')
        ) {
            return null;
        }

        return $w->renderDiv(
            $w->content_only,
            'acronyms ' . $w->class,
            '',
            ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') .
            sprintf(
                '<ul><li><a href="%s">%s</a></li></ul>',
                dcCore::app()->blog->url . dcCore::app()->url->getBase(basename(__DIR__)),
                __('List of Acronyms')
            )
        );
    }
}
