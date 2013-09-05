<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CampaignsController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

    public function indexAction()
    {

        return new ViewModel(array(

        ));
    }

    public function addAction()
    {
    	// TODO move to factory
    	$brand_id = (int) $this->params()->fromRoute('brand_id', 0);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');
    	$brand = $brands->find($brand_id);
    	// TODO Set defaults from brand

    	$form = new \Application\Form\Campaign();
    	$form->prepareElements();
    	$form->setInputFilter(new \Application\Form\CampaignFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$newCampaign = new \Application\Entity\Campaign();
    		$form->bind($newCampaign);
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {

    			$newCampaign->brand = $brand;

    			$this->getEntityManager()->persist($newCampaign);
    			$this->getEntityManager()->flush();

    			$this->redirect()->toRoute('campaigns/send-to', array('id' => $newCampaign->id));
    		}
    	}

    	return new ViewModel(array(
    			'form' => $form
    	));
    }

    public function sendToAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	$campaigns = $this->getEntityManager()->getRepository('Application\Entity\Campaign');
    	$campaign = $campaigns->find($id);

    	$form = new \Application\Form\CampaignRecipients();
    	$form->prepareElements($campaign);
    	$form->setInputFilter(new \Application\Form\CampaignRecipientsFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {

    			$campaign->recepient_lists = $data['recipients'];
    			$this->getEntityManager()->persist($campaign);
    			$this->getEntityManager()->flush();

    			$this->redirect()->toRoute('brands/show', array('id' => $campaign->brand->id));
    		}
    	}

    	return new ViewModel(array(
    			'form' => $form
    	));
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
