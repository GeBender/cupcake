<?php
namespace Cupcake;


class CacambasTest extends TestHelper
{

	public $AssinantesDAO;
	public $CacambasDAO;
	
    public function setUp()
    {

        self::$Class = new \Cacambas();
        
        self::$Class->app['Auth'] = $this->getMockBuilder('Auth')
        ->disableOriginalConstructor()
        ->setMethods(array('assinanteId'))
        ->getMock();
        
        $this->AssinantesDAO = $this->getMockBuilder('\Apps\Logistick\DAO\AssinantesDAO')
        ->disableOriginalConstructor()
        ->setMethods(array('find'))
        ->getMock();
        $this->CacambasDAO = $this->getMockBuilder('\Apps\Logistick\DAO\CacambasDAO')
        ->disableOriginalConstructor()
        ->setMethods(array('getTotal'))
        ->getMock();
        
        //self::$Class->fs = $this->getMock('Cupcake\Filesystem');

    }
    
    public function testRetornarBooleanLimiteCacambas()
    {
    	$Assinante = new \Assinantes();
    	
    	$this->AssinantesDAO->expects($this->once())->method('find')->will($this->returnValue($Assinante));
    	$this->CacambasDAO->expects($this->once())->method('getTotal')->will($this->returnValue(1));
    	$actual = self::$Class->checaLimite($this->AssinantesDAO, $this->CacambasDAO);
    	$this->assertInternalType("bool", $actual);
    }
    
    public function testLimitesCacambaTrue()
    {
    	$Assinante = new \Assinantes();
    	$Assinante->setItens(50);    	
    	$this->AssinantesDAO->expects($this->once())->method('find')->will($this->returnValue($Assinante));
    	
    	$this->CacambasDAO->expects($this->once())->method('getTotal')->will($this->returnValue(49));
    	$actual = self::$Class->checaLimite($this->AssinantesDAO, $this->CacambasDAO);
    	
    	$this->assertEquals(true, $actual);
    }
    
    public function testLimitesCacambaFalseEquals()
    {
    	$Assinante = new \Assinantes();
    	$Assinante->setItens(50);
    	$this->AssinantesDAO->expects($this->once())->method('find')->will($this->returnValue($Assinante));
    	 
    	$this->CacambasDAO->expects($this->once())->method('getTotal')->will($this->returnValue(50));
    	$actual = self::$Class->checaLimite($this->AssinantesDAO, $this->CacambasDAO);
    	 
    	$this->assertEquals(false, $actual);
    }
    
    public function testLimitesCacambaFalse()
    {
    	$Assinante = new \Assinantes();
    	$Assinante->setItens(50);
    	$this->AssinantesDAO->expects($this->once())->method('find')->will($this->returnValue($Assinante));
    
    	$this->CacambasDAO->expects($this->once())->method('getTotal')->will($this->returnValue(51));
    	$actual = self::$Class->checaLimite($this->AssinantesDAO, $this->CacambasDAO);
    
    	$this->assertEquals(false, $actual);
    }
    
    public function testLimitesCacambaUnlimited()
    {
    	$Assinante = new \Assinantes();
    	$Assinante->setItens(0);
    	$this->AssinantesDAO->expects($this->once())->method('find')->will($this->returnValue($Assinante));
    
    	$this->CacambasDAO->expects($this->once())->method('getTotal')->will($this->returnValue(1000));
    	$actual = self::$Class->checaLimite($this->AssinantesDAO, $this->CacambasDAO);
    
    	$this->assertEquals(true, $actual);
    }

}
