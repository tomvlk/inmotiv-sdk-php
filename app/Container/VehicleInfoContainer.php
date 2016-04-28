<?php
namespace InMotivClient\Container;

class VehicleInfoContainer
{
    const CLASS_MOTORCYCLE = 12;
    const CLASS_MOTORCYCLE_WITH_SIDECAR = 13;

    /** @var string */
    private $brand;

    /** @var int */
    private $productionYear;

    /** @var int */
    private $engineCC;

    /** @var int */
    private $horsePower;

    /** @var int */
    private $weight;

    /** @var int */
    private $catalogPrice;

    /** @var bool */
    private $isStolen;

    /** @var string */
    private $raw;

    /**
     * @param string $brand
     * @param int $productionYear
     * @param int $engineCC
     * @param int $horsePower
     * @param int $weight
     * @param int $catalogPrice
     * @param int $rdwClass
     * @param bool $isStolen
     */
    public function __construct($brand, $productionYear, $engineCC, $horsePower, $weight, $catalogPrice, $rdwClass, $isStolen, $raw)
    {
        $this->brand = $brand;
        $this->productionYear = $productionYear;
        $this->engineCC = $engineCC;
        $this->horsePower = $horsePower;
        $this->weight = $weight;
        $this->catalogPrice = $catalogPrice;
        $this->rdwClass = $rdwClass;
        $this->isStolen = $isStolen;
        $this->raw = $raw;
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

    /**
     * @return int
     */
    public function getHorsePower()
    {
        return $this->horsePower;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getCatalogPrice()
    {
        return $this->catalogPrice;
    }

    /**
     * @return bool
     */
    public function isMotorcycle()
    {
        return $this->rdwClass === self::CLASS_MOTORCYCLE || $this->rdwClass === self::CLASS_MOTORCYCLE_WITH_SIDECAR;
    }

    /**
     * @return bool
     */
    public function isStolen()
    {
        return $this->isStolen;
    }
}
