<?php
namespace App\Libraries;

set_include_path(get_include_path() . PATH_SEPARATOR . public_path() . '/phpseclib');

// echo get_include_path(); die;

require_once('Net/SSH2.php');
require_once('Crypt/RSA.php');

function getSSH() {  
    return new \Net_SSH2('111.255.255.255');
}

function getKey() {
    return new \Crypt_RSA();
}
/**
 * Client for Plesk SSH
 */
class PleskSshClient
{
    private $_host;
    private $_port;
    private $_protocol;
    private $_login;
    private $_password;
    private $_secretKey;

    private $ssh;
    private $key;

    /**
     * Create client
     *
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    public function __construct()
    {
        $this->ssh = getSSH();
        $this->key = getKey();
        $this->key->setPassword('sampleAdminPassword');

        $this->key->loadKey(file_get_contents(public_path() . '/phpseclib/id_rsa'));
        if (!$this->ssh->login('root', $this->key)) {
            exit('Login Failed');
        }
    }

    /**
     * Plesk SSH request
     *
     * @param string $request
     * @return string
     */
    public function request($cmd)
    {
        return $this->ssh->exec($cmd);
    }

    public function getErrors()
    {
        return $this->ssh->getLastError();
    }

    public function getLog(){
        return $this->ssh->getLog();
    }
}
