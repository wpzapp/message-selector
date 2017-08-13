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
 * Class representing the pluralization rule for a locale.
 *
 * @since 1.0.0
 */
class PluralizationRule
{

    /** @var Locale Locale the pluralization rule applies to. */
    private $locale;

    /** @var callable Callback to return the plural position for a given number. */
    private $ruleCallback;

    /**
     * Constructor.
     *
     * Set the locale and rule callback.
     *
     * @since 1.0.0
     *
     * @param Locale   $locale       The locale the pluralization rule applies to.
     * @param callable $ruleCallback Callback for the pluralization rule.
     */
    public function __construct(Locale $locale, callable $ruleCallback)
    {
        $this->locale       = $locale;
        $this->ruleCallback = $ruleCallback;
    }

    /**
     * Get the plural position to use for a given number.
     *
     * @since 1.0.0
     *
     * @param int $number The number to get the plural position for.
     *
     * @return int The plural position.
     */
    public function getPosition(int $number): int
    {
        $return = call_user_func($this->ruleCallback, $number);

        if (!is_int($return) || $return < 0) {
            return 0;
        }

        return $return;
    }

    /**
     * Get the locale for the pluralization rule.
     *
     * @since 1.0.0
     *
     * @return Locale The locale for the pluralization rule.
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }
}
