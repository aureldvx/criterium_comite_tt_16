<?php

namespace CTT\CriteriumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Liste
 *
 * @ORM\Table(name="liste")
 * @ORM\Entity(repositoryClass="CTT\CriteriumBundle\Repository\ListeRepository")
 */
class Liste
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
     * @ORM\Column(name="licence", type="string", length=255)
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    private $sexe;

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
     * @var int
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="echelon", type="string", length=255, nullable=true)
     */
    private $echelon;

    /**
     * @var string
     *
     * @ORM\Column(name="club", type="string", length=255, nullable=true)
     */
    private $club;

    /**
     * @var int
     *
     * @ORM\Column(name="tour", type="integer", nullable=true)
     */
    private $tour;


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
     * Set licence.
     *
     * @param string $licence
     *
     * @return Liste
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

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Liste
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
     * @return Liste
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
     * Set points.
     *
     * @param int|null $points
     *
     * @return Liste
     */
    public function setPoints($points = null)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return int|null
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set categorie.
     *
     * @param string|null $categorie
     *
     * @return Liste
     */
    public function setCategorie($categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie.
     *
     * @return string|null
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set echelon.
     *
     * @param string|null $echelon
     *
     * @return Liste
     */
    public function setEchelon($echelon = null)
    {
        $this->echelon = $echelon;

        return $this;
    }

    /**
     * Get echelon.
     *
     * @return string|null
     */
    public function getEchelon()
    {
        return $this->echelon;
    }

    /**
     * Set club.
     *
     * @param string|null $club
     *
     * @return Liste
     */
    public function setClub($club = null)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club.
     *
     * @return string|null
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set sexe.
     *
     * @param string $sexe
     *
     * @return Liste
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe.
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set tour.
     *
     * @param string|null $tour
     *
     * @return Liste
     */
    public function setTour($tour = null)
    {
        $this->tour = $tour;

        return $this;
    }

    /**
     * Get tour.
     *
     * @return string|null
     */
    public function getTour()
    {
        return $this->tour;
    }
}
