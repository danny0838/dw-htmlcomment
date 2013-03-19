<?php
/**
 * HTML Comment Plugin: allows HTML comments to be retained in the output
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Arndt <chris@chrisarndt.de>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

define('HTMLCOMMENT_SAFE', true);

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_htmlcomment extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Christopher Arndt',
            'email'  => 'chris@chrisarndt.de',
            'date'   => '2005-10-08',
            'name'   => 'HTML Comment Plugin',
            'desc'   => 'allows HTML comments to be retained in the output',
            'url'    => 'http://wiki.splitbrain.org/plugin:html_comment',
        );
    }

    function getType() {
        return 'substition';
    }

    function getSort() {
        return 325;
    }

    function connectTo($mode) {
        $this->Lexer->addSpecialPattern("<\!--.*?-->", $mode,
          'plugin_htmlcomment');
    }

    function handle($match, $state, $pos, &$handler) {
        if ($state == DOKU_LEXER_SPECIAL) {
             // strip <!-- from start and --> from end
            $match = substr($match,4,-3);
            return array($state, $match);
        }
        return array();
    }

    function render($mode, &$renderer, $data) {
        if ($mode == 'xhtml') {
            list($state, $match) = $data;
            if ($state == DOKU_LEXER_SPECIAL) {
                $renderer->doc .= '<!--';
                if (HTMLCOMMENT_SAFE) {
                    $renderer->doc .= $renderer->_xmlEntities($match);
                } else {
                    $renderer->doc .= $match;
                }
                $renderer->doc .= '-->';
            }
            return true;
        }
        return false;
    }
}

?>
