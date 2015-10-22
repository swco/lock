<?php
namespace SWCO\Lock\Test;

use SWCO\Lock\Lock;

/**
 * This class is hard to test because file locks apply to the process so locks within a single process
 * always succeed.
 */
class LockTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Lock
     */
    private $lock;

    public function setUp()
    {
        $this->lock = new Lock('testlock');
    }

    public function tearDown()
    {
        $this->lock->releaseLock();
    }

    public function testGetLockCanLockIfNotAlreadyLocked()
    {
        $this->assertTrue($this->lock->getLock());
    }

    public function testGetLockFailsIfAlreadyLocked()
    {
        $this->lock->getLock();

        $lock2 = new Lock('testlock');
        $this->assertFalse($lock2->getLock());
    }

    public function testGetLockCanLockIfUnlocked()
    {
        $this->lock->getLock();
        $this->lock->releaseLock();

        $lock2 = new Lock('testlock');
        $this->assertTrue($lock2->getLock());
    }

    public function testSameInstanceCanAlwaysGetLock()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->assertTrue($this->lock->getLock());
        }
    }
}
