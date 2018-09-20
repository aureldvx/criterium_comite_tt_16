<?php

namespace CTT\CriteriumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="CTT\CriteriumBundle\Repository\ParticipationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var bool
     *
     * @ORM\Column(name="participation", type="boolean")
     */
    private $participation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateChoix", type="datetime")
     */
    private $dateChoix;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateModification", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var string
     *
     * @ORM\Column(name="licence", type="string", length=255, unique=true)
     */
    private $licence;


    /**
     * @ORM\PrePersist
     */
    public function createDate()
    {
        $this->setDateChoix(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateModification()
    {
        $this->setDateModification(new \DateTime());
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Participation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string $prenom
     *
     * @return Participation
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance.
     *
     * @param \DateTime $dateNaissance
     *
     * @return Participation
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance.
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set participation.
     *
     * @param bool $participation
     *
     * @return Participation
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;

        return $this;
    }

    /**
     * Get participation.
     *
     * @return bool
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * Set dateChoix.
     *
     * @param \DateTime $dateChoix
     *
     * @return Participation
     */
    public function setDateChoix($dateChoix)
    {
        $this->dateChoix = $dateChoix;

        return $this;
    }

    /**
     * Get dateChoix.
     *
     * @return \DateTime
     */
    public function getDateChoix()
    {
        return $this->dateChoix;
    }

    /**
     * Set dateModification.
     *
     * @param \DateTime|null $dateModification
     *
     * @return Participation
     */
    public function setDateModification($dateModification = null)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification.
     *
     * @return \DateTime|null
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set licence.
     *
     * @param string $licence
     *
     * @return Participation
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Get licence.
     *
     * @return string
     */
    public function getLicence()
    {
        return $this->licence;
    }
}
