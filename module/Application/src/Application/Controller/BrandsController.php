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

class BrandsController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	protected $mandrill;

	/**
	 * On controller dispatch
	 */
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$this->layout()->setVariable('active', 'brands');
		$this->layout()->setVariable('mandrillInfo', $this->getMandrill()->users->info());
		return parent::onDispatch( $e );
	}

    public function indexAction()
    {
    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

        return new ViewModel(array(
        	'brands' => $brands->findAll()
        ));
    }

    public function showAction()
    {
    	$this->layout()->setVariable('menu', 'sidebar-brands');

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->layout()->setVariable('brand_id', $id);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$brand = $brands->find($id);

    	$campaignStats = $this->getServiceLocator()->get('campaignStats');
    	$listsService = $this->getServiceLocator()->get('listsService');

        return new ViewModel(array(
			'brand' => $brand,
			'campaignStats' => $campaignStats,
        	'listsService' => $listsService
        ));
    }

    public function campaignsAction()
    {
    	$this->layout()->setVariable('menu', 'sidebar-brands');
    	$this->layout()->setVariable('submenu_active', 'campaigns');

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->layout()->setVariable('brand_id', $id);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$brand = $brands->find($id);

    	$campaignStats = $this->getServiceLocator()->get('campaignStats');

        return new ViewModel(array(
			'brand' => $brand,
			'campaignStats' => $campaignStats,
        ));
    }

    public function listsAction()
    {
    	$this->layout()->setVariable('menu', 'sidebar-brands');
    	$this->layout()->setVariable('submenu_active', 'lists');

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->layout()->setVariable('brand_id', $id);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$brand = $brands->find($id);
		
    	$campaignStats = $this->getServiceLocator()->get('campaignStats');
    	$listsService = $this->getServiceLocator()->get('listsService');
    	
        return new ViewModel(array(
			'brand' => $brand,
			'campaignStats' => $campaignStats,
        	'listsService' => $listsService
        ));
    }

    public function reportsAction()
    {
    	$this->layout()->setVariable('menu', 'sidebar-brands');
    	$this->layout()->setVariable('submenu_active', 'reports');

    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->layout()->setVariable('brand_id', $id);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$brand = $brands->find($id);

    	$campaignStats = $this->getServiceLocator()->get('campaignStats');

        return new ViewModel(array(
			'brand' => $brand,
			'campaignStats' => $campaignStats,
        ));
    }

    public function editAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');
    	$brand = $brands->find($id);

    	$form = new \Application\Form\Brand();
    	$form->prepareElements();
    	$form->bind($brand);
    	$form->setInputFilter(new \Application\Form\BrandFilter());

    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$data = $request->getPost();

    		$form->setData($data);
    		if ($form->isValid()) {
    			$this->getEntityManager()->persist($brand);
    			$this->getEntityManager()->flush();

    			$this->flashMessenger()->addMessage('Changes has been saved!');

    			$this->redirect()->toRoute('brands');
    		}
    	}

    	// Save button value
    	$form->get('submit')->setValue('Edit');

        return new ViewModel(array(
        		'form' => $form
        ));
    }

    public function addAction()
    {
    	$form = new \Application\Form\Brand();
		$form->prepareElements();
		$form->setInputFilter(new \Application\Form\BrandFilter());

		$request = $this->getRequest();
		if ($request->isPost()){
			$newBrand = new \Application\Entity\Brand();
			$form->bind($newBrand);
			$data = $request->getPost();
			$form->setData($data);
			
			
			
			if ($form->isValid()) {
				$this->getEntityManager()->persist($newBrand);
				$this->getEntityManager()->flush();

				$this->flashMessenger()->addMessage('New brand has been added!');

				$this->redirect()->toRoute('brands');
			}
			else
			{	var_dump($form->getData());
				$this->flashMessenger()->addMessage($form->getMessages);
				echo('new brand fail');
			}
		}

        return new ViewModel(array(
        	'form' => $form
        ));
    }

    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	// Get brands repo
    	$brands = $this->getEntityManager()->getRepository('Application\Entity\Brand');

    	$brand = $brands->find($id);

    	$this->getEntityManager()->remove($brand);
		$this->getEntityManager()->flush();

		$this->flashMessenger()->addMessage('Brand has been deleted!');

		$this->redirect()->toRoute('brands');
    }

    /**
     * Entity Manager
     */
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

    /**
     * Mandrill service
     */
	public function getMandrill() {
		return $this->mandrill;
	}

	public function setMandrill($mandrill) {
		$this->mandrill = $mandrill;
		return $this;
	}

}
