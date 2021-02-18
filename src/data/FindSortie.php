<?php


namespace App\data;


use App\Entity\Campus;

class FindSortie
{/**
 * @var string
 */
    public $q='';

    /**
     * @var string|null
     */
    private $nomSortie;

    /**
     * @var Campus[]
     */
    private $Campus;

    /**
     * @var Date|null
     */
    private $startDate;


    /**
     * @var Date|null
     */
    private $endDate;

    /**
     *
     * @var boolean|null
     */
    private $createdByMe;

    /**
     *
     * @var boolean|null
     */
    private $subscrided;

    /**
     *
     * @var boolean|null
     */
    private $outOfDate;

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }

    /**
     * @return Campus[]
     */
    public function getCampus(): array
    {
        return $this->Campus;
    }

    /**
     * @param Campus[] $Campus
     */

    public function setCampus(array $Campus): void
    {
        $this->Campus = $Campus;
    }

    /**
     * @return string
     */
    public function getNomSortie(): string
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
     * @return Date|null
     */
    public function getStartDate(): ?Date
    {
        return $this->startDate;
    }

    /**
     * @param Date|null $startDate
     */
    public function setStartDate(?Date $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return Date|null
     */
    public function getEndDate(): ?Date
    {
        return $this->endDate;
    }

    /**
     * @param Date|null $endDate
     */
    public function setEndDate(?Date $endDate): void
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





}