<?php
namespace InMotivClient\Container;

class VehicleInfoContainer
{
    /** @var string */
    private $brand;

    /** @var int */
    private $productionYear;

    /** @var float */
    private $engineCC;

    /**
     * @param string $brand
     * @param int $productionYear
     * @param float $engineCC
     */
    public function __construct($brand, $productionYear, $engineCC)
    {
        $this->brand = $brand;
        $this->productionYear = $productionYear;
        $this->engineCC = $engineCC;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return int
     */
    public function getProductionYear()
    {
        return $this->productionYear;
    }

    /**
     * @return float
     */
    public function getEngineCC()
    {
        return $this->engineCC;
    }
}
