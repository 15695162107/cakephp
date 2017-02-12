<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Test\TestCase\Http\Client;

use Cake\Chronos\Chronos;
use Cake\Http\Client\Cookie\Cookie;
use Cake\TestSuite\TestCase;

/**
 * HTTP cookies test.
 */
class CookieTest extends TestCase
{

    /**
     * Test invalid cookie name
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The cookie name `no, this wont, work` contains invalid characters.
     */
    public function testValidateNameInvalidChars()
    {
        new Cookie('no, this wont, work', '');
    }

    /**
     * Test empty cookie name
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The cookie name cannot be empty.
     */
    public function testValidateNameEmptyName()
    {
        new Cookie('', '');
    }

    /**
     * Test decrypting the cookie
     *
     * @return void
     */
    public function testDecrypt()
    {
        $cookie = new Cookie('cakephp', 'Q2FrZQ==.N2Y1ODQ3ZDAzYzQzY2NkYTBlYTkwMmRkZjFmNGI3Mjk4ZWY5ZmExYTA4YmM2ZThjOWFhZWY1Njc4ZDZlMjE4Y/fhI6zv+siabYg0Cnm2j2P51Sghk7WsVxZr94g5fhmkLJ4ve7j54v9r5/vHSIHtog==');
        $this->assertEquals('Q2FrZQ==.N2Y1ODQ3ZDAzYzQzY2NkYTBlYTkwMmRkZjFmNGI3Mjk4ZWY5ZmExYTA4YmM2ZThjOWFhZWY1Njc4ZDZlMjE4Y/fhI6zv+siabYg0Cnm2j2P51Sghk7WsVxZr94g5fhmkLJ4ve7j54v9r5/vHSIHtog==', $cookie->getValue());

        $cookie->decrypt('someverysecretkeythatisatleast32charslong');
        $this->assertEquals('cakephp-rocks-and-is-awesome', $cookie->getValue());
    }

    /**
     * Testing encrypting the cookie
     *
     * @return void
     */
    public function testEncrypt()
    {
        $cookie = new Cookie('cakephp', 'cakephp-rocks-and-is-awesome');
        $cookie->encrypt('someverysecretkeythatisatleast32charslong');
        $this->assertNotEmpty('cakephp-rocks-and-is-awesome', $cookie->getValue());
    }

    /**
     * Tests the header value
     *
     * @return void
     */
    public function testToHeaderValue()
    {
        $cookie = new Cookie('cakephp', 'cakephp-rocks');
        $result = $cookie->toHeaderValue();
        $this->assertEquals('cakephp=cakephp-rocks', $result);

        $date = Chronos::createFromFormat('m/d/Y h:m:s', '12/1/2050 12:00:00');

        $cookie = new Cookie('cakephp', 'cakephp-rocks');
        $cookie->setDomain('cakephp.org');
        $cookie->expiresAt($date);
        $result = $cookie->toHeaderValue();
        $this->assertEquals('cakephp=cakephp-rocks; expires=Wed, 01-Dec-2049 12:00:00 GMT; domain=cakephp.org', $result);
    }

    /**
     * Test getting the value from the cookie
     *
     * @return void
     */
    public function testGetValue()
    {
        $cookie = new Cookie('cakephp', 'cakephp-rocks');
        $result = $cookie->getValue();
        $this->assertEquals('cakephp-rocks', $result);

        $cookie = new Cookie('cakephp', '');
        $result = $cookie->getValue();
        $this->assertEquals('', $result);
    }
}
