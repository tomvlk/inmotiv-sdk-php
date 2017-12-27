<?php
namespace InMotivClient\Container;

class VehicleInfoContainer
{
    const CLASS_MOTORCYCLE = 12;
    const CLASS_MOTORCYCLE_WITH_SIDECAR = 13;

    /** @var string */
    private $kenteken;

    /** @var string */
    private $brand;

    /** @var string */
    private $typeName;

    /** @var string */
    private $typeSpecification;

    /** @var \DateTime */
    private $firstRegistration;

    /** @var \DateTime */
    private $firstAdmission;

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
    private $rawResponse;

    /**
     * @param string $kenteken
     * @param string $brand
     * @param string $typeName
     * @param string $typeSpecification
     * @param \DateTime $firstRegistration
     * @param \DateTime $firstAdmission
     * @param int $productionYear
     * @param int $engineCC
     * @param int $horsePower
     * @param int $weight
     * @param int $catalogPrice
     * @param int $rdwClass
     * @param bool $isStolen
     * @param string $rawResponse
     */
    public function __construct(
        $kenteken,
        $brand,
        $typeName,
        $typeSpecification,
        $productionYear,
        $firstRegistration,
        $firstAdmission,
        $engineCC,
        $horsePower,
        $weight,
        $catalogPrice,
        $rdwClass,
        $isStolen,
        $rawResponse
    )
    {
        $this->kenteken = $kenteken;
        $this->brand = $brand;
        $this->typeName = $typeName;
        $this->typeSpecification = $typeSpecification;
        $this->productionYear = $productionYear;
        $this->firstAdmission = $firstAdmission;
        $this->firstRegistration = $firstRegistration;
        $this->engineCC = $engineCC;
        $this->horsePower = $horsePower;
        $this->weight = $weight;
        $this->catalogPrice = $catalogPrice;
        $this->rdwClass = $rdwClass;
        $this->isStolen = $isStolen;
        $this->rawResponse = $rawResponse;
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

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @return string
     */
    public function getKenteken()
    {
        return $this->kenteken;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return \DateTime
     */
    public function getFirstRegistration()
    {
        return $this->firstRegistration;
    }

    /**
     * @return string
     */
    public function getTypeSpecification()
    {
        return $this->typeSpecification;
    }

    /**
     * @return \DateTime
     */
    public function getFirstAdmission()
    {
        return $this->firstAdmission;
    }
}
