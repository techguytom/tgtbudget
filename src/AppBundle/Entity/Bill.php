<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * Bill
 *
 * @ORM\Table(name="bills")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BillRepository")
 */
class Bill
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
     * @ORM\Column(name="budget_amount", type="integer")
     */
    private $budgetAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="budget_currency", type="string", length=64)
     */
    private $budgetCurrency;


    /**
     * @var integer
     *
     * @ORM\Column(name="due_date", type="date")
     */
    private $dueDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recurring", type="boolean")
     */
    private $recurring;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="bills")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Account", inversedBy="bills")
     * @ORM\JoinColumn(name="pay_to_account_id", referencedColumnName="id")
     */
    protected $payToAccount;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="bills")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="bill")
     */
    protected $transactions;

    /**
     * construct
     *
     * initialize collections and call parent
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->setDueDate(new \DateTime());

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
     * @return bill
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
    public function getBudgetAmount()
    {
        if (!$this->budgetCurrency) {
            return null;
        }
        if (!$this->budgetAmount) {
            return new Money(0, new Currency($this->budgetCurrency));
        }
        return new Money($this->budgetAmount, new Currency($this->budgetCurrency));
    }

    /**
     * Set price
     *
     * @param Money $budgetAmount
     *
     * @return Money
     */
    public function setBudgetAmount(Money $budgetAmount)
    {
        $this->budgetAmount = $budgetAmount->getAmount();
        $this->budgetCurrency = $budgetAmount->getCurrency()->getName();

        return $this;
    }

    /**
     * Get due date
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set due date
     *
     * @param \DateTime $dueDate
     *
     * @return Bill
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * is recurring
     *
     * @return boolean
     */
    public function isRecurring()
    {
        return $this->recurring;
    }

    /**
     * Set recurring
     *
     * @param mixed $recurring
     *
     * @return Bill
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;

        return $this;
    }

    /**
     * Is Paid
     *
     * @return boolean
     */
    public function isPaid()
    {
        return $this->paid;
    }

    /**
     * Set Paid
     *
     * @param boolean $paid
     *
     * @return Bill
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

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
     * @return Bill
     */
    public function setCategory($category)
    {
        $this->category = $category;

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
     * @return Bill
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get Account
     *
     * @return Account
     */
    public function getPayToAccount()
    {
        return $this->payToAccount;
    }

    /**
     * Set Account
     *
     * @param Account $payToAccount
     *
     * @return Bill
     */
    public function setPayToAccount($payToAccount)
    {
        $this->payToAccount = $payToAccount;

        return $this;
    }
}
