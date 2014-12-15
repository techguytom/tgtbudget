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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
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
     * @return Money
     */
    public function setTransactionAmount(Money $transactionAmount)
    {
        $this->transactionAmount = $transactionAmount->getAmount();
        $this->transactionCurrency = $transactionAmount->getCurrency()->getName();

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
