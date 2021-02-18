<?php


namespace App\data;


use App\Entity\Campus;

class FindSortie
{
    /**
     * @var string
     */
    public $q='';

    /**
     * @var string
     */
    private $nomSortie;

    /**
     * @var Campus[]
     */
    private $nomCampus;

    /**
     * @var Date()
     */
    private $dateDebut;

    /**
     * @var Date()
     */
    private $dateFin;

    /**
     *
     * @var boolean|null
     */
    private $mesSorties;

    /**
     *
     * @var boolean|null
     */
    private $mesInscriptions;

    /**
     *
     * @var boolean|null
     */
    private $sortiesFinies;
}