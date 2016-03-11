<?php

namespace BukkitVotifier;

class Votifier
{
    private $sServerIP = '';
    private $iVotifierPort = '';
    private $sPublicKey = '';
    private $sUsername = '';
    private $sUserAddress = '';
    private $iVoteTimeStamp = '';
    private $sServerlist = '';

    public function __construct($sServerIP, $iVotifierPort, $sPublicKey, $sUsername, $sServerlist, $sUserIP)
    {
        $this->sServerIP = $sServerIP;
        $this->iVotifierPort = $iVotifierPort;
        $this->sPublicKey = $sPublicKey;
        $this->sPublicKey = wordwrap($this->sPublicKey, 65, "\n", true); // Pharse the public key
        $this->sPublicKey = <<<EOF
-----BEGIN PUBLIC KEY-----
$this->sPublicKey
-----END PUBLIC KEY-----
EOF;
        $this->sUsername = preg_replace('/[^A-Za-z0-9_]+/', '', $sUsername); // Replace username to letters, numbers an "_"
        $this->sUserAddress = $sUserIP; // Get user IP
        $this->sServerlist = $sServerlist;
    }

    public function send()
    {
        // Set voting time
        $this->iVoteTimeStamp = time();
        // Details of the vote
        $sVoteString = 'VOTE'."\n".$this->sServerlist."\n".$this->sUsername."\n".$this->sUserAddress."\n".$this->iVoteTimeStamp."\n";
        // Fill blanks to make packet lenght 256
        $leftover = (256 - strlen($sVoteString)) / 2;
        while ($leftover > 0) {
            $sVoteString .= "\x0";
            --$leftover;
        }
        // Encrypt the string
        openssl_public_encrypt($sVoteString, $sCryptedPublicKey, $this->sPublicKey);
        // Try connect to server
        $oSocket = fsockopen($this->sServerIP, $this->iVotifierPort, $errno, $errstr, 3);
        if ($oSocket) {
            fwrite($oSocket, $sCryptedPublicKey); // On success send encrypted packet to server
            return true;
        } else {
            return false;
        }
    }
}
