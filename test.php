<?php
$wgTypography['SettingsDefault'] = array(
    // General attributes.
    'set_tags_to_ignore' => '',
    'set_classes_to_ignore' => array( 'vcard', 'noTypo', 'mw-ui-button', 'mw-whatlinkshere-list'),
    'set_ids_to_ignore' => array( 'articlePhase', 'articleStatus', 'wspmTable', 'articleBelongsTo' ),

    // Smart characters.
    'set_smart_quotes' => 'false',
    'set_smart_quotes_primary' => '',
    'set_smart_quotes_secondary' => '',
    'set_smart_quotes_exceptions' => '',
    'set_smart_dashes' => 'false',
    'set_smart_dashes_style' => '',
    'set_smart_ellipses' => 'false',
    'set_smart_diacritics' => 'false',
    'set_diacritic_language' => '',
    'set_diacritic_custom_replacements' => '',
    'set_smart_marks' => 'false',
    'set_smart_ordinal_suffix' => 'false',
    'set_smart_ordinal_suffix_match_roman_numerals' => 'false',
    'set_smart_math' => 'false',
    'set_smart_fractions' => 'false',
    'set_smart_exponents' => 'false',

    // Smart spacing.
    'set_fraction_spacing' => 'false',
    'set_unit_spacing' => 'false',
    'set_french_punctuation_spacing' => 'false',
    'set_units' => 'false',
    'set_dash_spacing' => 'false',
    'set_dewidow' => 'false',
    'set_max_dewidow_length' => '',
    'set_max_dewidow_pull' => '',
    'set_dewidow_word_number' => '',
    'set_wrap_hard_hyphens' => 'false',
    'set_url_wrap' => 'false',
    'set_email_wrap' => 'false',
    'set_min_after_url_wrap' => 'false',
    'set_space_collapse' => 'false',
    'set_true_no_break_narrow_space' => 'true',

    // Character styling.
    'set_style_ampersands' => 'false',
    'set_style_caps' => 'false',
    'set_style_initial_quotes' => 'false',
    'set_style_numbers' => 'false',
    'set_style_hanging_punctuation' => 'false',
    'set_initial_quote_tags' => 'false',

    // Hyphenation.
    'set_hyphenation' => 'true',
    'set_hyphenation_language' => 'will be set later',
    'set_min_length_hyphenation' => '4',
    'set_min_before_hyphenation' => '',
    'set_min_after_hyphenation' => '',
    'set_hyphenate_headings' => '',
    'set_hyphenate_all_caps' => '',
    'set_hyphenate_title_case' => '',
    'set_hyphenate_compounds' => '',
    'set_hyphenation_exceptions' => '',

    // Parser error handling.
    'set_ignore_parser_errors' => '',

    // Single character words /vendor/mundschenk-at/php-typography/src/class-settings.php:747
    'set_single_character_word_spacing' => 'false',
);

$wgTypography['Settings'] = array(
    'set_min_after_hyphenation' => '1234',
    'set_hyphenate_headings' => '---',
    'set_hyphenate_all_caps' => '',
    'set_hyphenate_title_case' => '345',
    'set_hyphenate_compounds' => '',
    'set_hyphenation_exceptions' => '234',
    'set_ignore_parser_errors' => '111',

);

$wgTypography['Settings']['set_min_length_hyphenation'] = '22222222222';
$wgTypography['Settings']['set_single_character_word_spacing'] = 'TRUE';

foreach ($wgTypography['Settings'] as $key => $value) {
    $wgTypography['SettingsDefault'][$key]  = $value;
}

foreach ($wgTypography['SettingsDefault'] as $key => $value) {
    //echo $key . ' => ' . $value . '<br>';

    echo "\$mwTypographySettings->$key($value)";
    echo '<br>';
}

