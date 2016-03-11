<?php

namespace Votifier\Client;

class Vote
{
    private $server_ip = '';
    private $votifier_port = '';
    private $public_key = '';
    private $username = '';
    private $user_ip = '';
    private $vote_time = '';
    private $server_list = '';

    public function __construct($server_ip, $votifier_port, $public_key, $username, $server_list, $user_ip)
    {
        $this->server_ip = $server_ip;
        $this->votifier_port = $votifier_port;
        $this->public_key = $public_key;
        $this->public_key = wordwrap($this->public_key, 65, "\n", true); // Pharse the public key
        $this->public_key = <<<EOF
-----BEGIN PUBLIC KEY-----
$this->public_key
-----END PUBLIC KEY-----
EOF;
        $this->username = preg_replace('/[^A-Za-z0-9_]+/', '', $username); // Replace username to letters, numbers an "_"
        $this->user_ip = $user_ip; // Get user IP
        $this->server_list = $server_list;
    }

    public function send()
    {
        // Set voting time
        $this->vote_time = time();
        // Details of the vote
        $vote_package = 'VOTE'."\n".$this->server_list."\n".$this->username."\n".$this->user_ip."\n".$this->vote_time."\n";
        // Fill blanks to make packet length 256
        $leftover = (256 - strlen($vote_package)) / 2;
        while ($leftover > 0) {
            $vote_package .= "\x0";
            --$leftover;
        }
        // Encrypt the string
        openssl_public_encrypt($vote_package, $enc_public_key, $this->public_key);
        // Try connect to server
        $server__socket = fsockopen($this->server_ip, $this->votifier_port, $errno, $errstr, 3);
        if ($server__socket) {
            fwrite($server__socket, $enc_public_key); // On success send encrypted packet to server
            return true;
        } else {
            return false;
        }
    }
}
