<?php
use InMotivClient\Container\VehicleInfoContainer;
use InMotivClient\InMotivClient;
use InMotivClient\ProductionEndpointProvider;
use InMotivClient\SandboxEndpointProvider;
use InMotivClient\XmlBuilder;

class InMotivClientTest extends PHPUnit_Framework_TestCase
{
    public function testIsDriverLicenceValidValid()
    {
        $this->assertTrue($this->getProductionClient()->isDriverLicenceValid(
            getenv('DRIVER_LICENCE_NUMBER'),
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    public function testIsDriverLicenceValidInvalid()
    {
        $this->assertFalse($this->getProductionClient()->isDriverLicenceValid(
            str_repeat('1', strlen(getenv('DRIVER_LICENCE_NUMBER'))),
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    /**
     * @expectedException \InMotivClient\Exception\IncorrectFieldException
     */
    public function testIsDriverLicenceValidWrongNumber()
    {
        $this->assertFalse($this->getProductionClient()->isDriverLicenceValid(
            1,
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    /**
     * @expectedException \InMotivClient\Exception\IncorrectFieldException
     */
    public function testIsDriverLicenceValidwrongDate()
    {
        $this->assertTrue($this->getProductionClient()->isDriverLicenceValid(
            getenv('DRIVER_LICENCE_NUMBER'),
            99,
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    public function testVehicleInfoSuccessCar()
    {
        $result = $this->getProductionClient()->getVehicleInfo(getenv('NUMBERPLATES_CAR'));
        $this->assertInstanceOf(VehicleInfoContainer::class, $result);
        $this->assertSame('SKODA', $result->getBrand());
        $this->assertSame(1197, $result->getEngineCC());
        $this->assertSame(2011, $result->getProductionYear());
        $this->assertSame(105, $result->getHorsePower());
        $this->assertSame(1205, $result->getWeight());
        $this->assertSame(25630, $result->getCatalogPrice());
        $this->assertFalse($result->isMotorcycle());
        $this->assertFalse($result->isStolen());
    }

    public function testVehicleInfoSuccessMotorcycle()
    {
        $result = $this->getProductionClient()->getVehicleInfo(getenv('NUMBERPLATES_MOTORCYCLE'));
        $this->assertInstanceOf(VehicleInfoContainer::class, $result);
        $this->assertSame('HONDA', $result->getBrand());
        $this->assertSame(647, $result->getEngineCC());
        $this->assertSame(2005, $result->getProductionYear());
        $this->assertSame(53, $result->getHorsePower());
        $this->assertSame(221, $result->getWeight());
        $this->assertSame(0, $result->getCatalogPrice());
        $this->assertTrue($result->isMotorcycle());
        $this->assertFalse($result->isStolen());
    }

    /**
     * @expectedException \InMotivClient\Exception\VehicleNotFoundException
     */
    public function testVehicleInfoFail()
    {
        $this->getProductionClient()->getVehicleInfo(str_repeat('1', strlen(getenv('NUMBERPLATES_CAR'))));
    }

    /**
     * @expectedException \InMotivClient\Exception\XmlBuilder\RequestXmlInvalidException
     */
    public function testVehicleInfoInvalidRequestXml()
    {
        $this->getProductionClient()->getVehicleInfo('invalid < xml & value');
    }

    /**
     * @return InMotivClient
     */
    private function getProductionClient()
    {
        $endpointProvider = new ProductionEndpointProvider();
        $xmlBuilder = new XmlBuilder();
        return new InMotivClient(
            $endpointProvider,
            $xmlBuilder,
            getenv('INMOTIV_CLIENT_NUMBER'),
            getenv('INMOTIV_USERNAME'),
            getenv('INMOTIV_PASSWORD'),
            false
        );
    }

    /**
     * @return InMotivClient
     */
    private function getSandboxClient()
    {
        $endpointProvider = new SandboxEndpointProvider();
        $xmlBuilder = new XmlBuilder();
        return new InMotivClient(
            $endpointProvider,
            $xmlBuilder,
            getenv('INMOTIV_CLIENT_NUMBER'),
            getenv('INMOTIV_USERNAME'),
            getenv('INMOTIV_PASSWORD'),
            false
        );
    }
}
