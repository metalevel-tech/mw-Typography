# mw-Typography

MediaWiki Extension that uses the repository [php-typography](https://github.com/mundschenk-at/php-typography) and process the wiki text through it in order to hyphen the text. The first aim is to provide Hyphenation, but all features of the repository PHP-Typography are available.

***This is a Beta stable version**, that currently meets my desires and I decided to share it. I'm just enthusiast that can use Google search and Stack Exchange services. This is my first attempt with MediaWiki extensions. So this extension could be done in more professional way. I'm still learning.*

## Features

* **Hyphenation of the wiki text.** The repository [php-typography](https://github.com/mundschenk-at/php-typography) has more features (than this), and currently most of them are available via the user's configuration.

* **Wiki family support**. The repository [php-typography](https://github.com/mundschenk-at/php-typography) supports over [70 languages](vendor/mundschenk-at/php-typography/src/lang). So the extension has the following options:

  * Autodetect the wiki's language - in this case the extension will detect the wiki's language, and process the text within that wiki by this **single language**. There are some locales limitations of this autodetection - for example the locale `en` will be converted to `en-US`, but such filters are not applied for any other language. I would prefer the other option!

  * Manual set the wiki's language**s** - with this option you can manually set the wiki's content language. [Or you can set more than one content languages in order to translate your **multi language** wiki's content]. In order to activate this option you need to add a line as the follow in your `LocalSettings.php` file:

    ````php
    $wgTypography['HyphenLanguages'] = array('bg', 'en-US', 'ru');
    ````

     You can see the list of the available language and their locales in the directory [vendor/mundschenk-at/php-typography/src/lang](vendor/mundschenk-at/php-typography/src/lang).

* **Justify the text.** The extension will add a small portion of [CSS code](modules/TypographyStyle.css) in order to justify your wiki content. **It is better (faster) to place CCS such this in your `MediaWiki:Common.css`.** In the next version the loading of this CSS code will be optional.

* **Clear clipboard.** The extension uses an additional JavaScript in order to remove the `&shy;` signs from the text when you copy it. This JavaScript is borrowed from [wp-Typography](https://wordpress.org/plugins/wp-typography/). The current implementation of the [script](/modules) has a bug: When you copy text from `pre` tag, the new line characters are dismissed. The temporal solution is to copy the entire `pre` tag plus small portion of text from any surrounded tag `div`, `p`, etc.

* **Name spaces limitation. Deprecated, since the main Hook is changed from [ParserAfterTidy](https://www.mediawiki.org/wiki/Manual:Hooks/ParserAfterTidy) to [OutputPageBeforeHTML](https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageBeforeHTML)!** 

  <s>There is an array that defines the numerical values of the name spaces on which the extension should operate. You can setup the desired values within your `LocalSettings.php`. The default values are:

  ````php
  $wgTypography['AllowedNameSpaces'] = array('0', '1', '2', '3', '4', '5', '6', '7', '10', '11', '12', '13', '14', '15');
  ````

  Please do not add MediaWiki's `Special:` pages (NS 8 and 9) to this array, because their processing time is too slow.</s>

* **Compound words additional support.** Via the configuration array `$wgTypography['ColonWords']` you can provide a list of  'compound words' (for example that contain `:`, like as `Категория:Файлове`, `Category:Files`) in order to divide them in two parts (by adding a space `:_`). In this way the two parts will be processed properly and will be hyphenated. There is an identical option within the settings of the repository PHP-Typography, but it will add two surrounding spaces to the colon sign `_:_`. By default the current option is disabled. In order to enable it, add a line as the follow in your `LocalSettings.php` file:

   ````php
   $wgTypography['ColonWords'] = array('Категория:', 'Category:', 'МедияУики:', 'MediaWiki:', 'Extension:');
   ````

   If you want to use the native PHP-Typography's option read the next section.

* **PHP-Typography settings.** All settings of the main repository PHP-Typography are available via the array `$wgTypography['Settings']` that should be added to your `LocalSettings.php` file in a way as this:

  ````php
  $wgTypography['Settings'] = array(
      'set_tags_to_ignore' => array('code', 'kbd', 'pre'),
      'set_classes_to_ignore' => array( 'vcard', 'noTypo', 'mw-ui-button', 'mw-whatlinkshere-list'),
      'set_ids_to_ignore' => array('articlePhase', 'articleStatus', 'wspmTable', 'articleBelongsTo'),
      'set_hyphenation' => true,
      'set_min_length_hyphenation' => 4,
      'set_min_before_hyphenation' => 2,
      'set_single_character_word_spacing' => false,
  );
  ````
  
  You can read the extension's default values within the beginning of the file [`Typography.hooks.php`](/Typography.hooks.php). You can read about the meaning of each option within the file [`/vendor/mundschenk-at/php-typography/src/class-settings.php`](vendor/mundschenk-at/php-typography/src/class-settings.php).

## Installation

Clone (download) the extension's repository in your MediaWiki `$IP/extensions` directory:

````bash
cd $IP/extensions
sudo git clone https://github.com/pa4080/mw-Typography.git Typography # HTTPS
sudo git clone git@github.com:pa4080/mw-Typography.git Typography     # SSH

cd $IP/extensions/Typography
git branch -a
sudo git checkout the_desired_branch
````

* In order to setup SSH access read: [How do I setup SSH key based authentication for GitHub by using .ssh/config?](https://askubuntu.com/a/1097078/566421) Note you should have enough rights to the repository.

The current repository redistributes [php-typography](https://github.com/mundschenk-at/php-typography), if you want to [update](https://github.com/mundschenk-at/php-typography#installation) it, you can use Composer in this way:

````bash
cd $IP/extensions/Typography
sudo composer update            # sudo chown -R www-data:www-data ./ && sudo -u www-data composer update
php vendor/bin/update-iana.php  # sudo -u www-data php vendor/bin/update-iana.php
````

Finally enable the extension in `LocalSettings.php` of your MediaWiki instance and optionnaly add some user's configuration settings:

````php
wfLoadExtension( 'Typography' );

// Optionally apply your custom configuration
$wgTypography['HyphenLanguages'] = array('bg', 'en-US', 'ru');
$wgTypography['AllowedNameSpaces'] = array('0', '1', '2', '3', '4', '5', '6', '7', '10', '11', '12', '13', '14', '15', '20', '21');
$wgTypography['ColonWords'] = array('Категория:', 'Category:', 'МедияУики:', 'MediaWiki:', 'Extension:');
$wgTypography['Settings'] = array(
    'set_tags_to_ignore' => array('code', 'kbd', 'pre'),
    'set_classes_to_ignore' => array( 'vcard', 'noTypo', 'mw-ui-button', 'mw-whatlinkshere-list'),
    'set_ids_to_ignore' => array('articlePhase', 'articleStatus', 'wspmTable', 'articleBelongsTo'),
    'set_hyphenation' => true,
    'set_min_length_hyphenation' => 4,
    'set_min_before_hyphenation' => 2,
    'set_single_character_word_spacing' => false,
);
````

* Note all settings added by `LocalSettings.php` will override the default values!

Navigate to `Special:Version` to check whether `Extension:Typography` is enabled. It should works now.

## Requirements

Requirements transcluded from [Code by Der Mundschenk & Cie. /wp-Typography](https://code.mundschenk.at/wp-typography/):

* The host server must run `PHP 5.6.0` **or later**,
* The installation of PHP must include the [`mbstring`](https://www.php.net/manual/en/mbstring.installation.php) extension,
* The text must be `UTF‐8` encoded.

MediaWiki version compatibility:

* The extension is tested with MediaWiki **1.32**, **1.33**, **1.34** and **1.35**. The hooks - <s>[ParserAfterTidy](https://www.mediawiki.org/wiki/Manual:Hooks/ParserAfterTidy)</s> [OutputPageBeforeHTML](https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageBeforeHTML) and [BeforePageDisplay](https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay) - used in the extension are available from version <s>1.5</s> 1.6 and 1.7. According to that **MediaWiki 1.7** should be the oldest version that could use this extension. Currently, independently of the branch, the minimal verion is set, by the [`extension.json`](/extension.json) file, to 1.30.

* I will issue a branch of this extension after each MediaWiki's branch that I'm following on my production or test environment.

* After a branch is issued I do not intent to apply further edits to it, so most recent updates will be available in the master branch of this repository.

## Screen shots and tests

The extension is tested on a MediaWiki Family that supports Bulgarian, Russian and English languages and it works nice - as it is expected. In my opinion, of doctor of engineering science, this is must have feature for each CMS!

![Example 1.](.images/comparison_between_articles_with_and_without_hyphenation.png)

## Performance

Here is a performance comparison between processing multilingual text (BG, RU, EN) with enabled or disabled Typography extension. You can see the performance difference is not so huge with a relatively large page that contains about 10k characters (which is about 5 printed pages).

| Prerformance      | NewPP limit report CPU/Real time usage     | Language       | Browser total time |
| ---               | ---                                        | ---            | ---                |
| 160,000 chars     | 0.190 / 0.200 sec (160k chars / 2 Mb post) | Mixed BG/EN/RU | 1.8 sec            |
| 160,000 **+Typo** | 0.226 / 0.308 sec (160k chars / 2 Mb post) | Mixed BG/EN/RU | 8.9 sec            |

Note when the page is already rendered and loaded from the cache these are not the actual times.

## Acknowledgement

Special thanks to [Peter Putzer](https://code.mundschenk.at/), the author of [wp-Typography](https://wordpress.org/plugins/wp-typography/), who [pointed me](https://wordpress.org/support/topic/excellent-great-and-essential-plugin/) in the right direction.

## Work in progress notes

* https://www.mediawiki.org/wiki/Manual:Hooks
* Currently used hooks can't process the StructuredDiscussions (Flow) pages - [reference](https://www.mediawiki.org/wiki/Topic:V2lkq91o5myfo6r0)
