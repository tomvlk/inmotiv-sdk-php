<?php
namespace InMotivClient;

use InMotivClient\Exception\IncorrectFieldException;
use InMotivClient\Exception\UnexpectedResponseException;

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
     */
    public function vehicleInfo($numberplate)
    {
        $xml = $this->xmlBuilder->buildRequestOpvragenVoertuigscanMSI($this->clientNumber, $numberplate);

        $client = $this->getClient($this->endpointProvider->getVTS());
        $sax = $client->request('opvragenVoertuigscanMSI', $xml);

        echo $sax;
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
}
