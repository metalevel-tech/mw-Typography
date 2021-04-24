/**
 *  This file is part of wp-Typography.
 *
 *  Copyright 2016, 2018 Peter Putzer.
 *
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *  ***
 *
 *  @package mundschenk-at/wp-typography
 *  @license http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Clean up clipboard content on cut & copy. Removes &shy; and zero-width space.
 *
 * @author Peter Putzer <github@mundschenk.at>
 *
 * @editor Spas Z. Spasov <spas.z.spasov@gmail.com>
 * :ignored tags functionality was added in order be used fluently with MediaWiki
 * as apart of the Extension:mw-Typography
 */
( function ( $, mw ) {
	'use strict';

    // https://stackoverflow.com/questions/7215479/get-parent-element-of-a-selected-text
    function getSelectionParentElement() {
        var parentEl = null, sel;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.rangeCount) {
                parentEl = sel.getRangeAt(0).commonAncestorContainer;
                if (parentEl.nodeType != 1) {
                    parentEl = parentEl.parentNode;
                }
            }
        } else if ( (sel = document.selection) && sel.type != "Control") {
            parentEl = sel.createRange().parentElement();
        }
        return parentEl;
    }

    function doHyphensClean() {
        // From wp-Typography by Peter Putzer
        if ( window.getSelection ) {
            document.addEventListener( 'copy', function() {
                if ( getSelectionParentElement().tagName !== 'PRE' && (![ 'INPUT', 'TEXTAREA', 'PRE' ].includes(document.activeElement.tagName)) ) {

                    var
                        sel        = window.getSelection(),
                        ranges     = [],
                        rangeCount = sel.rangeCount,
                        i, shadow;

                    for ( i = 0; i < rangeCount; i++ ) {
                        ranges[i] = sel.getRangeAt( i );
                    }

                    // Create new div containing cleaned HTML content
                    shadow = $( '<div>', {
                        style: { position: 'absolute', left: '-99999px' },
                        html: $( '<div></div>' ).append( sel.getRangeAt( 0 ).cloneContents() ).html().replace( /\u00AD/gi, '' ).replace( /\u200B/gi, '' )   // default action
                                .replace( /(<span class=\"mw-editsection\">(<([^>]+)>).*<\/span>)/gi, '' )                                                  // skinTimeless
                                .replace( /(<div id=\"(mw-page-header-links|mw-site-navigation|mw-related-navigation)\">(<([^>]+)>).*<\/div>)/gi, '' )      // skinTimeless
                        } );

                    // Append to DOM
                    $( 'body' ).append( shadow );

                    // Select the children of our "clean" div
                    sel.selectAllChildren( shadow[0] );

                    // Clean up after copy
                    window.setTimeout( function() {
                        // Remove div
                        shadow.remove();

                        // Restore selection
                        sel.removeAllRanges();
                        for ( i = 0; i < rangeCount; i++ ) {
                            sel.addRange( ranges[i] );
                        }
                    }, 0 );
                }
            } );
        }
    }

    // Do the hyphens clean
    var typoAction  = mw.config.get( 'wgAction' );
    if ( typoAction === 'view' ) {
        mw.hook( 'wikipage.categories' ).add( doHyphensClean );
    }
}( jQuery, mediaWiki ) );
