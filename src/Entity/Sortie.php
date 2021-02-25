<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez indiquer un nom de sortie")
     * @Assert\Length(min = 2, max = 100,
     *      minMessage = "Le nom de la ville est trop cours ({{ limit }} caractères min)",
     *      maxMessage = "Le nom de la ville est trop long ({{ limit }} caractères max)")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Assert\NotBlank(message="vous devez indiquer une date")
     */
    private $startedDateTime;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     *  @Assert\Length(min = 1, max = 4,
     *      minMessage = "La durée est trop courte ({{ limit }} caractères min)",
     *      maxMessage = "La durée est trop longue ({{ limit }} caractères max)")
     * @Assert\NotBlank(message="vous devez indiquer une durée en heure")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     * @Assert\NotBlank(message="vous devez indiquer une date")
     */
    private $deadline;

    /**
     * @Assert\NotBlank(message="vous devez indiquer une date limite d'inscription")
     * @ORM\Column(type="integer")
     */
    private $maxNbOfRegistration;

    /**
     * @ORM\Column(type="string", length=3000)
     * @Assert\NotBlank(message="vous devez indiquer une description")
     * @Assert\Length(min = 15, max = 3000,
     *      minMessage = "La description est trop courte ({{ limit }} caractères min)",
     *      maxMessage = "La description est trop longue ({{ limit }} caractères max)")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sortie", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="sorties")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventCreated")
     */
    private $organiser;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;


    public function __construct()
    {
        $this->users = new ArrayCollection();

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

    public function getStartedDateTime(): ?\DateTimeInterface
    {
        return $this->startedDateTime;
    }

    public function setStartedDateTime($startedDateTime): self
    {
        $this->startedDateTime = $startedDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline( $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getMaxNbOfRegistration(): ?int
    {
        return $this->maxNbOfRegistration;
    }

    public function setMaxNbOfRegistration(int $maxNbOfRegistration): self
    {
        $this->maxNbOfRegistration = $maxNbOfRegistration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSortie($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSortie($this);
        }

        return $this;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getOrganiser(): ?User
    {
        return $this->organiser;
    }

    public function setOrganiser(?User $organiser): self
    {
        $this->organiser = $organiser;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu( $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


}
