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
 * @package    stagehand-php-lexer
 * @copyright  2009 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      File available since Release 0.1.0
 */

// {{{ Stagehand_PHP_LexerTest

/**
 * Some tests for Stagehand_PHP_Lexer
 *
 * @package    stagehand-php-lexer
 * @copyright  2009 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Stagehand_PHP_LexerTest extends PHPUnit_Framework_TestCase
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

    /**#@-*/

    /**#@+
     * @access public
     */

    public function setUp() { }

    public function tearDown() { }

    /**
     * @test
     */
    public function lex()
    {
        $lexer = new Stagehand_PHP_Lexer(dirname(__FILE__) . '/LexerTest/example.php');

        $lexerToken = null;
        $parserToken = $lexer->yylex($lexerToken);

        $this->assertEquals($lexerToken->getValue(), '$foo');
        $this->assertEquals($lexerToken->getPosition(), 1);
        $this->assertEquals($parserToken, Stagehand_PHP_Parser::T_VARIABLE);

        $lexerToken = null;
        $parserToken = $lexer->yylex($lexerToken);

        $this->assertEquals($lexerToken->getValue(), '=');
        $this->assertEquals($lexerToken->getPosition(), 3);
        $this->assertEquals($parserToken, ord('='));

        $lexerToken = null;
        $parserToken = $lexer->yylex($lexerToken);

        $this->assertEquals($lexerToken->getValue(), "'example'");
        $this->assertEquals($lexerToken->getPosition(), 5);
        $this->assertEquals($parserToken, Stagehand_PHP_Parser::T_CONSTANT_ENCAPSED_STRING);
    }

    /**
     * @test
     */
    public function getTokens()
    {
        $lexer = new Stagehand_PHP_Lexer(dirname(__FILE__) . '/LexerTest/example.php');

        $tokens = $lexer->getTokens(0, 2);

        $this->assertEquals(count($tokens), 3);
        $this->assertEquals($this->_createCode($tokens), '<?php
$foo ');

        $tokens = $lexer->getTokens(1, 6);

        $this->assertEquals(count($tokens), 6);
        $this->assertEquals($this->_createCode($tokens), '$foo = \'example\';');

        $tokens = $lexer->getTokens();

        $this->assertEquals(count($tokens), 8);
        $this->assertEquals($this->_createCode($tokens),
                            file_get_contents(dirname(__FILE__) . '/LexerTest/example.php')
                            );
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private function _createCode($tokens)
    {
        $code = null;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                $code .= $token[1];
            } else {
                $code .= $token;
            }
        }

        return $code;
    }

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
