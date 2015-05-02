<?php // tests/Selenium/SeleneseTest.php
/**
 * http://docs.seleniumhq.org/docs/02_selenium_ide.jsp#selenium-commands-selenese
 */

class SeleneseTests extends PHPUnit_Extensions_SeleniumTestCase {

    public static $browsers = array(
      array(
        'name'    => 'Firefox on Windows 8',
        'browser' => '*firefox C:\\Program Files (x86)\\Mozilla Firefox\\firefox.exe',
        'host'    => PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_HOST,
        'port'    => 4444,
        'timeout' => 10000,
      )
    );
    
    public static $seleneseDirectory = PHPUNIT_SELENESE_DIR;

    protected function setUp() {
      $this->setBrowserUrl(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_BASEURL);
    }

}