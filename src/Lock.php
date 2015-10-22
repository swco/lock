<?php
namespace SWCO\Lock;

class Lock
{
    /**
     * @var string Unique name associated with the lock
     */
    private $name;

    /**
     * @var string A directory to write a lock file to
     */
    private $directory;

    /**
     * @var bool Whether this object currently has a lock
     */
    private $hasLock = false;

    /**
     * @var Resource
     */
    private $fp;

    /**
     * @param string $name
     * @param string $directory
     */
    public function __construct($name, $directory = '/tmp')
    {
        $this->name      = $name;
        $this->directory = rtrim($directory, '/');
    }

    /**
     * @return bool If the lock was gained successfully
     */
    public function getLock()
    {
        $this->fp      = fopen(sprintf("%s/%s.lock", $this->directory, $this->name), "w+");
        $this->hasLock = flock($this->fp, LOCK_EX | LOCK_NB);

        return $this->hasLock;
    }

    /**
     * @return bool
     */
    public function hasLock()
    {
        return $this->hasLock;
    }

    /**
     * Returns null if there is no lock to release, otherwise returns bool to show success.
     *
     * @return bool|null
     */
    public function releaseLock()
    {
        $res = null;
        if ($this->hasLock()) {
            $res = flock($this->fp, LOCK_UN);
            $this->hasLock = !$res;
            fclose($this->fp);
        }

        return $res;
    }
}
