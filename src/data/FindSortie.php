<?php


namespace App\data;


use App\Entity\Campus;
use DateTime;


class FindSortie
{/**
 * @var string|null
 */
    public $q='';

    /**
     * @var string|null
     */
    public $nomSortie;

    /**
     * @var Campus|null
     */
    public $campus;

    /**
     * @var DateTime|null
     */
    public $startDate;


    /**
     * @var DateTime|null
     */
    public $endDate;

    /**
     *
     * @var boolean|null
     */
    public $createdByMe;

    /**
     *
     * @var boolean|null
     */
    public $subscrided;

    /**
     *
     * @var boolean|null
     */
    public $outOfDate;

    /**
     * @return string
     */
    public function getQ(): ?string
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }





    /**
     * @return string
     */
    public function getNomSortie(): ?string
    {
        return $this->nomSortie;
    }

    /**
     * @param string $nomSortie
     */
    public function setNomSortie(string $nomSortie): void
    {
        $this->nomSortie = $nomSortie;
    }



    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return bool|null
     */
    public function getCreatedByMe(): ?bool
    {
        return $this->createdByMe;
    }

    /**
     * @param bool|null $createdByMe
     */
    public function setCreatedByMe(?bool $createdByMe): void
    {
        $this->createdByMe = $createdByMe;
    }

    /**
     * @return bool|null
     */
    public function getSubscrided(): ?bool
    {
        return $this->subscrided;
    }

    /**
     * @param bool|null $subscrided
     */
    public function setSubscrided(?bool $subscrided): void
    {
        $this->subscrided = $subscrided;
    }

    /**
     * @return bool|null
     */
    public function getOutOfDate(): ?bool
    {
        return $this->outOfDate;
    }

    /**
     * @param bool|null $outOfDate
     */
    public function setOutOfDate(?bool $outOfDate): void
    {
        $this->outOfDate = $outOfDate;
    }

    public function __toString(){

        return $this->campus->getName();
    }
}