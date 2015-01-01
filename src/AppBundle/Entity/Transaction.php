<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * Transaction
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity
 */
class Transaction
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_amount", type="integer")
     */
    private $transactionAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_currency", type="string", length=64)
     */
    private $transactionCurrency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Account", inversedBy="transactions")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="transactions")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Bill", inversedBy="transactions")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id")
     */
    protected $bill;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Constructor
     *
     * Set default date to today's date
     */
    public function __construct()
    {
        $this->setDate(new \DateTime());
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
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return Transaction
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * get Money
     *
     * @return Money
     */
    public function getTransactionAmount()
    {
        if (!$this->transactionCurrency) {
            return null;
        }
        if (!$this->transactionAmount) {
            return new Money(0, new Currency($this->transactionCurrency));
        }
        return new Money($this->transactionAmount, new Currency($this->transactionCurrency));
    }

    /**
     * Set price
     *
     * @param Money $transactionAmount
     *
     * @return Money
     */
    public function setTransactionAmount(Money $transactionAmount)
    {
        $this->transactionAmount = $transactionAmount->getAmount();
        $this->transactionCurrency = $transactionAmount->getCurrency()->getName();

        return $this;
    }

    /**
     * Get Accounts
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set Accounts
     *
     * @param Account $account
     *
     * @return Transaction
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get Category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Category
     *
     * @param Category $category
     *
     * @return Transaction
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get Bill
     *
     * @return Bill
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set Bill
     *
     * @param Bill $bill
     *
     * @return Transaction
     */
    public function setBill($bill)
    {
        $this->bill = $bill;

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
     * @return Transaction
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
