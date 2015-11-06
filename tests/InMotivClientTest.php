<?php
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

    public function test_isDriverLicenceValid_invalidXml()
    {
        $this->markTestIncomplete();
        $this->assertTrue($this->client->isDriverLicenceValid(
            '<invalidXml',
            getenv('BIRTHDAY_YEAR'),
            getenv('BIRTHDAY_MONTH'),
            getenv('BIRTHDAY_DAY')
        ));
    }

    public function test_vehicleInfo_success()
    {
        $this->client->vehicleInfo('22PBR4');
    }
}
