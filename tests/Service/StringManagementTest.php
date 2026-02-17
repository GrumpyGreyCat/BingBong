<?php

namespace Tests\Service;

use App\Service\StringManagement;
use PHPUnit\Framework\TestCase;


class StringManagementTest extends TestCase
{

    public function testIsString() {
        $stringManagement = new StringManagement();
        
        $result = $stringManagement->isString('test');
        
        $this->assertEquals(true, $result);
        $this->assertNotEquals(false, $result);
    }

    public function testRemoveFirstCaracter() {
        $stringManagement = new StringManagement();
        
        $result = $stringManagement->removeFirstCaracter('test');
        
        $this->assertEquals('est', $result);
        $this->assertNotEquals('test', $result);
    }
}