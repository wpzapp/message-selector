<?php
/**
 * Utility class to select the correct portion of a pluralized message.
 *
 * @package WPZAPP\MessageSelector
 * @license GPL-3.0
 * @link    https://wpzapp.org
 */

namespace WPZAPP\MessageSelector;

use WPZAPP\Locale\Locale;

/**
 * Factory class for instantiating pluralization rules.
 *
 * @since 1.0.0
 */
class PluralizationRuleFactory
{

    /** @var array<PluralizationRule> List of instantiated pluralization rules. */
    private static $rules = array();

    /**
     * Get the pluralization rule instance for a locale.
     *
     * Pluralization rule instances are shared and not reinstantiated on request.
     *
     * @since 1.0.0
     *
     * @param Locale $locale Locale to get the pluralization rule.
     *
     * @return PluralizationRule The pluralization rule for the locale.
     */
    public static function getInstance(Locale $locale): PluralizationRule
    {
        $localeIdentifier   = $locale->getValue();
        $languageIdentifier = $locale->getLanguage()->getValue();

        if (isset(self::$rules[$localeIdentifier])) {
            return self::$rules[$localeIdentifier];
        }

        switch ($languageIdentifier) {
            case 'az':
            case 'bo':
            case 'dz':
            case 'id':
            case 'ja':
            case 'jv':
            case 'ka':
            case 'km':
            case 'kn':
            case 'ko':
            case 'ms':
            case 'th':
            case 'tr':
            case 'vi':
            case 'zh':
                $ruleCallback = function () {
                    return 0;
                };
                break;
            case 'af':
            case 'bn':
            case 'bg':
            case 'ca':
            case 'da':
            case 'de':
            case 'el':
            case 'en':
            case 'eo':
            case 'es':
            case 'et':
            case 'eu':
            case 'fa':
            case 'fi':
            case 'fo':
            case 'fur':
            case 'fy':
            case 'gl':
            case 'gu':
            case 'ha':
            case 'he':
            case 'hu':
            case 'is':
            case 'it':
            case 'ku':
            case 'lb':
            case 'ml':
            case 'mn':
            case 'mr':
            case 'nah':
            case 'nb':
            case 'ne':
            case 'nl':
            case 'nn':
            case 'no':
            case 'om':
            case 'or':
            case 'pa':
            case 'pap':
            case 'ps':
            case 'pt':
            case 'so':
            case 'sq':
            case 'sv':
            case 'sw':
            case 'ta':
            case 'te':
            case 'tk':
            case 'ur':
            case 'zu':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : 1;
                };
                break;
            case 'am':
            case 'bh':
            case 'fil':
            case 'fr':
            case 'gun':
            case 'hi':
            case 'hy':
            case 'ln':
            case 'mg':
            case 'nso':
            case 'xbr':
            case 'ti':
            case 'wa':
                $ruleCallback = function ($number) {
                    return (($number == 0) || ($number == 1)) ? 0 : 1;
                };
                break;
            case 'be':
            case 'bs':
            case 'hr':
            case 'ru':
            case 'sr':
            case 'uk':
                $ruleCallback = function ($number) {
                    return (($number % 10 == 1) && ($number % 100 != 11)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2);
                };
                break;
            case 'cs':
            case 'sk':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : ((($number >= 2) && ($number <= 4)) ? 1 : 2);
                };
                break;
            case 'ga':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : (($number == 2) ? 1 : 2);
                };
                break;
            case 'lt':
                $ruleCallback = function ($number) {
                    return (($number % 10 == 1) && ($number % 100 != 11)) ? 0 : ((($number % 10 >= 2) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2);
                };
                break;
            case 'sl':
                $ruleCallback = function ($number) {
                    return ($number % 100 == 1) ? 0 : (($number % 100 == 2) ? 1 : ((($number % 100 == 3) || ($number % 100 == 4)) ? 2 : 3));
                };
                break;
            case 'mk':
                $ruleCallback = function ($number) {
                    return ($number % 10 == 1) ? 0 : 1;
                };
                break;
            case 'mt':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : ((($number == 0) || (($number % 100 > 1) && ($number % 100 < 11))) ? 1 : ((($number % 100 > 10) && ($number % 100 < 20)) ? 2 : 3));
                };
                break;
            case 'lv':
                $ruleCallback = function ($number) {
                    return ($number == 0) ? 0 : ((($number % 10 == 1) && ($number % 100 != 11)) ? 1 : 2);
                };
                break;
            case 'pl':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 12) || ($number % 100 > 14))) ? 1 : 2);
                };
                break;
            case 'cy':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : (($number == 2) ? 1 : ((($number == 8) || ($number == 11)) ? 2 : 3));
                };
                break;
            case 'ro':
                $ruleCallback = function ($number) {
                    return ($number == 1) ? 0 : ((($number == 0) || (($number % 100 > 0) && ($number % 100 < 20))) ? 1 : 2);
                };
                break;
            case 'ar':
                $ruleCallback = function ($number) {
                    return ($number == 0) ? 0 : (($number == 1) ? 1 : (($number == 2) ? 2 : ((($number % 100 >= 3) && ($number % 100 <= 10)) ? 3 : ((($number % 100 >= 11) && ($number % 100 <= 99)) ? 4 : 5))));
                };
                break;
            default:
                $ruleCallback = function () {
                    return 0;
                };
        }

        self::$rules[$localeIdentifier] = new PluralizationRule($locale, $ruleCallback);

        return self::$rules[$localeIdentifier];
    }
}
