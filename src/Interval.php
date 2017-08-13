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
 * Class for testing if a given number belongs to a given math interval.
 *
 * @since 1.0.0
 */
class Interval
{

    /**
     * Test if a given number is in a specific math interval.
     *
     * @since 1.0.0
     *
     * @param int    $number   The number to test.
     * @param string $interval The interval to test against.
     *
     * @return bool True if the number belongs to the interval, false otherwise.
     *
     * @throws InvalidArgumentException
     */
    public static function test(int $number, string $interval): bool
    {
        $interval = trim($interval);

        if (!preg_match('/^' . self::getIntervalRegexp() . '$/x', $interval, $matches)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid interval.', $interval));
        }

        if ($matches[1]) {
            foreach (explode(',', $matches[2]) as $n) {
                if ($number == $n) {
                    return true;
                }
            }
        } else {
            $leftNumber = self::convertNumber($matches['left']);
            $rightNumber = self::convertNumber($matches['right']);

            $leftCondition = '[' === $matches['left_delimiter'] ? $number >= $leftNumber : $number > $leftNumber;
            $rightCondition = ']' === $matches['right_delimiter'] ? $number <= $rightNumber : $number < $rightNumber;

            return $leftCondition && $rightCondition;
        }

        return false;
    }

    /**
     * Get the regular expression that matches valid intervals.
     *
     * @since 1.0.0
     *
     * @return string Regular expression without delimiters.
     */
    public static function getIntervalRegexp(): string
    {
        return <<<EOF
        ({\s*
            (\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)
        \s*})

            |

        (?P<left_delimiter>[\[\]])
            \s*
            (?P<left>-Inf|\-?\d+(\.\d+)?)
            \s*,\s*
            (?P<right>\+?Inf|\-?\d+(\.\d+)?)
            \s*
        (?P<right_delimiter>[\[\]])
EOF;
    }

    /**
     * Convert a numeric match into an actual float value.
     *
     * @since 1.0.0
     *
     * @param string $number The numeric match to convert.
     *
     * @return float Converted number.
     */
    private static function convertNumber(string $number): float
    {
        if ('-Inf' === $number) {
            return log(0);
        } elseif ('+Inf' === $number || 'Inf' === $number) {
            return -log(0);
        }

        return (float) $number;
    }
}
