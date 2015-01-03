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
     * @ORM\Column(name="credit_line_amount", type="integer", nullable=true)
     */
    private $creditLineAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="credit_line_currency", type="string", length=64, nullable=true)
     */
    private $creditLineCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_balance_amount", type="integer")
     */
    private $currentBalanceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="current_balance_currency", type="string", length=64)
     */
    private $currentBalanceCurrency;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bill", mappedBy="payToAccount")
     */
    protected $bills;

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
        $this->bills        = new ArrayCollection();
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
        $this->creditLineAmount   = $creditLine->getAmount();
        $this->creditLineCurrency = $creditLine->getCurrency()
                                               ->getName();

        return $this;
    }

    /**
     * get Money
     *
     * @return Money
     */
    public function getCurrentBalance()
    {
        if (!$this->currentBalanceCurrency) {
            return null;
        }
        if (!$this->currentBalanceAmount) {
            return new Money(0, new Currency($this->currentBalanceCurrency));
        }
        return new Money($this->currentBalanceAmount, new Currency($this->currentBalanceCurrency));
    }

    /**
     * Set available balance
     *
     * @param Money $currentBalance
     *
     * @return Money
     */
    public function setCurrentBalance(Money $currentBalance)
    {
        $this->currentBalanceAmount   = $currentBalance->getAmount();
        $this->currentBalanceCurrency = $currentBalance->getCurrency()
                                                       ->getName();

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
     * @return Account
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
     * @return Account
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
     * @return Account
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
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
     * $return Account
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    /**
     * Calculate available balance and return
     *
     * @return Money
     */
    public function getAvailableBalance()
    {
        if ($this->getType()
                 ->isCreditAccount()
        ) {
            return $this->getCreditLine()
                        ->subtract($this->getCurrentBalance());
        } else {
            return $this->getCurrentBalance();
        }
    }

    public function isAboveOptimumBalance()
    {
        if (!$this->getType()->isCreditAccount()) {
            return false;
        }

        $optimumAmount = $this->getCreditLine()->getAmount() * .3;
        if ($optimumAmount < $this->getCurrentBalance()->getAmount()) {
            return true;
        }

        return false;
    }
}
