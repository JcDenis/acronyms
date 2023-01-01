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

class dcAcronyms
{
    public static function id()
    {
        return basename(dirname(__DIR__));
    }

    public static function dir(): string
    {
        return implode(DIRECTORY_SEPARATOR, [dcCore::app()->blog->public_path, initAcronyms::DIR]);
    }

    public static function file(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::dir(), dcCore::app()->blog->id . '.txt']);
    }

    /**
     * Check if acronyms file exists and create it if not
     */
    public static function init(): void
    {
        if (!file_exists(self::file())) {
            if (!is_dir(self::dir())) {
                files::makeDir(self::dir(), true);
            }

            files::putContent(self::file(), file_get_contents(implode(DIRECTORY_SEPARATOR, [self::id(), initAcronyms::FILE])));
        }
    }

    public static function write($acronyms_list): void
    {
        $contents = '';
        foreach ($acronyms_list as $nk => $nv) {
            $contents .= (string) $nk . "\t\t : " . (string) $nv . "\n";
        }

        files::putContent(self::file(), $contents);
    }

    public static function read(): array
    {
        $acronyms_list = [];
        if (false !== ($fc = @file(self::file()))) {
            foreach ($fc as $v) {
                $v = trim($v);
                if ($v != '') {
                    $p = strpos($v, ':');
                    $K = (string) trim(substr($v, 0, $p));
                    $V = (string) trim(substr($v, ($p + 1)));

                    if ($K) {
                        $acronyms_list[$K] = $V;
                    }
                }
            }
        } else {
            dcCore::app()->error->add(sprintf(__('Unable to read the %s file'), self::file()));

            return [];
        }

        return $acronyms_list;
    }
}
