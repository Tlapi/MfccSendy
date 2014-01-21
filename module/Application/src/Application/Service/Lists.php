<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Mandrill Service
 */
class Lists implements ServiceLocatorAwareInterface {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Subscribe user to list
	 * @param \Application\Entity\Subscriber $subscriber
	 * @param \Application\Entity\Lists $list
	 */
	public function addSubscriber(\Application\Entity\Subscriber $subscriber, \Application\Entity\Lists $list)
	{
		// Get subscribers repo
		$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
		$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');

		// Check if e-mail is already in db
		$alreadySubsribed = false;
		$alreadyRegistred = $subscribers->findOneBy(array('email' => $subscriber->email));
		if($alreadyRegistred){
			$subscriber = $alreadyRegistred;
			// TODO Handle conflict ?

			// Check if user is subscribed to this list!!!!
			$alreadySubsribed = $listsToSubscribers->findBy(array('list' => $list, 'subscriber' => $subscriber));
		} else {
			$this->getEntityManager()->persist($subscriber);
		}

		if(!$alreadySubsribed){
			// Subscriber to list link
			$listToSubscriber = new \Application\Entity\ListsToSubscribers();
			$listToSubscriber->subscriber = $subscriber;
			$listToSubscriber->list = $list;
			$listToSubscriber->status = 1;
			$this->getEntityManager()->persist($listToSubscriber);

			$this->getEntityManager()->flush();
			
			return 'subscribed';
		}
		
		return 'already subscribed';
	}

	/**
	 * Remove subscribers from list
	 * @param string $email
	 * @param \Application\Entity\Lists $list
	 */
	public function removeSubscriberByEmail($email, \Application\Entity\Lists $list)
	{
		// Get subscribers repo
		$subscribers = $this->getEntityManager()->getRepository('Application\Entity\Subscriber');
		$listsToSubscribers = $this->getEntityManager()->getRepository('Application\Entity\ListsToSubscribers');

		$subscriber = $subscribers->findOneBy(array('email' => $email));
		if($subscriber){
			$listLink = $listsToSubscribers->findOneBy(array('list' => $list, 'subscriber' => $subscriber));
			$this->getEntityManager()->remove($listLink);
		}

		$this->getEntityManager()->flush();
	}

	/**
	 * Import users from string separated by new lines
	 * @param \Application\Entity\Lists $list
	 * @param string $string
	 */
	public function importSubsribersFromString(\Application\Entity\Lists $list, $string)
	{
		$lines = explode("\n", $string);
		foreach($lines as $line){
			$lineParts = explode(',', $line);
			$subscriber = new \Application\Entity\Subscriber();
			if($lineParts[0]){
				// check if name is present
				if(sizeof($lineParts)==1){
					$subscriber->email = trim($lineParts[0]);
				} else {
					$subscriber->name = trim($lineParts[0]);
					$subscriber->email = trim($lineParts[1]);
				}
			}
			$this->addSubscriber($subscriber, $list);
		}
	}

	/**
	 * Import users from list from string
	 * @param \Application\Entity\Lists $list
	 * @param string $string
	 */
	public function removeSubsribersFromString(\Application\Entity\Lists $list, $string)
	{
		$lines = explode("\n", $string);
		foreach($lines as $line){
			$this->removeSubscriberByEmail(trim($line), $list);
		}
	}
	
	private function subscriberCountQuery($list,$status)
	{	$actives = $this->getEntityManager()->createQuery("SELECT count(s)
				FROM Application\Entity\Subscriber AS s
				LEFT JOIN Application\Entity\ListsToSubscribers AS ls
				WITH s.id = ls.subscriber
				WHERE ls.list = ($list)
				AND ls.status = $status
				")->getResult();
		return $actives[0][1];
	}
	
	public function getActiveUsersCount($list)
	{	return $this->subscriberCountQuery($list,'1');
	}
	
	public function getBouncedUsersCount($list)
	{	return $this->subscriberCountQuery($list,'3')+$this->subscriberCountQuery($list,'4');
	}
	
	public function getUnsubscribedUsersCount($list)
	{	return $this->subscriberCountQuery($list,'2');
	}
	
	public function getComplainedUsersCount($list)
	{	return $this->subscriberCountQuery($list,'5');
	}
	

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function setEntityManager(EntityManager $em)
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