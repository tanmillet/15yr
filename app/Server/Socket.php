<?php
namespace App\Server;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Socket
 *
 * @author 七彩P1
 */


class Socket {

    private static $instance;
    private $connection = null;
    private $connectionState = false;
    private $defaultHost = "127.0.0.1";
    private $defaultPort = 80;
    private $defaultTimeout = 10;
    public  $debug = false;

    function __construct($socktConfig ="") {
        if(!$socktConfig){
            $socktConfig = config("socket.connect.one");
        }
        $this->defaultHost=$socktConfig['socketIp'];
        $this->defaultPort=$socktConfig['socketPort'];
        isset($socktConfig['connectionState']) && $this->connectionState=$socktConfig['connectionState'];
        isset($socktConfig['defaultTimeout']) && $this->defaultTimeout=$socktConfig['defaultTimeout'];
        isset($socktConfig['debug']) &&  $this->debug=$socktConfig['debug'];
        
        if(!$this->connection){
            $this->connect();
        }
    }

    /**
     * Singleton pattern. Returns the same instance to all callers
     *
     * @return Socket
     */
    public static function singleton() {
        if (self::$instance == null || !self::$instance instanceof Socket) {
            self::$instance = new Socket();
        }
        return self::$instance;
    }

    /**
     * Connects to the socket with the given address and port
     * 
     * @return void
     */
    public function connect($serverHost = false, $serverPort = false, $timeOut = false) {
        if ($serverHost == false) {
            $serverHost = $this->defaultHost;
        }

        if ($serverPort == false) {
            $serverPort = $this->defaultPort;
        }
        $this->defaultHost = $serverHost;
        $this->defaultPort = $serverPort;

        if ($timeOut == false) {
            $timeOut = $this->defaultTimeout;
        }
        $this->connection = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        try {
            if (socket_connect($this->connection, $serverHost, $serverPort) == false) {
                $errorString = socket_strerror(socket_last_error($this->connection));
                $this->_throwError("Connecting to {$serverHost}:{$serverPort} failed.<br>Reason: {$errorString}");
            } else {
                $this->_throwMsg("Socket connected!");
            }
            $this->connectionState = true;
        } catch (Exception $ex) {
            $this->_throwMsg("Socket connected!");
        }        
    }

    /**
     * Disconnects from the server
     * 
     * @return True on succes, false if the connection was already closed
     */
    public function disconnect() {
        if ($this->validateConnection()) {
            socket_close($this->connection);
            $this->connectionState = FALSE;
            $this->_throwMsg("Socket disconnected!");
            return true;
        }
        return false;
    }

    /**
     * Sends a command to the server
     * 
     * @return string Server response
     */
    public function sendRequest($command) {
        if ($this->validateConnection()) {
            $result = socket_write($this->connection, $command, strlen($command));
            return $result;
        }
        $this->_throwError("Sending command \"{$command}\" failed.<br>Reason: Not connected");
    }

    public function isConn() {
        return $this->connectionState;
    }

    public function getUnreadBytes() {

        $info = socket_get_status($this->connection);
        return $info['unread_bytes'];
    }

    public function getConnName(&$addr, &$port) {
        if ($this->validateConnection()) {
            socket_getsockname($this->connection, $addr, $port);
        }
    }

    /**
     * Gets the server response (not multilined)
     * 
     * @return string Server response
     */
    public function getResponse() {
        $read_set = array($this->connection);

        while (($events = socket_select($read_set, $write_set = NULL, $exception_set = NULL, 0)) !== false) {
            if ($events > 0) {
                foreach ($read_set as $so) {
                    if (!is_resource($so)) {
                        $this->_throwError("Receiving response from server failed.<br>Reason: Not connected");
                        return false;
                    } elseif (( $ret = @socket_read($so, 4096, PHP_BINARY_READ) ) == false) {
                        $this->_throwError("Receiving response from server failed.<br>Reason: Not bytes to read");
                        return false;
                    }
                    return $ret;
                }
            }
        }

        return false;
    }

    public function waitForResponse() {
        if ($this->validateConnection()) {
            return socket_read($this->connection, 2048);
        }

        $this->_throwError("Receiving response from server failed.<br>Reason: Not connected");
        return false;
    }

    /**
     * Validates the connection state
     * 
     * @return bool
     */
    private function validateConnection() {
        return (is_resource($this->connection) && ($this->connectionState == true));
    }

    /**
     * Throws an error
     * 
     * @return void
     */
    private function _throwError($errorMessage) {
        echo "Socket error: " . $errorMessage;
    }

    /**
     * Throws an message
     * 
     * @return void
     */
    private function _throwMsg($msg) {
        if ($this->debug) {
            echo "Socket message: " . $msg . "\n\n";
        }
    }

    /**
     * If there still was a connection alive, disconnect it
     */
    public function __destruct() {
        $this->disconnect();
    }

}
