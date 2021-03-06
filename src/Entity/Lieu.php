<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez indiquer un nom de Lieu")
     * @Assert\Length(min = 2, max = 100,
     *      minMessage = "Le nom du lieu est trop cours ({{ limit }} caractères min)",
     *      maxMessage = "Le nom du lieu est trop long ({{ limit }} caractères max)")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="vous devez indiquer un nom de rue")
     * @Assert\Length(min = 2, max = 150,
     *      minMessage = "Le nom de la rue est trop cours ({{ limit }} caractères min)",
     *      maxMessage = "Le nom de la rue est trop long ({{ limit }} caractères max)")
     */
    private $rue;

    /**
     * @ORM\Column(type="float")
     * @Assert\Length(min = 5, max = 20,
     *      minMessage = "Les coordonnées sont trop courte ({{ limit }} caractères min)",
     *      maxMessage = "Les coordonnées sont  trop longue ({{ limit }} caractères max)")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Length(min = 5, max = 20,
     *      minMessage = "Les coordonnées sont trop courte ({{ limit }} caractères min)",
     *      maxMessage = "Les coordonnées sont  trop longue ({{ limit }} caractères max)")
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux", cascade={"persist"} )
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="lieu")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorties(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->setLieu($this);
        }

        return $this;
    }

    public function removeSorties(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getLieu() === $this) {
                $sortie->setLieu(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
