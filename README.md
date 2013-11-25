# Daemon Processes
====================

Spawns background processes via PHP. Can also stop them again

## Installation
````json
{
    "require": {
        "alanpich/php-daemon": "*"
    }
}
````


## Usage
````php

// Command to execute
$script = 'php '.dirname(__FILE__).'/index.php';
// File to log to
$logFile = dirname(__FILE__).'/log.txt';
// Path to save pid file to
$pidFile = dirname(__FILE__).'/process.pid';
// Create the daemon wrapper
$daemon = new \AlanPich\Daemon\DaemonProcess($script,$pidFile,$logFile);


$daemon->start();

echo $daemon->status()? 'RUNNING' : 'NOT RUNNING';

$daemon->stop();
````