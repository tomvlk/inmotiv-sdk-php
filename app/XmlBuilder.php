<?php
namespace InMotivClient;

use DOMDocument;
use InMotivClient\Exception\XmlBuilder\PlaceholderNotFoundException;
use InMotivClient\Exception\XmlBuilder\RequestXmlInvalidException;

class XmlBuilder
{
    /**
     * @param string $username
     * @param string $password
     * @param string $nonce
     * @return string
     */
    public function renderHeader($username, $password, $nonce)
    {
        $data = [
            'username' => $username,
            'password' => $password,
            'nonce' => $nonce,
        ];
        return $this->buildXml('soapHeader', $data);
    }

    /**
     * @param string $clientNumber
     * @param string $drivingLicenceNumber
     * @param string $birthday
     * @return string
     */
    public function renderDocumentVerificatieSysteem($clientNumber, $drivingLicenceNumber, $birthday)
    {
        $data = [
            'rdc' => $clientNumber,
            'drivingLicenceNumber' => $drivingLicenceNumber,
            'driverBirthday' => $birthday,
        ];
        return $this->buildXml('documentVerificatieSysteem', $data);
    }

    /**
     * @param string $clientNumber
     * @param string $numberplate
     * @return string
     */
    public function renderOpvragenVoertuigscanMSI($clientNumber, $numberplate)
    {
        $data = [
            'rdc' => $clientNumber,
            'numberplate' => $numberplate,
        ];
        return $this->buildXml('opvragenVoertuigscanMSI', $data);
    }

    /**
     * @param string $templateFilename
     * @param array $vars
     * @return string
     * @throws PlaceholderNotFoundException
     */
    protected function buildXml($templateFilename, array $vars = [])
    {
        $template = $this->loadTemplate($templateFilename);
        return $this->render($template, $vars);
    }

    /**
     * @param string $template
     * @param array $vars
     * @return string
     */
    protected function render($template, array $vars)
    {
        foreach ($vars as $k => $v) {
            $search = sprintf('{{ %s }}', $k);
            if (false === strpos($template, $search)) {
                $msg = sprintf('Placeholder for key %s not found in the template', $k);
                throw new PlaceholderNotFoundException($msg);
            }
            $template = str_replace($search, $v, $template);
        }

        $dom = new DOMDocument;
        if (!@$dom->loadXML('<?xml version="1.0" encoding="UTF-8"?>' . $template)) {
            throw new RequestXmlInvalidException;
        }

        return $template;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function loadTemplate($filename)
    {
        $path = sprintf(__DIR__ . '/../resources/xmlTemplates/%s.xml', $filename);
        return file_get_contents($path);
    }
}
