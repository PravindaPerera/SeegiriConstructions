<?php

namespace sepBundle\Tests\Controller;

//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use sepBundle\Controller\UserProfileController;

class UserProfileControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testProfitCalc()
    {
        $userProfile = new UserProfileController();
        $result1 = $userProfile->calcProfit(10000, 7000, 2000);
        $this->assertEquals(1000, $result1);
    }
    
    public function testClosingBalance() {
        $userProfile = new UserProfileController();
        $result2 = $userProfile->calcClossingbalance(20000, 10000, 2000);
        $this->assertEquals(12000, $result2);
    }
    
    public function testHashing() {
        $userProfile = new UserProfileController();
        $pass = "Test123";
        $result3 = $userProfile->calchasing($pass);
        $this->assertEquals("68eacb97d86f0c4621fa2b0e17cabd8c", $result3);
    }
    
    public function testEncryption() {
        $userProfile = new UserProfileController();
        $string = "Increase the cement supply";
        $key = "abc";
        $result4 = $userProfile->calcEncryption($string, $key);
        $this->assertEquals(10, $result4);
    }
}

