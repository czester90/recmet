<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Bundle extends AbstractHelper implements ServiceManagerAwareInterface
{
    protected $em;
    protected $sm;

    public function __construct($e, $sm)
    {
        $app = $e->getParam('application');
        $this->sm = $sm;
        $em = $this->getEntityManager();
    }

    public function __invoke()
    {
        $bundlePayment = $this->em->getRepository('Company\Entity\BundlePayments')->findOneBy(array('company_id' => $this->getCompanyId()));
        $bundleDetails = $this->em->getRepository('Company\Entity\BundleDetails')->find($bundlePayment->getPakiet());

        return $bundleDetails;
    }

    public function getEntityManager() {

        if (null === $this->em) {
            $this->em = $this->sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function getCompanyId()
    {
        $user = $this->sm->getServiceLocator()->get('user_auth_service');
        return $user->getIdentity()->getCompanyId();
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getServiceManager()
    {
        return $this->sm->getServiceLocator();
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
    }
} 