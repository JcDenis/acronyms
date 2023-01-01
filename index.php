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

dcPage::check(dcCore::app()->auth->makePermissions([
    initAcronyms::PERMISSION,
]));

dcAcronyms::init();
$ns            = dcCore::app()->blog->settings->get(basename(__DIR__));
$acronyms_list = dcAcronyms::read();
$action        = $_POST['action'] ?? '';
$a_acro        = '';
$a_title       = '';

// modfication des parametres
if ('config' == $action) {
    $ns->put('button_enabled', !empty($_POST['button_enabled']), 'boolean');
    $ns->put('public_enabled', !empty($_POST['public_enabled']), 'boolean');

    dcCore::app()->blog->triggerBlog();

    dcAdminNotices::addSuccessNotice(
        __('Acronyms parameters successfully updated.')
    );

    dcCore::app()->adminurl->redirect(
        'admin.plugin.' . basename(__DIR__)
    );
}

// modfication de la liste des acronymes
if ('edit' == $action) {
    $p_acronyms = !empty($_POST['p_acronyms']) && is_array($_POST['p_acronyms']) ? array_map('trim', $_POST['p_acronyms']) : [];

    $acronyms_list = [];
    foreach ($p_acronyms as $nk => $nv) {
        if ($nv != '') {
            $acronyms_list[$nk] = $nv;
        } else {
            unset($acronyms_list[$nk]);
        }
    }
    ksort($acronyms_list);

    dcAcronyms::write($acronyms_list);

    dcCore::app()->blog->triggerBlog();

    dcAdminNotices::addSuccessNotice(
        __('Acronyms list successfully updated.')
    );

    dcCore::app()->adminurl->redirect(
        'admin.plugin.' . basename(__DIR__)
    );
}

// ajout d'un acronyme
if ('add' == $action) {
    try {
        $a_acro  = !empty($_POST['a_acro']) ? trim($_POST['a_acro']) : '';
        $a_title = !empty($_POST['a_title']) ? trim($_POST['a_title']) : '';

        if ($a_acro == '') {
            throw new Exception(__('You must give an acronym'));
        }

        if ($a_title == '') {
            throw new Exception(__('You must give a title'));
        }

        if (isset($acronyms_list[$a_acro])) {
            throw new Exception(__('This acronym already exists'));
        }

        $acronyms_list[$a_acro] = $a_title;
        ksort($acronyms_list);

        dcAcronyms::write($acronyms_list);

        dcCore::app()->blog->triggerBlog();

        dcAdminNotices::addSuccessNotice(
            __('Acronym successfully added.')
        );

        dcCore::app()->adminurl->redirect(
            'admin.plugin.' . basename(__DIR__)
        );
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

echo '
<html><head><title>' . __('Acronyms Manager') . '</title>' .
dcPage::cssModuleLoad(basename(__DIR__) . '/css/index.css') . '
</head><body>' .
dcPage::breadcrumb([
    dcCore::app()->blog->name => '',
    __('Acronyms Manager')    => '',
]) .
dcPage::notices() . '

<div class="fieldset"><h4>' . __('Activation') . '</h4>
<form id="param_acronyms" action="' . dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)) . '" method="post">

<p><label for="button_enabled">' .
form::checkbox('button_enabled', '1', (bool) $ns->get('button_enabled')) .
__('Enable acronyms button on toolbar') . '</label></p>

<p><label for="public_enabled">' .
form::checkbox('public_enabled', '1', (bool) $ns->get('public_enabled')) .
__('Enable acronyms public page') . '</label></p>';

if ($ns->get('public_enabled')) {
    echo
    '<p class="clear"><a class="onblog_link outgoing" href="' .
    dcCore::app()->blog->url . dcCore::app()->url->getBase(basename(__DIR__)) .
    '" title="' . __('View the acronyms page') . '">' . __('View the acronyms page') .
    ' <img src="images/outgoing-blue.png" alt="" /></a></p>';
}

echo '
<p class="clear">' .
form::hidden(['action'], 'config') .
dcCore::app()->formNonce() . '
<input type="submit" name="save" value="' . __('Save') . '" />
</p>
</form>
</div>';

echo '
<div class="fieldset"><h4>' . __('Edit acronyms') . '</h4>
<form id="edit_acronyms" action="' . dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)) . '" method="post">
<div id="listacro">';

$i = 1;
foreach ($acronyms_list as $k => $v) {
    echo
    '<p class="field">' .
    '<label for="acronym_' . $i . '"><acronym title="' . $v . '">' . html::escapeHTML($k) . '</acronym></label>' .
    form::field(['p_acronyms[' . $k . ']', 'acronym_' . $i], 60, 200, html::escapeHTML($v)) .
    '</p>';

    ++$i;
}

echo '
</div><!-- #listacro -->
<p class="clear">' .
form::hidden(['action'], 'edit') .
dcCore::app()->formNonce() . '
<input type="submit" class="submit" value="' . __('Edit') . '" />
</p>
</form>
</div>

<div class="fieldset"><h4>' . __('Add an acronym') . '</h4>
<form id="add_acronyms" action="' . dcCore::app()->adminurl->get('admin.plugin.' . basename(__DIR__)) . '" method="post">

<p class="acroleft"><label for="a_acro" class="required"><abbr title="' . __('Required field') . '">*</abbr>' . __('Acronym') . '</label>' .
form::field('a_acro', 10, 200, $a_acro, '', '') . '</p>

<p class="acroright"><label for="a_title" class="required"><abbr title="' . __('Required field') . '">*</abbr>' . __('Entitled') . '</label>' .
form::field('a_title', 60, 200, $a_title, '', '') . '</p>

<p class="clear">' .
form::hidden(['action'], 'add') .
dcCore::app()->formNonce() . '
<input type="submit" name="save" value="' . __('Add') . '" />
</p>
</form>
</div>';

dcPage::helpBlock('acronyms');

echo '
</body>
</html>';
