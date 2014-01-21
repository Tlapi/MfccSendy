<?php

namespace Application\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as Result;

class Host implements AdapterInterface
{
    
    /**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	private $password;
	
	private $id;

    /**
     * Sets password and id for authentication
     *
     * @return void
     */
    public function __construct($password, $id, $em)
    {
    	$this->setEntityManager($em);
    	$this->password = $password;
    	$this->id = $id;
    }
    
    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
    	$reports = $this->getEntityManager()->getRepository('Application\Entity\Report');
    	$report = $reports->findOneBy(array(
    		'password' => md5($this->password),
    		'campaign' => $this->id,
    	));
    	
    	if($report){
    		return new Result(Result::SUCCESS, 53);
    	} else {
    		return new Result(Result::FAILURE_CREDENTIAL_INVALID, 53);
    	}
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
    	$this->em = $em;
    }
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }

}