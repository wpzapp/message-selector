<?php
/**
 * Tests for the message selector class.
 *
 * @package WPZAPP\MessageSelector
 * @license GPL-3.0
 * @link    https://wpzapp.org
 */

namespace WPZAPP\MessageSelector\Tests;

use WPZAPP\MessageSelector\MessageSelector;
use WPZAPP\MessageSelector\PluralizationRule;
use WPZAPP\Locale\Locale;
use WPZAPP\Locale\Language;
use WPZAPP\Locale\Country;
use PHPUnit\Framework\TestCase;

class MessageSelectorTest extends TestCase
{

    public function testGetLocale()
    {
        $locale = new Locale(new Language('en'), new Country('US'));

        $messageSelector = new MessageSelector($locale);
        $this->assertSame($locale, $messageSelector->getLocale());
    }

    public function testGetPluralizationRule()
    {
        $locale            = new Locale(new Language('en'), new Country('US'));
        $pluralizationRule = new PluralizationRule($locale, function() {
            return 0;
        });

        $messageSelector = new MessageSelector($locale, $pluralizationRule);
        $this->assertSame($pluralizationRule, $messageSelector->getPluralizationRule());
    }
}
