<?php
/**
 * @author    Spas Z. Spasov <spas.z.spasov@gmail.com>
 * @copyright 2020 Spas Z. Spasov
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
 * @home      https://github.com/metalevel-tech/mw-Typography
 *
 * This file is a part of the MediaWiki Extension:Typography.
 *
 * This is the actual MediaWiki Extension:Typography.
 * The extension uses the repository PHP-Typography (https://github.com/mundschenk-at/php-typography).
 * The first aim is to provide Hyphenation, but all features of the repository PHP-Typography are available.
 *
 * This project is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * The project is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Sources and References:
 * https://wordpress.org/plugins/wp-typography/        	// the WP plugin that inspired me to start work on the current extension
 * https://github.com/mundschenk-at/php-typography	        // the hyphenation repository
 * https://www.mediawiki.org/wiki/Extension:DollarSign	        // example structure of an extension
 * https://www.mediawiki.org/wiki/Manual:Hooks/ParserAfterTidy
 */


if (!defined('MEDIAWIKI')) {
    die('This file is an extension to MediaWiki and thus not a valid entry point.');
} else {
    // Get the extension's settings array
    global $wgTypography;
    global $wgLanguageCode;

    // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
    // Set the NameSpaces on which the extension will operate
    // Ref: https://www.mediawiki.org/wiki/Manual:Namespace#Built-in_namespaces
    //if (!$wgTypography['AllowedNameSpaces']) {
    //    $wgTypography['AllowedNameSpaces'] = array('false', '1', '2', '3', '4', '5', '6', '7', '10', '11', '12', '13', '14', '15');
    //}

    // Get the Language Locales. If any locales are not provided via $wgTypography['HyphenLanguages'],
    // we will tray to autodetect the wiki's language and will apply some fixes as 'en'->'en-US'.
    // The list of the available languages and their locales: vendor/mundschenk-at/php-typography/src/lang
    if (!$wgTypography['HyphenLanguages']) {
        if ($wgLanguageCode == 'en') {
            $wgTypography['HyphenLanguages'] = array('en-US');
        } else {
            $wgTypography['HyphenLanguages'] = array($wgLanguageCode);
        }
    }

    /**
     * Read the comment at line 187
    // Add support for 'compound words', that contains ':', like as: Категория:Файлове, Category:Files - A better solution is needed!
    // LocalSettings.php example: $wgTypography['ColonWords'] = array('Категория:', 'Category:');
    if (!isset($wgTypography['ColonWords'])) {
        $wgTypography['ColonWords'] = false;
    }
    **/

    // Default settings for PHP-Typography
    // Ref: vendor/mundschenk-at/php-typography/src/class-settings.php   Line: 393
    $wgTypography['SettingsDefault'] = array(
        // General attributes.
        'set_tags_to_ignore' => array(),
        'set_classes_to_ignore' => array(),
        'set_ids_to_ignore' => array(),

        // Smart characters.
        'set_smart_quotes' => false,
        'set_smart_quotes_primary' => 'not_set', // semi auto set before text process below
        'set_smart_quotes_secondary' => 'not_set', // semi auto set before text process below
        'set_smart_quotes_exceptions' => 'not_set',
        'set_smart_dashes' => false,
        'set_smart_dashes_style' => 'not_set', // 'traditionalUS', 'international', 'internationalNoHairSpaces', (https://type.today/en/journal/spaces)
        'set_smart_ellipses' => false,
        'set_smart_diacritics' => false,
        'set_diacritic_language' => 'not_set',
        'set_diacritic_custom_replacements' => 'not_set',
        'set_smart_marks' => false,
        'set_smart_ordinal_suffix' => false,
        'set_smart_ordinal_suffix_match_roman_numerals' => false,
        'set_smart_math' => false,
        'set_smart_fractions' => false,
        'set_smart_exponents' => false,

        // Smart spacing.
        'set_fraction_spacing' => false,
        'set_unit_spacing' => false,
        'set_french_punctuation_spacing' => false,
        'set_units' => false,
        'set_dash_spacing' => false,
        'set_dewidow' => false,
        'set_max_dewidow_length' => 'not_set',
        'set_max_dewidow_pull' => 'not_set',
        'set_dewidow_word_number' => 'not_set',
        'set_wrap_hard_hyphens' => false,
        'set_url_wrap' => false,
        'set_email_wrap' => false,
        'set_min_after_url_wrap' => false,
        'set_space_collapse' => false,
        'set_true_no_break_narrow_space' => true,

        // Character styling.
        'set_style_ampersands' => false,
        'set_style_caps' => false,
        'set_style_initial_quotes' => false,
        'set_style_numbers' => false,
        'set_style_hanging_punctuation' => false,
        'set_initial_quote_tags' => false,

        // Hyphenation.
        'set_hyphenation' => true,
        'set_hyphenation_language' => 'not_set',
        'set_min_length_hyphenation' => 'not_set',
        'set_min_before_hyphenation' => 'not_set',
        'set_min_after_hyphenation' => 'not_set',
        'set_hyphenate_headings' => 'not_set',
        'set_hyphenate_all_caps' => 'not_set',
        'set_hyphenate_title_case' => 'not_set',
        'set_hyphenate_compounds' => 'not_set',
        'set_hyphenation_exceptions' => 'not_set',

        // Parser error handling.
        'set_ignore_parser_errors' => 'not_set',

        // Single character words vendor/mundschenk-at/php-typography/src/class-settings.php:747
        'set_single_character_word_spacing' => false,
    );

    // Merge the user's settings with the default ones
    foreach ($wgTypography['Settings'] as $key => $value) {
        $wgTypography['SettingsDefault'][$key]  = $value;
    }
}


/**
 * This is the main Class of the extension
 * Ref: https://www.mediawiki.org/wiki/Manual:Hooks
 */
class TypographyHooks
{
    /**
     * This is the main function, the one that will process the content.
     * Ref: https://www.mediawiki.org/wiki/Manual:Hooks/ParserAfterTidy
     */

    // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
    //public static function onParserAfterTidy( Parser &$parser, &$text )

    public static function onOutputPageBeforeHTML( OutputPage $out, &$text )
    {
        global $wgTypography;

        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        // Get the current NameSpace
        //$theCurrentNamespace = $parser->getTitle()->getNamespace();

        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        // Test whether the current NameSpace belongs to the Allowed NameSpaces
        //if (in_array($theCurrentNamespace, $wgTypography['AllowedNameSpaces'])) {

            // Load the main resources
            include_once __DIR__ . '/vendor/autoload.php';

            // Create a Settings object and Pass values for the settings
            // For more information: vendor/mundschenk-at/php-typography/src/class-settings.php
            $mwTypographySettings = new \PHP_Typography\Settings();

            foreach ($wgTypography['SettingsDefault'] as $key => $value) {
                if ($value != 'not_set') {
                    $mwTypographySettings->{$key}($value);
                }
            }

            // Process the content
            $mwTypographyTypo = new \PHP_Typography\PHP_Typography();

            /**
             * Such support is added in a better way by modifying line 74 of
             * mundschenk-at/php-typography/src/fixes/token-fixes/class-hyphenate-compounds-fix.php
             * '/(-)/' >> '/(-|\:)/'
             *
            // Add support for 'compound words', that contains ':', like as: Категория:Файлове, Category:Files - A better solution is needed!
            if ($wgTypography['ColonWords']) {
                foreach ($wgTypography['ColonWords'] as $colonWord) {
                    $colonWord_replace_1 = $colonWord . '&shy;';
                    $colonWord_replace_2 = $colonWord . '&shy;&shy;';
                    $text = str_replace($colonWord, $colonWord_replace_1, $text);
                    $text = str_replace($colonWord_replace_2, $colonWord_replace_1, $text);
                }
            }
            **/

            // Process the content multi language, for each language
            foreach ($wgTypography['HyphenLanguages'] as $language) {
                $mwTypographySettings->set_hyphenation_language($language);
                
                if ($language === 'bg') {
                    $mwTypographySettings->set_smart_quotes_primary('doubleLow9Reversed');
                    $mwTypographySettings->set_smart_quotes_secondary('singleLow9Reversed');
                }

                $text = $mwTypographyTypo->process($text, $mwTypographySettings);
            }
            // Process the content for single language
            // $text = $mwTypographyTypo->process($text, $mwTypographySettings);

            return true;
        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        //}
    }

    /**
     * Load the extension's Scripts And Styles
     * Ref: https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
     */
    public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
    {
        global $wgTypography;

        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        // Get the current NameSpace
        //$theCurrentNamespace = $out->getTitle()->getNamespace();

        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        //if (in_array($theCurrentNamespace, $wgTypography['AllowedNameSpaces'])) {
            $out->addModules('TypographyScriptsAndStyles');
            return true;
        // Deprecated, since the main Hook is changed from ParserAfterTidy to OutputPageBeforeHTML!
        //}
    }
}
