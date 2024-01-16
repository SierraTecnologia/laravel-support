<?php
/**
 * https://github.com/rap2hpoutre/similar-text-finder/blob/master/test/FinderTest.php
 */

declare(strict_types=1);

namespace Muleta\Utils\Compare;

use Muleta\Utils\Modificators\StringModificator;

class StringCompare
{


    public static function isSimilar($strOne, $strTwo): bool
    {
        if (is_array($strOne)) {
            foreach ($strOne as $str) {
                if (self::isSimilar($str, $strTwo)) {
                    return true;
                }
            }
            return false;
        }
        if (is_array($strTwo)) {
            foreach ($strTwo as $str) {
                if (self::isSimilar($strOne, $str)) {
                    return true;
                }
            }
            return false;
        }
        
        if (StringModificator::singularizeAndLower($strOne) == StringModificator::singularizeAndLower($strTwo)) {
            return true;
        }
        
        if (strpos(StringModificator::singularizeAndLower($strOne), StringModificator::singularizeAndLower($strTwo)) !== false) {
            return true;
        }
        
        if (strpos(StringModificator::singularizeAndLower($strTwo), StringModificator::singularizeAndLower($strOne)) !== false) {
            return true;
        }

        return false;
    }
}
