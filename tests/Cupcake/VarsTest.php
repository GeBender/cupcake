<?php
namespace Cupcake;

class VarsTest extends TestHelper
{


    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$Class = new Vars();

    }


    public function testDeveSetarPorCallDinamico()
    {
        $expected = 'valor';
        self::$Class->setIndex($expected);
        $actual = self::$Class->vars['index'];

        $this->assertEquals($expected, $actual);

    }


    public function testDeveLerDinamicamente()
    {
        $expected = 'valor';
        self::$Class->setIndex($expected);
        $actual = self::$Class->getIndex();

        $this->assertEquals($expected, $actual);

    }


    public function testDeveRetornarFalseSeCallForHasENaoHouverVariavel()
    {
        $expected = false;
        $actual = self::$Class->hasTeste();

        $this->assertEquals($expected, $actual);

    }


    public function testDeveRetornarTrueSeCallForHasEHouverVariavel()
    {
        $expected = true;
        self::$Class->vars['teste'] = 'teste';
        $actual = self::$Class->hasTeste();

        $this->assertEquals($expected, $actual);

    }


    public function testDeveRetornarFalseSePedirVariavelInexistente()
    {
        $expected = false;
        $actual = self::$Class->getTeste3();
        $this->assertEquals($expected, $actual);

    }


    public function testDeveLerDinamicamenteSemPrefixoGet()
    {
        $expected = 'valor';
        self::$Class->setIndex($expected);
        $actual = self::$Class->index();

        $this->assertEquals($expected, $actual);

    }


    public function testValidaArgRetornaFalsoQuandoNaoTiverIndiceZero()
    {
        $expected = false;
        $actual = self::$Class->validaArg(array());

        $this->assertEquals($expected, $actual);

    }


    public function testValidaArgRetornaVarQuandoTiverIndiceZero()
    {
        $expected = 'valor';
        $actual = self::$Class->validaArg(array(
                $expected
        ));

        $this->assertEquals($expected, $actual);

    }


    public function testGetter()
    {
        $expected = array(
                'teste' => 'val1',
                'teste2' => 'val2'
        );

        self::$Class->vars = $expected;
        $actual = self::$Class->get();

        $this->assertEquals($expected, $actual);

    }


}
