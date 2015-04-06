<?php
/**
 * AccountTest.php
 * 
 * @package AppBundle\Tests\Entity 

 * @subpackage
 */
 
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Account;
use AppBundle\Entity\User;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\Bill;
use Money\Currency;
use Money\Money;

/**
 * Test coverage for Account Entity
 *
 * @package AppBundle\Tests\Entity 

 * @subpackage TGT Budget
 * @author Tom Jenkins <tom@techguytom.com>
  */
class AccountTest extends \PHPUnit_Framework_TestCase
{
    private $account;

    public function __construct()
    {
        $this->account = new Account();
    }
    public function testGetNameReturnsTrueWhenNameSuccessfullySet()
    {
        $name = 'Test Name';

        $this->account->setName($name);

        $this->assertEquals($name, $this->account->getName());
    }

    public function testGetCreditLineReturnsTrueWhenCreditLineSuccessfullySet()
    {
        $this->markTestIncomplete('Not yet implemented');
    }

    public function testGetCurrentBalanceReturnsTrueWhenCurrentBalanceSuccessfullySet()
    {
        $this->markTestIncomplete('Not yet implemented');
    }

    public function testGetTransactionReturnsTrueWhenTransactionsSuccessfullySet()
    {
        $transactions = [
            [new Transaction()],
            [new Transaction()],
        ];

        $this->account->setTransactions($transactions);

        $this->assertEquals($transactions, $this->account->getTransactions());
    }

    public function testGetTypeReturnsTrueWhenTypeSuccessfullySet()
    {
        $this->account->setType('type');

        $this->assertEquals('type', $this->account->getType());
    }

    public function testGetUserReturnsTrueWhenUserSuccessfullySet()
    {
        $user = new User;

        $this->account->setUser($user);

        $this->assertEquals($user, $this->account->getUser());
    }

    public function testGetBillsReturnsTrueWhenBillsSuccessfullySet()
    {
        $bills = [
            [new Bill()],
            [new Bill()],
        ];

        $this->account->setBills($bills);

        $this->assertEquals($bills, $this->account->getBills());
    }
}
