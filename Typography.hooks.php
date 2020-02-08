<?php
/**
 * @author    Spas Z. Spasov <spas.z.spasov@gmail.com>
 * @copyright 2020 Spas Z. Spasov
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 (or later)
 * @home      https://github.com/pa4080/mw-Typography
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

    // Set the NameSpaces on which the extension will operate
    // Ref: https://www.mediawiki.org/wiki/Manual:Namespace#Built-in_namespaces
    if (!$wgTypography['AllowedNameSpaces']) {
        $wgTypography['AllowedNameSpaces'] = array('0', '1', '2', '3', '4', '5', '6', '7', '10', '11', '12', '13', '14', '15');
    }

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

    // Add support for 'compound words', that contains ':', like as: Категория:Файлове, Category:Files - A better solution is needed!
    // LocalSettings.php example: $wgTypography['ColonWords'] = array('Категория:', 'Category:');
    if (!$wgTypography['ColonWords']) {
        $wgTypography['ColonWords'] = false;
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
    public static function onParserAfterTidy( Parser &$parser, &$text )
    {
        global $wgTypography;

        // Get the current NameSpace
        $theCurrentNamespace = $parser->getTitle()->getNamespace();

        // Test whether the current NameSpace belongs to the Allowed NameSpaces
        if (in_array($theCurrentNamespace, $wgTypography['AllowedNameSpaces'])) {

            // Load the main resources
            include_once __DIR__ . '/vendor/autoload.php';

            // Create a Settings object and Pass values for the settings
            // For more information: vendor/mundschenk-at/php-typography/src/class-settings.php
            $mwTypographySettings = new \PHP_Typography\Settings();

            // General attributes.
            $mwTypographySettings->set_tags_to_ignore();

            $mwTypographySettings->set_classes_to_ignore([ 'vcard', 'noTypo', 'mw-ui-button', 'mw-whatlinkshere-list' ]);
            $mwTypographySettings->set_ids_to_ignore([ 'articlePhase', 'articleStatus', 'wspmTable', 'articleBelongsTo' ]);

            // Smart characters.
            $mwTypographySettings->set_smart_quotes(false);
            $mwTypographySettings->set_smart_quotes_primary();
            $mwTypographySettings->set_smart_quotes_secondary();
            $mwTypographySettings->set_smart_quotes_exceptions();
            $mwTypographySettings->set_smart_dashes(false);
            $mwTypographySettings->set_smart_dashes_style();
            $mwTypographySettings->set_smart_ellipses(false);
            $mwTypographySettings->set_smart_diacritics(false);
            $mwTypographySettings->set_diacritic_language();
            $mwTypographySettings->set_diacritic_custom_replacements();
            $mwTypographySettings->set_smart_marks(false);
            $mwTypographySettings->set_smart_ordinal_suffix(false);
            $mwTypographySettings->set_smart_ordinal_suffix_match_roman_numerals(false);
            $mwTypographySettings->set_smart_math(false);
            $mwTypographySettings->set_smart_fractions(false);
            $mwTypographySettings->set_smart_exponents(false);

            // Smart spacing.
            $mwTypographySettings->set_fraction_spacing(false);
            $mwTypographySettings->set_unit_spacing(false);
            $mwTypographySettings->set_french_punctuation_spacing(false);
            $mwTypographySettings->set_units(false);
            $mwTypographySettings->set_dash_spacing(false);
            $mwTypographySettings->set_dewidow(false);
            $mwTypographySettings->set_max_dewidow_length();
            $mwTypographySettings->set_max_dewidow_pull();
            $mwTypographySettings->set_dewidow_word_number();
            $mwTypographySettings->set_wrap_hard_hyphens(false);
            $mwTypographySettings->set_url_wrap(false);
            $mwTypographySettings->set_email_wrap(false);
            $mwTypographySettings->set_min_after_url_wrap(false);
            $mwTypographySettings->set_space_collapse(false);
            $mwTypographySettings->set_true_no_break_narrow_space(true);

            // Character styling.
            $mwTypographySettings->set_style_ampersands(false);
            $mwTypographySettings->set_style_caps(false);
            $mwTypographySettings->set_style_initial_quotes(false);
            $mwTypographySettings->set_style_numbers(false);
            $mwTypographySettings->set_style_hanging_punctuation(false);
            $mwTypographySettings->set_initial_quote_tags(false);

            // Hyphenation.
            $mwTypographySettings->set_hyphenation(true);
            //$mwTypographySettings->set_hyphenation_language('bg');
            $mwTypographySettings->set_min_length_hyphenation(4);
            $mwTypographySettings->set_min_before_hyphenation();
            $mwTypographySettings->set_min_after_hyphenation();
            $mwTypographySettings->set_hyphenate_headings();
            $mwTypographySettings->set_hyphenate_all_caps();
            $mwTypographySettings->set_hyphenate_title_case();
            $mwTypographySettings->set_hyphenate_compounds();
            $mwTypographySettings->set_hyphenation_exceptions();

            // Parser error handling.
            $mwTypographySettings->set_ignore_parser_errors();

            // Single character words /vendor/mundschenk-at/php-typography/src/class-settings.php:747
            $mwTypographySettings->set_single_character_word_spacing(false);

            // Process the content
            $mwTypographyTypo = new \PHP_Typography\PHP_Typography();

            // Add support for 'compound words', that contains ':', like as: Категория:Файлове, Category:Files - A better solution is needed!
	    if ($wgTypography['ColonWords']) {
                foreach ($wgTypography['ColonWords'] as $colonWord) {
                    $colonWord_replace_1 = $colonWord . ' ';
                    $colonWord_replace_2 = $colonWord . '  ';
                    $text = str_replace($colonWord, $colonWord_replace_1, $text);
                    $text = str_replace($colonWord_replace_2, $colonWord_replace_1, $text);
                }
            }

            // Process the content multi language, for each language
            foreach ($wgTypography['HyphenLanguages'] as $language) {
                $mwTypographySettings->set_hyphenation_language($language);
                $text = $mwTypographyTypo->process($text, $mwTypographySettings);
            }
            // Process the content for single language
            // $text = $mwTypographyTypo->process($text, $mwTypographySettings);

            return true;
        }
    }


    /**
     * Load the extension's Scripts And Styles
     * Ref: https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
     */
    public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
    {
        global $wgTypography;

        // Get the current NameSpace
        $theCurrentNamespace = $out->getTitle()->getNamespace();

        // Test whether the current NameSpace belongs to the Allowed NameSpaces
        if (in_array($theCurrentNamespace, $wgTypography['AllowedNameSpaces'])) {
            $out->addModules('TypographyScriptsAndStyles');
            return true;
        }
    }
}
