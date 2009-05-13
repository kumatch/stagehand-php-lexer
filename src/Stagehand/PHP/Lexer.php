<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5
 *
 * Copyright (c) 2009 KUMAKURA Yousuke <kumatch@gmail.com>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    sh-php-lexer
 * @copyright  2009 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      File available since Release 0.1.0
 */

// {{{ Stagehand_PHP_Lexer

/**
 * A class for PHP lex.
 *
 * @package    sh-php-lexer
 * @copyright  2009 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Stagehand_PHP_Lexer
{
    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private $_position;
    private $_tokens;

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ __construct()

    /**
     * Loads a PHP script file.
     *
     * @param string $filename  PHP script filename.
     */
    function __construct($filename)
    {
        $this->_position = 0;
        $this->_tokens = token_get_all(file_get_contents($filename));
    }

    // }}}
    // {{{ yylex()

    /**
     * Lexs a PHP script.
     *
     * @param object $yylval
     * @return mixed
     */
    function yylex(&$yylval)
    {
        while (1) {
            $currentPosition = $this->_position;
            $token = @$this->_tokens[$currentPosition];
            ++$this->_position;

            if (!$token) {
                return 0;
            }

            if (!is_array($token)) {
                $yylval = new Stagehand_PHP_Lexer_Token($token, $currentPosition);
                return ord($yylval);
            } else {
                $name = token_name($token[0]);
                $yylval = new Stagehand_PHP_Lexer_Token($token[1], $currentPosition);

                $ignoreList = array('T_OPEN_TAG', 'T_CLOSE_TAG', 'T_WHITESPACE',
                                    'T_COMMENT', 'T_DOC_COMMENT', 'T_INLINE_HTML', 
                                    );
                if (in_array($name, $ignoreList)) {
                    continue;
                }

                if ($name === 'T_DOUBLE_COLON') {
                    return Stagehand_PHP_Parser::T_PAAMAYIM_NEKUDOTAYIM;
                }

                return constant("Stagehand_PHP_Parser::{$name}");
            }
        }
    }

    // }}}
    // {{{ getTokens()

    /**
     * Gets tokens.
     *
     * @param integer $startPosition  number of start position
     * @param integer $endPosition    number of end position
     * @return array
     */
    function getTokens($startPosition = 0, $endPosition = -1)
    {
        if ($endPosition < 0) {
            $endPosition = count($this->_tokens) - 1;
        }

        $tokens = array();
        for ($i = $startPosition; $i <= $endPosition; ++$i) {
            if (isset($this->_tokens[$i])) {
                array_push($tokens, $this->_tokens[$i]);
            }
        }

        return $tokens;
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
