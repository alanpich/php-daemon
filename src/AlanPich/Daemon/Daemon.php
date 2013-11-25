<?php
namespace AlanPich\Daemon;

use AlanPich\Daemon\Exception\ProcessStatusException;

/**
 * Class Daemon
 *
 * @package AlanPich\Daemon
 */
class Daemon
{
    private $cmd;
    private $logFile;
    private $pidFile;

    private $pid = false;

    public function __construct($cmd,$pidFile,$logFile=null)
    {
        $this->cmd = $cmd;
        $this->pidFile = $pidFile;
        $this->logFile = $logFile;

        // Try to load pid for already-running process
        $this->pid = $this->readPid();
    }


    /**
     * Stop the daemon
     *
     * @throws Exception\ProcessStatusException
     * @return bool
     */
    public function stop()
    {
        if($this->pid===false){
            throw new ProcessStatusException("Daemon is not running");
        }
        system("kill {$this->pid}");
        $this->pid = false;
        unlink($this->pidFile);
        return true;
    }


    /**
     * Start the daemon
     *
     * @return bool
     * @throws Exception\ProcessStatusException
     */
    public function start()
    {
        if($this->pid!==false){
            throw new Exception\ProcessStatusException("Daemon is already running");
        }

        $pid = system("{$this->cmd} >{$this->logFile} 2>{$this->logFile} & echo $!");
        file_put_contents($this->pidFile,$pid);
        echo "Daemon started. pid {$pid}\n";
        return true;
    }


    /**
     * Get process status
     *
     * @return bool True if process is running
     */
    public function status()
    {
        return $this->pid !== false;
    }




    protected function readPid()
    {
        if(!file_exists($this->pidFile))
            return false;

        $str = file_get_contents($this->pidFile);
        if(!is_numeric($str))
            return false;

        return (int)$str;
    }



    /**
     * @param mixed $logFile
     */
    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;
    }

    /**
     * @return mixed
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @param mixed $pidFile
     */
    public function setPidFile($pidFile)
    {
        $this->pidFile = $pidFile;
    }

    /**
     * @return mixed
     */
    public function getPidFile()
    {
        return $this->pidFile;
    }


} 