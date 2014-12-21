<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * Bill
 *
 * @ORM\Table(name="bills")
 * @ORM\Entity
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
     * @ORM\Column(name="due_date", type="integer")
     */
    private $dueDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="bills")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="bills")
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
     * @return integer
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set due date
     *
     * @param integer $dueDate
     * @return Bill
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
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
