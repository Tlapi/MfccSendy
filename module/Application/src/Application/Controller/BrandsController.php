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
    	$id = (int) $this->params()->fromRoute('id', 0);

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

				$this->redirect()->toRoute('brands');
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

		$this->redirect()->toRoute('brands');
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
