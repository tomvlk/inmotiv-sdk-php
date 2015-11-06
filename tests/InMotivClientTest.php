<?php
use InMotivClient\Container\VehicleInfoContainer;
use InMotivClient\InMotivClient;
use InMotivClient\ProductionEndpointProvider;
use InMotivClient\XmlBuilder;

class InMotivClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InMotivClient
     */
    private $client;

    public function setUp()
    {
        $endpointProvider = new ProductionEndpointProvider();
        $xmlBuilder = new XmlBuilder();
        $this->client = new InMotivClient(
            $endpointProvider,
            $xmlBuilder,
            getenv('INMOTIV_CLIENT_NUMBER'),
            getenv('INMOTIV_USERNAME'),
            getenv('INMOTIV_PASSWORD'),
            false
        );
    }

    public function test_isDriverLicenceValid_valid()
    {
        $this->assertTrue($this->client->isDriverLicenceValid(
            getenv('DRIVER_LICENCE_NUMBER'),
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    public function test_isDriverLicenceValid_invalid()
    {
        $this->assertFalse($this->client->isDriverLicenceValid(
            str_repeat('1', strlen(getenv('DRIVER_LICENCE_NUMBER'))),
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    /**
     * @expectedException \InMotivClient\Exception\IncorrectFieldException
     */
    public function test_isDriverLicenceValid_wrongNumber()
    {
        $this->assertFalse($this->client->isDriverLicenceValid(
            1,
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    /**
     * @expectedException \InMotivClient\Exception\IncorrectFieldException
     */
    public function test_isDriverLicenceValid_wrongDate()
    {
        $this->assertTrue($this->client->isDriverLicenceValid(
            getenv('DRIVER_LICENCE_NUMBER'),
            99,
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    public function test_vehicleInfo_success()
    {
        $result = $this->client->getVehicleInfo(getenv('NUMBERPLATES'));
        $this->assertInstanceOf(VehicleInfoContainer::class, $result);
        $this->assertSame('HONDA', $result->getBrand());
        $this->assertSame(647, $result->getEngineCC());
        $this->assertSame(2005, $result->getProductionYear());
    }

    /**
     * @expectedException \InMotivClient\Exception\VehicleNotFoundException
     */
    public function test_vehicleInfo_fail()
    {
        $this->client->getVehicleInfo(str_repeat('1', strlen(getenv('NUMBERPLATES'))));
    }

    /**
     * @expectedException \InMotivClient\Exception\XmlBuilder\RequestXmlInvalidException
     */
    public function test_vehicleInfo_invalidXml()
    {
        $this->client->getVehicleInfo('invalid < xml & value');
    }
}
