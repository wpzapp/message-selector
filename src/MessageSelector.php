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
use WPZAPP\Exceptions\InvalidArgumentException;

/**
 * Class for selecting the correct portion of a pluralized message based on a given number.
 *
 * @since 1.0.0
 */
class MessageSelector
{

    /** @var Locale Locale for the message selector. */
    private $locale;

    /** @var PluralizationRule Pluralization rule for the message selector. */
    private $pluralizationRule;

    /**
     * Constructor.
     *
     * Set the locale and pluralization rule.
     *
     * @since 1.0.0
     *
     * @param Locale                 $locale            The locale for the message selector.
     * @param PluralizationRule|null $pluralizationRule Optional. Pluralization rule for the message selector.
     *                                                  Default is pluralization rule based on the locale.
     */
    public function __construct(Locale $locale, PluralizationRule $pluralizationRule = null)
    {
        if ($pluralizationRule === null) {
            $pluralizationRule = PluralizationRuleFactory::getInstance($locale);
        }

        $this->locale            = $locale;
        $this->pluralizationRule = $pluralizationRule;
    }

    /**
     * Get the correct portion of a given message with different plural translations separated by a pipe (|).
     *
     * The message supports two different types of pluralization rule:
     *
     * interval: {0} There are no apples.|{1} There is one apple.|]1,Inf] There are %d apples.
     * indexed: There is one apple.|There are %d apples.
     *
     * Getting the correct portion of the indexed variant is based on pluralization rule of the set locale.
     *
     * @since 1.0.0
     *
     * @param string $message The pluralized message to extract the correct portion from.
     * @param int    $number  The number of items represented for the message.
     *
     * @return string The extracted message based on the given number.
     *
     * @throws InvalidArgumentException
     */
    public function choose(string $message, int $number): string
    {
        preg_match_all('/(?:\|\||[^\|])++/', $message, $parts);

        $explicitRules = array();
        $standardRules = array();

        $regex = '/^(?P<interval>' . Interval::getIntervalRegexp() . ')\s*(?P<message>.*?)$/xs';

        foreach ($parts[0] as $part) {
            $part = trim(str_replace('||', '|', $part));

            if (preg_match($regex, $part, $matches)) {
                $explicitRules[$matches['interval']] = $matches['message'];
            } elseif (preg_match('/^\w+\:\s*(.*?)$/', $part, $matches)) {
                $standardRules[] = $matches[1];
            } else {
                $standardRules[] = $part;
            }
        }

        // try to match an explicit rule, then fallback to the standard ones
        foreach ($explicitRules as $interval => $m) {
            if (Interval::test($number, $interval)) {
                return $m;
            }
        }

        $position = $this->pluralizationRule->getPosition($number);

        if (!isset($standardRules[$position])) {
            // when there's exactly one rule given, and that rule is a standard
            // rule, use this rule
            if (1 === count($parts[0]) && isset($standardRules[0])) {
                return $standardRules[0];
            }

            throw new InvalidArgumentException(sprintf('Unable to choose a translation for "%s" with locale "%s" for value "%d".', $message, $this->locale->getValue(), $number) . ' Double check that this translation has the correct plural options (e.g. "There is one apple|There are %d apples").');
        }

        return $standardRules[$position];
    }

    /**
     * Get the locale for the message selector.
     *
     * @since 1.0.0
     *
     * @return Locale The locale for the message selector.
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * Get the pluralization rule for the message selector.
     *
     * @since 1.0.0
     *
     * @return PluralizationRule The pluralization rule for the message selector.
     */
    public function getPluralizationRule(): PluralizationRule
    {
        return $this->pluralizationRule;
    }
}
