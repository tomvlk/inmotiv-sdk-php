<?php
namespace InMotivClient;

use InMotivClient\Container\VehicleInfoContainer;
use InMotivClient\Exception\IncorrectFieldException;
use InMotivClient\Exception\SoapException;
use InMotivClient\Exception\UnexpectedResponseException;
use InMotivClient\Exception\VehicleNotFoundException;
use SimpleXMLElement;

class InMotivClient
{
    /** @var SoapClientWrapper[] */
    private $clients;

    /** @var XmlBuilder */
    private $xmlBuilder;

    /** @var string */
    private $clientNumber;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var bool */
    private $debug;

    /** @var EndpointProviderInterface */
    private $endpointProvider;

    /**
     * @param EndpointProviderInterface $endpointProvider
     * @param XmlBuilder $xmlBuilder
     * @param string $clientNumber
     * @param string $username
     * @param string $password
     * @param bool $debug
     */
    public function __construct(
        EndpointProviderInterface $endpointProvider,
        XmlBuilder $xmlBuilder,
        $clientNumber,
        $username,
        $password,
        $debug = false
    ) {
        $this->endpointProvider = $endpointProvider;
        $this->xmlBuilder = $xmlBuilder;

        $this->clientNumber = $clientNumber;
        $this->username = $username;
        $this->password = $password;

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

        $birthday = sprintf('%04d%02d%02d', $birthYear, $birthMonth, $birthDay);
        $xml = $this->xmlBuilder->buildRequestDocumentVerificatieSysteem(
            $this->clientNumber,
            $drivingLicenceNumber,
            $birthday
        );

        $client = $this->getClient($this->endpointProvider->getDVS());
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
     * @return VehicleInfoContainer
     * @throws VehicleNotFoundException
     * @throws SoapException
     * @throws IncorrectFieldException
     */
    public function getVehicleInfo($numberplate)
    {
        $xml = $this->xmlBuilder->buildRequestOpvragenVoertuigscanMSI($this->clientNumber, $numberplate);

        $client = $this->getClient($this->endpointProvider->getVTS());
        $sxe = $client->request('opvragenVoertuigscanMSI', $xml);

        $nodes = $sxe->xpath('//*[local-name() = "Kentekengegevens"][@Verwerkingsstatus="00"]');
        if (!count($nodes)) {
            throw new VehicleNotFoundException;
        }

        $brand = $this->extractFirstNodeValue($sxe, '//*[local-name() = "Merk"]');
        $productionYear = $this->extractFirstNodeValue($sxe, '//*[local-name() = "DatumEersteToelating"]');
        $cc = $this->extractFirstNodeValue($sxe, '//*[local-name() = "Cilinderinhoud"]');

        $rdwClassSxe = $this->extractFirstNode($sxe, '//*[local-name() = "VoertuigClassificatieRDW"]');
        $rdwClass = (int)$rdwClassSxe->attributes()->Code;

        $rdwClassSxe = $this->extractFirstNode($sxe, '//*[local-name() = "StatusGestolen"]');
        $isStolen = (string)$rdwClassSxe->attributes()->Code !== '0';

        $result = new VehicleInfoContainer($brand, (int)substr($productionYear, 0, 4), (int)$cc, $rdwClass, $isStolen);
        return $result;
    }

    /**
     * @param string $url
     * @return SoapClientWrapper
     */
    private function getClient($url)
    {
        if (isset($this->clients[$url])) {
            return isset($this->clients[$url]);
        }
        $this->clients[$url] = new SoapClientWrapper(
            $url,
            $this->username,
            $this->password,
            $this->xmlBuilder,
            $this->debug
        );
        return $this->clients[$url];
    }

    /**
     * @param SimpleXMLElement $sax
     * @param string $xpathExpression
     * @return string
     */
    private function extractFirstNodeValue(SimpleXMLElement $sax, $xpathExpression)
    {
        return (string)$this->extractFirstNode($sax, $xpathExpression);
    }

    /**
     * @param SimpleXMLElement $sax
     * @param string $xpathExpression
     * @return SimpleXMLElement
     */
    private function extractFirstNode(SimpleXMLElement $sax, $xpathExpression)
    {
        $nodes = $sax->xpath($xpathExpression);
        if (count($nodes) < 1) {
            $msg = sprintf('Expected at lest one node by expression: %s', $xpathExpression);
            throw new UnexpectedResponseException($msg);
        }
        return $nodes[0];
    }
}
