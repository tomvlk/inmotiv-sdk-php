<?php
namespace InMotivClient;

use InMotivClient\Exception\IncorrectFieldException;
use InMotivClient\Exception\UnexpectedResponseException;

class InMotivClient
{
    const DVS_WSDL = 'https://services.rdc.nl/dvs/1.0/wsdl';
    const VTS_WSDL = 'https://services.rdc.nl/voertuigscan/2.0/wsdl';

    /** @var string */
    private $rdcClientNumber;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var bool */
    private $debug;

    /** @var InMotivSoapClient[] */
    private $clients;

    /** @var XmlBuilder */
    private $xmlBuilder;

    /**
     * @param string $rdcClientNumber
     * @param string $username
     * @param string $password
     * @param XmlBuilder $xmlBuilder
     * @param bool $debug
     */
    public function __construct($rdcClientNumber, $username, $password, XmlBuilder $xmlBuilder, $debug = false)
    {
        $this->rdcClientNumber = $rdcClientNumber;
        $this->username = $username;
        $this->password = $password;
        $this->xmlBuilder = $xmlBuilder;
        $this->debug = $debug;
    }

    /**
     * @param string $drivingLicenceNumber
     * @param int $birthYear
     * @param int $birthMonth
     * @param int $birthDay
     * @return bool
     */
    public function isDriverLicenceValid($drivingLicenceNumber, $birthYear, $birthMonth, $birthDay)
    {
        if (!is_numeric($drivingLicenceNumber)) {
            throw new IncorrectFieldException('Driving licence number should be numeric');
        }

        $client = $this->getClient(self::DVS_WSDL);

        $birthday = sprintf('%04d%02d%02d', $birthYear, $birthMonth, $birthDay);
        $xml = $this->xmlBuilder->renderDocumentVerificatieSysteem(
            $this->rdcClientNumber,
            $drivingLicenceNumber,
            $birthday
        );

        $sax = $client->request('documentVerificatieSysteem', $xml);

        $nodes = $sax->xpath('//*[local-name() = "RIJBEWIJSGELDIG"]');
        if (!count($nodes)) {
            throw new UnexpectedResponseException('Expected node RIJBEWIJSGELDIG not found');
        }

        $value = (string)reset($nodes);
        return $value === 'J';
    }

    /**
     * @param string $numberplate
     */
    public function vehicleInfo($numberplate)
    {
        $client = $this->getClient(self::VTS_WSDL);

        $xml = $this->xmlBuilder->renderOpvragenVoertuigscanMSI($this->rdcClientNumber, $numberplate);
        $sax = $client->request('opvragenVoertuigscanMSI', $xml);

        echo $sax;
    }

    /**
     * @param string $url
     * @return InMotivSoapClient
     */
    private function getClient($url)
    {
        if (isset($this->clients[$url])) {
            return isset($this->clients[$url]);
        }
        $this->clients[$url] = new InMotivSoapClient(
            $url,
            $this->username,
            $this->password,
            $this->xmlBuilder,
            $this->debug
        );
        return $this->clients[$url];
    }
}
