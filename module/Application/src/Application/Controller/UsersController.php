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
use Zend\Crypt\Password\Bcrypt;

class UsersController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	private $form;

    public function indexAction()
    {
    	// TODO move to factory
    	$mandrill = $this->getServiceLocator()->get('mandrill');
    	$mandrillInfo = $mandrill->users->info();
    	$this->layout()->setVariable('mandrillInfo', $mandrillInfo);

    	// main menu
    	$this->layout()->setVariable('active', 'dashboard');

    	$users = $this->getEntityManager()->getRepository('SamUser\Entity\User');
    	$roles = $this->getEntityManager()->getRepository('SamUser\Entity\Role');

        return new ViewModel(array(
			'users' => $users->findAll(),
			'roles' => $roles->findAll()
        ));
    }

    /**
     * Add new user
     */
    public function addAction()
    {
    	if(!$this->isAllowed('users', 'add')){
    		die('Not allowed!');
    	}
    	
    	$this->setForm();
    	$this->processForm();

    	$this->form->get('password')->setValue(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10));

    	return new ViewModel(array(
    			'form' => $this->form
    	));
    }

    public function editAction()
    {
    	$this->setForm();
    	$this->processForm();

    	$this->form->get('password')->setLabel('Change password')->setValue('');

    	return new ViewModel(array(
    			'form' => $this->form
    	));
    }

    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	$user = $this->getEntityManager()->getRepository('SamUser\Entity\User')->find($id);

    	$this->getEntityManager()->remove($user);
    	$this->getEntityManager()->flush();

    	$this->flashMessenger()->addMessage('User has been deleted!');

    	$this->redirect()->toRoute('users');
    }

    public function setForm()
    {
    	$this->form = new \Application\Form\User();
    	$this->form->prepareElements();
    	$this->form->setInputFilter(new \Application\Form\UserFilter());
    	$this->form->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods());

    	// Add roles options
    	$roles = $this->getEntityManager()->getRepository('SamUser\Entity\Role');
    	$rolesOptions = array();
    	foreach($roles->findAll() as $role){
    		$rolesOptions[$role->getId()] = $role->getRoleId();
    	}
    	$this->form->get('role')->setValueOptions($rolesOptions);
    }

    public function processForm()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

    	$roles = $this->getEntityManager()->getRepository('SamUser\Entity\Role');

    	if(!$id){ // New user
    		$user = new \SamUser\Entity\User();
    	} else {
    		$user = $this->getEntityManager()->getRepository('SamUser\Entity\User')->find($id);
    	}
    	$this->form->bind($user);

    	$request = $this->getRequest();
		if ($request->isPost()){

			// ignore empty pass field
			if(!$data['password']){
				unset($data['password']);
			}

			$userService = $this->getServiceLocator()->get('zfcuser_user_service');

			$data = $request->getPost();
			$this->form->setData($data);
			if ($this->form->isValid()) {

				// Remove old roles
				$user->clearRoles();
				$user->addRole($roles->find($data['role']));

				// Change  password
				if(isset($data['password'])){
					$bcrypt = new Bcrypt;
					$bcrypt->setCost($this->getServiceLocator()->get('zfcuser_module_options')->getPasswordCost());
					$user->setPassword($bcrypt->create($data['password']));
				}

				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();

				if(!$id){ // New user
					$this->flashMessenger()->addMessage('New user has been added!');
				} else {
					$this->flashMessenger()->addMessage('Changes has been saved!');
				}

				$this->redirect()->toRoute('users');
			}
		}
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
