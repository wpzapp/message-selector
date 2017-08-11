<?php
/**
 * Utility class to select the correct portion of a pluralized message.
 *
 * @package WPZAPP\MessageSelector
 * @license GPL-3.0
 * @link    https://wpzapp.org
 */

namespace WPZAPP\MessageSelector;

use WPZAPP\Exceptions\InvalidArgumentException;

/**
 * Class for selecting the correct portion of a pluralized message based on a given number.
 *
 * @since 1.0.0
 */
class MessageSelector
{
    public function choose(string $message, int $number, string $locale): string
    {
        preg_match_all('/(?:\|\||[^\|])++/', $message, $parts);
        $explicitRules = array();
        $standardRules = array();
        foreach ($parts[0] as $part) {
            $part = trim(str_replace('||', '|', $part));

            if (preg_match('/^(?P<interval>'.Interval::getIntervalRegexp().')\s*(?P<message>.*?)$/xs', $part, $matches)) {
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

        $position = PluralizationRules::get($number, $locale);

        if (!isset($standardRules[$position])) {
            // when there's exactly one rule given, and that rule is a standard
            // rule, use this rule
            if (1 === count($parts[0]) && isset($standardRules[0])) {
                return $standardRules[0];
            }

            throw new InvalidArgumentException(sprintf('Unable to choose a translation for "%s" with locale "%s" for value "%d". Double check that this translation has the correct plural options (e.g. "There is one apple|There are %d apples").', $message, $locale, $number));
        }

        return $standardRules[$position];
    }
}
