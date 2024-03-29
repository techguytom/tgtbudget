<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints\UniqueName;

/**
 * AccountType
 *
 * @ORM\Table(name="account_types")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AccountTypeRepository")
 * @UniqueName()
 */
class AccountType
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="credit_account", type="boolean", nullable=true)
     */
    private $creditAccount;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Account", mappedBy="type")
     */
    protected $accounts;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="accountTypes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * constructor
     *
     * initialize array collections
     */
    public function __construct()
    {
        $this->accounts = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return AccountType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Accounts
     *
     * @return account[]|ArrayCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set Accounts
     *
     * @param account[]|ArrayCollection $accounts
     *
     * @return $this
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Is Credit Account
     *
     * @return boolean
     */
    public function isCreditAccount()
    {
        return $this->creditAccount;
    }

    /**
     * Set Credit Account
     *
     * @param boolean $creditAccount
     *
     * @return Category $this
     */
    public function setCreditAccount($creditAccount)
    {
        $this->creditAccount = $creditAccount;

        return $this;
    }
}
