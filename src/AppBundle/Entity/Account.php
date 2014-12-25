<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * Account
 *
 * @ORM\Table(name="accounts")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AccountRepository")
 */
class Account
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
     * @var integer
     *
     * @ORM\Column(name="account_number", type="integer")
     */
    private $accountNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_line_amount", type="integer")
     */
    private $creditLineAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="credit_line_currency", type="string", length=64)
     */
    private $creditLineCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="available_balance_amount", type="integer")
     */
    private $availableBalanceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="available_balance_currency", type="string", length=64)
     */
    private $availableBalanceCurrency;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="account")
     */
    protected $transactions;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AccountType", inversedBy="accounts")
     * @ORM\JoinColumn(name="account_type", referencedColumnName="id")
     */
    protected $type;

    /**
     * Constructor
     *
     * instantiate array collections.
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
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
     * @return Account
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
     * Set accountNumber
     *
     * @param integer $accountNumber
     *
     * @return Account
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return integer 
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * get Money
     *
     * @return Money
     */
    public function getCreditLine()
    {
        if (!$this->creditLineCurrency) {
            return null;
        }
        if (!$this->creditLineAmount) {
            return new Money(0, new Currency($this->creditLineCurrency));
        }
        return new Money($this->creditLineAmount, new Currency($this->creditLineCurrency));
    }

    /**
     * Set credit line
     *
     * @param Money $creditLine
     *
     * @return Money
     */
    public function setCreditLine(Money $creditLine)
    {
        $this->creditLineAmount = $creditLine->getAmount();
        $this->creditLineCurrency = $creditLine->getCurrency()->getName();

        return $this;
    }

    /**
     * get Money
     *
     * @return Money
     */
    public function getAvailableBalance()
    {
        if (!$this->availableBalanceCurrency) {
            return null;
        }
        if (!$this->availableBalanceAmount) {
            return new Money(0, new Currency($this->availableBalanceCurrency));
        }
        return new Money($this->availableBalanceAmount, new Currency($this->availableBalanceCurrency));
    }

    /**
     * Set available balance
     *
     * @param Money $availableBalance
     *
     * @return Money
     */
    public function setAvailableBalance(Money $availableBalance)
    {
        $this->availableBalanceAmount = $availableBalance->getAmount();
        $this->availableBalanceCurrency = $availableBalance->getCurrency()->getName();

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
     * @return $this
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

    /**
     * Get Account Type
     *
     * @return AccountType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Account Type
     *
     * @param AccountType $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
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
}
