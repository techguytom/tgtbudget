<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="tgt_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bill", mappedBy="user")
     */
    protected $bills;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Setting", mappedBy="user")
     */
    protected $settings;

    /**
     * construct
     *
     * initialize collections and call parent
     */
    public function __construct()
    {
        $this->bills    = new ArrayCollection();
        $this->settings = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bill[]|ArrayCollection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param bill[]|ArrayCollection $bills
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    /**
     * @return setting[]|ArrayCollection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param setting[]|ArrayCollection $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
