<?php

namespace Zym\CloudIdx;

class BulkDataClient
{
    const API_ENDPOINT = 'http://axisws.idxre.com:8080/axis2/services/IHFPartnerServices?wsdl'
    
    /**
     * SoapClient
     * 
     * @var \SoapClient
     */
    private $soapClient;
    
    /**
     * Username
     * 
     * @var string
     */
    private $username;
    
    /**
     * Password
     * 
     * @var string
     */
    private $password;
    
    /**
     * Context
     * 
     * @var string
     */
    private $context;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        
        $this->soapClient = new \SoapClient(self::API_ENDPOINT);
    }
    
    public function getSoapClient()
    {
        return $this->soapClient;
    }
    
    public function setSoapClient(\SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getContext()
    {
        if (!$this->context) {
            $this->login();    
        }
        
        return $this->context;
    }
    
    public function setContext($context)
    {
        $this->context = $context;
    }
    
    public function hasContext()
    {
        return !is_null($this->context);
    }
    
    public function login()
    {
        $client = $this->getSoapClient();
        $response = $client->login(array(
            'username' => $this->getUsername(), 
            'password' => $this->getPassword()
        ));
        
        $context = (int)$response->return;
        
        $this->context = $context;
        return $context;
    }
    
    public function logoff($context = null)
    {
        $context = !is_null($context) ?: $this->getContext();
        
        $client = $this->getSoapClient();
        $response = $client->logoff(array('context' => $context));
        
        $result = (bool)$response->return;
        return $result;
    }
    
    public function getBoards($context = null)
    {
        $context = !is_null($context) ?: $this->getContext();
        
        $client = $this->getSoapClient();
        
        $response = $client->getBoards(array('context' => $context));
        
        $boardIds = array();
        if (sizeof($response->return) == 1) {
            $boardIds[] = (int)$response->return;
        } else {
            $boardIds = (array)$response->return;
        }
        
        return $boardIds;
    }

    public function getBoardData($boardId, $context = null)
    {
        $context = !is_null($context) ?: $this->getContext();
        
        $client = $this->getSoapClient();
        
        $response = $client->getBoardData(array(
            'context' => $context,
            'boardID' => $boardId
        ));
        
        return $response->return;
    }
    
    public function getHeaders($context = null)
    {
        $context = !is_null($context) ?: $this->getContext();
        
        $client = $this->getSoapClient();
        
        $response = $client->getBoardData(array(
            'context' => $context
        ));
        
        return $response->return;
    }
    
}