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

require __DIR__ . '/_widgets.php';

if (!dcCore::app()->blog->settings->get(basename(__DIR__))->get('public_enabled')) {
    return null;
}

dcCore::app()->tpl->addBlock('Acronyms', function ($attr, $content) {
    return
    "<?php\n" .
    'dcAcronyms::init(); ' .
    '$arrayAcronyms = []; ' .
    'foreach (dcAcronyms::read() as $acronym => $title) {' .
    "	\$arrayAcronyms[] = ['acronym' => \$acronym, 'title' => \$title];" .
    '}' .
    'dcCore::app()->ctx->__set("acronyms", staticRecord::newFromArray($arrayAcronyms)); ' .
    '?>' .
    '<?php while (dcCore::app()->ctx->__get("acronyms")->fetch()) : ?>' . $content . '<?php endwhile; ' .
    'dcCore::app()->ctx->__set("acronyms", null); unset($arrayAcronyms); ?>';
});

dcCore::app()->tpl->addBlock('AcronymsHeader', function ($attr, $content) {
    return "<?php if (dcCore::app()->ctx->__get('acronyms')->isStart()) : ?>" . $content . '<?php endif; ?>';
});

dcCore::app()->tpl->addBlock('AcronymsFooter', function ($attr, $content) {
    return "<?php if (dcCore::app()->ctx->__get('acronyms')->isEnd()) : ?>" . $content . '<?php endif; ?>';
});

dcCore::app()->tpl->addValue('Acronym', function ($attr) {
    return '<?php echo ' . sprintf(dcCore::app()->tpl->getFilters($attr), 'dcCore::app()->ctx->__get("acronyms")->__get("acronym")') . '; ?>';
});

dcCore::app()->tpl->addValue('AcronymTitle', function ($attr) {
    return '<?php echo ' . sprintf(dcCore::app()->tpl->getFilters($attr), 'dcCore::app()->ctx->__get("acronyms")->__get("title")') . '; ?>';
});

dcCore::app()->addBehavior('publicBreadcrumb', function ($context, $separator) {
    if ($context == 'acronyms') {
        return __('List of Acronyms');
    }
});
