<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints\UniqueName;

/**
 * Category
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 * @UniqueName()
 */
class Category
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="category")
     */
    protected $transactions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bill", mappedBy="category")
     */
    protected $bills;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="categories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * construct
     *
     * initialize array collections
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
     * @return Category $this
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
     * @return Category
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Get Bills
     *
     * @return transaction[]|ArrayCollection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Set Bills
     *
     * @param transaction[]|ArrayCollection $bills
     *
     * @return Category
     */
    public function setBills($bills)
    {
        $this->bills = $bills;

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
     * @return Category
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
