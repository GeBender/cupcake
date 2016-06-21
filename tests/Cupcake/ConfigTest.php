<?php
namespace Cupcake;

class ConfigTest extends TestHelper
{


    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$Class = new Vars();

    }


    public function testLoad()
    {
        $actual = Config::load();

        $this->assertInternalType('array', $actual);
    }


    public function testRoutr()
    {
        $actual = Config::route();

        $this->assertInternalType('array', $actual);
    }
}
