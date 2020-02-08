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
 * https://wordpress.org/plugins/wp-typography/			// the WP plugin that inspired me to start work on the current extension
 * https://github.com/mundschenk-at/php-typography	        // the hyphenation repository
 * https://www.mediawiki.org/wiki/Extension:DollarSign	        // example structure of an extension
 * https://www.mediawiki.org/wiki/Manual:Hooks/ParserAfterTidy
 *
 */


if( !defined('MEDIAWIKI') ) {
    die( 'This is an extension to the MediaWiki package and cannot be run standalone.' );
}
/**
 * if ( version_compare( $GLOBALS[ 'wgVersion' ], '1.7', 'lt' ) ) {
 * 	die( '<b>Error:</b> This version of mw-Typography is only tested on MW 1.7.x and above' );
 * }
**/

// The extension version
define( 'mwTypographyVersion', '0.1.0' );


// register the extension
$GLOBALS[ 'wgExtensionCredits' ][ 'other' ][ ] = array(
	'path' 		 => __FILE__,
	'name' 		 => 'mw-Typography',
	'author' 	 => 'Spas Z. Spasov',
	'url' 		 => 'https://github.com/pa4080/mw-Typography',
     //	'descriptionmsg' => 'Uses the repository https://github.com/mundschenk-at/php-typography and process the mediawiki text through it in order to insert hyphens inside.',
	'description' 	 => 'Uses the repository https://github.com/mundschenk-at/php-typography and process the mediawiki text through it in order to insert hyphens inside.',
	'version' 	 => mwTypographyVersion,
	'license-name'   => 'GPL-3.0+',
);

if (!defined('MEDIAWIKI')) {
    die('This file is an extension to MediaWiki and thus not a valid entry point.');
} else {
    global $IP;
    global $wgServer;
    global $wgPWAC;

    $wgPWAC['IP']                 = (($wgPWAC['IP'])                 ? $wgPWAC['IP']                 : $IP);
 
}

// Set the NameSpaces on which the extension will operate
global $mwTypographyAllowedNameSpaces;
$mwTypographyAllowedNameSpaces = array('0', '1', '10', '11', '4', '5', '6', '7', '14', '15', '20', '21');


/**
 * The main functionality of the extension
**/

// Parse function for php-typography
function mwTypography( &$parser, &$text ) {

    // Dial with the language codes
    global $wgLanguageCode;
    if ( $wgLanguageCode == 'en' ) {
	$mwHyphenationLanguage = 'en-US';
    } else {
	$mwHyphenationLanguage = $wgLanguageCode;
    }

    // Dial with the namespaces in use
    global $mwTypographyAllowedNameSpaces;
    $theCurrentNamespace = $parser->getTitle()->getNamespace();


    if (in_array($theCurrentNamespace, $mwTypographyAllowedNameSpaces)) {

	// Load the main resources
	require_once __DIR__ . '/vendor/autoload.php';

	/**
	 * Create a Settings object and Pass values for the settings
	 * For more information look at: vendor/mundschenk-at/php-typography/src/class-settings.php
	**/
	$mwTypographySettings = new \PHP_Typography\Settings();

	// General attributes.
	$mwTypographySettings->set_tags_to_ignore();
	//$mwTypographySettings->set_classes_to_ignore( [ 'vcard', 'noTypo', 'catlinks', 'new', 'mw-normal-catlinks', 'plainlinks', 'mw-ui-button', 'mw-whatlinkshere-list' ] );
	$mwTypographySettings->set_classes_to_ignore( [ 'vcard', 'noTypo', 'mw-ui-button', 'mw-whatlinkshere-list' ] );
	$mwTypographySettings->set_ids_to_ignore( [ 'articlePhase', 'articleStatus', 'wspmTable', 'articleBelongsTo' ] );

	// Smart characters.
	$mwTypographySettings->set_smart_quotes( false );
	$mwTypographySettings->set_smart_quotes_primary();
	$mwTypographySettings->set_smart_quotes_secondary();
	$mwTypographySettings->set_smart_quotes_exceptions();
	$mwTypographySettings->set_smart_dashes( false );
	$mwTypographySettings->set_smart_dashes_style();
	$mwTypographySettings->set_smart_ellipses( false );
	$mwTypographySettings->set_smart_diacritics( false );
	$mwTypographySettings->set_diacritic_language();
	$mwTypographySettings->set_diacritic_custom_replacements();
	$mwTypographySettings->set_smart_marks( false );
	$mwTypographySettings->set_smart_ordinal_suffix( false );
	$mwTypographySettings->set_smart_ordinal_suffix_match_roman_numerals( false );
	$mwTypographySettings->set_smart_math( false );
	$mwTypographySettings->set_smart_fractions( false );
	$mwTypographySettings->set_smart_exponents( false );

	// Smart spacing.
	$mwTypographySettings->set_fraction_spacing( false );
	$mwTypographySettings->set_unit_spacing( false );
	$mwTypographySettings->set_french_punctuation_spacing( false );
	$mwTypographySettings->set_units( false );
	$mwTypographySettings->set_dash_spacing( false );
	$mwTypographySettings->set_dewidow( false );
	$mwTypographySettings->set_max_dewidow_length();
	$mwTypographySettings->set_max_dewidow_pull();
	$mwTypographySettings->set_dewidow_word_number();
	$mwTypographySettings->set_wrap_hard_hyphens( false );
	$mwTypographySettings->set_url_wrap( false );
	$mwTypographySettings->set_email_wrap( false );
	$mwTypographySettings->set_min_after_url_wrap( false );
	$mwTypographySettings->set_space_collapse( false );
	$mwTypographySettings->set_true_no_break_narrow_space( true );

	// Character styling.
	$mwTypographySettings->set_style_ampersands( false );
	$mwTypographySettings->set_style_caps( false );
	$mwTypographySettings->set_style_initial_quotes( false );
	$mwTypographySettings->set_style_numbers( false );
	$mwTypographySettings->set_style_hanging_punctuation( false );
	$mwTypographySettings->set_initial_quote_tags( false );

	// Hyphenation.
	$mwTypographySettings->set_hyphenation( true );
	//$mwTypographySettings->set_hyphenation_language( 'bg' );
	$mwTypographySettings->set_hyphenation_language( $mwHyphenationLanguage );
	$mwTypographySettings->set_min_length_hyphenation( 4 );
	$mwTypographySettings->set_min_before_hyphenation();
	$mwTypographySettings->set_min_after_hyphenation();
	$mwTypographySettings->set_hyphenate_headings();
	$mwTypographySettings->set_hyphenate_all_caps();
	$mwTypographySettings->set_hyphenate_title_case();
	$mwTypographySettings->set_hyphenate_compounds();
	$mwTypographySettings->set_hyphenation_exceptions( [ 'ст' ] );

	// Parser error handling.
	$mwTypographySettings->set_ignore_parser_errors();

	// Single character words /vendor/mundschenk-at/php-typography/src/class-settings.php:747
	$mwTypographySettings->set_single_character_word_spacing( false );


	/**
	 * Process the content
	**/

	$mwTypographyTypo = new \PHP_Typography\PHP_Typography();
	$text = $mwTypographyTypo->process( $text , $mwTypographySettings );

	return true;

    }

}
// Register hook for php-typography
$wgHooks['ParserAfterTidy'][] = 'mwTypography';



/**
 * Remove &shy; when copy the text to the clipboard
 * https://stackoverflow.com/questions/29902768/how-to-add-custom-global-javascript-to-mediawiki
 * https://www.php.net/manual/en/language.variables.scope.php
**/

// register a ResourceLoader module...
$wgResourceModules['mwTypographyResources'] = array(
    'scripts' => array( 'extensions/mw-Typography/js/mw-TypographyCleanClipboard.js' ),
    'styles' => array( 'extensions/mw-Typography/css/mw-Typography.css' ),
    // could e.g. add dependencies on core modules here
);

// ...and set up a hook to add it to every page
function mwTypographyResourcesLoader( &$out ) {

    global $mwTypographyAllowedNameSpaces;
    $theCurrentNamespace = $out->getTitle()->getNamespace();

    if (in_array($theCurrentNamespace, $mwTypographyAllowedNameSpaces)) {
        $out->addModules( 'mwTypographyResources' );

        return true;

    }

}
// Register hook
$wgHooks['BeforePageDisplay'][] = 'mwTypographyResourcesLoader';
