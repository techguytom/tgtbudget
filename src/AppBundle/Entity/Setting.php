<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity
 */
class Setting
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_lead_time", type="integer")
     */
    private $transactionLeadTime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="settings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

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
     * Set transactionLeadTime
     *
     * @param integer $transactionLeadTime
     * @return Setting
     */
    public function setTransactionLeadTime($transactionLeadTime)
    {
        $this->transactionLeadTime = $transactionLeadTime;

        return $this;
    }

    /**
     * Get transactionLeadTime
     *
     * @return integer 
     */
    public function getTransactionLeadTime()
    {
        return $this->transactionLeadTime;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
