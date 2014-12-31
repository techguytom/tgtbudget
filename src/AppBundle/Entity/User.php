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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Account", mappedBy="user")
     */
    protected $accounts;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Setting", mappedBy="user")
     */
    protected $settings;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AccountType", mappedBy="user")
     */
    protected $accountTypes;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Category", mappedBy="user")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="user")
     */
    protected $transactions;

    /**
     * construct
     *
     * initialize collections and call parent
     */
    public function __construct()
    {
        $this->bills        = new ArrayCollection();
        $this->settings     = new ArrayCollection();
        $this->accounts     = new ArrayCollection();
        $this->accountTypes = new ArrayCollection();
        $this->categories   = new ArrayCollection();
        $this->transactions = new ArrayCollection();

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
     * Get Bills
     *
     * @return bill[]|ArrayCollection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Set Bills
     *
     * @param bill[]|ArrayCollection $bills
     *
     * @return User
     */
    public function setBills($bills)
    {
        $this->bills = $bills;

        return $this;
    }

    /**
     * Get Settings
     *
     * @return setting[]|ArrayCollection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set Settings
     *
     * @param setting[]|ArrayCollection $settings
     *
     * @return User
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
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
     * @return User
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Get Account Types
     *
     * @return accountType[]|ArrayCollection
     */
    public function getAccountTypes()
    {
        return $this->accountTypes;
    }

    /**
     * Set Account Types
     *
     * @param accountType[]|ArrayCollection $accountTypes
     *
     * @return User
     */
    public function setAccountTypes($accountTypes)
    {
        $this->accountTypes = $accountTypes;

        return $this;
    }

    /**
     * Get Categories
     *
     * @return category[]|ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set Categories
     *
     * @param category[]|ArrayCollection $categories
     *
     * @return User
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get Transactions
     *
     * @return transaction[]|ArrayCollection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Set Transactions
     *
     * @param transaction[]|ArrayCollection $transactions
     *
     * @return User
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }
}
