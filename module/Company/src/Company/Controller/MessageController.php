<?php
namespace Company\Controller;


use Application\Controller\BaseController;
use Company\Entity\Company;
use Company\Entity\Message;
use Zend\View\Model\ViewModel;

class MessageController extends BaseController {

    public function indexAction()
    {

        return new ViewModel();
    }

    public function createAction()
    {
        $senderId = $this->getParam('userId');
        $receiverId = $this->getParam('receiverId');

        $senderCompany = $this->em('Company\Entity\Company')->find($senderId);
        $receiverCompany = $this->em('Company\Entity\Company')->find($receiverId);
        if (!$senderCompany) {
            $this->errorResponse('wrong.sender.company.id');
        }

        if (!$receiverCompany) {
            $this->errorResponse('wrong.receiver.company.id');
        }

        if ($this->request->isPost()) {
            $title = $this->post('title');
            $description = $this->post('description');

            if ($this->validator($title)->notEmpty()) {
                $this->errorResponse('title.is.empty');
            }

            if ($this->validator($description)->notEmpty()) {
                $this->errorResponse('description.is.empty');
            }

            $message = new Message();
            $message->setSenderCompany($senderId);
            $message->setReceiverCompany($receiverId);
            $message->setTitle($title);
            $message->setDescription($description);
            $this->em()->persist($message);
            $this->em()->flush();

            $this->successResponse($message);
        }
    }

    public function deleteAction()
    {
        if ($this->request->isPost()) {
            $companyId = $this->post('companyId');
            $company = $this->em('Company\Entity\Company')->find($companyId);
            if (!$company) {
                $this->errorResponse('wrong.sender.company.id');
            }
            $messageId = $this->post('messageId');
            $message = $this->em('Company\Entity\Message')->find($messageId);
            if ($message) {
                $this->em()->remove($message);
                $this->em()->flush();
            }
        }
    }

    public function markAsReadAction()
    {
        if ($this->request->isPost()) {
            $companyId = $this->post('companyId');
            $company = $this->em('Company\Entity\Company')->find($companyId);
            if (!$company) {
                $this->errorResponse('wrong.sender.company.id');
            }
            $messageId = $this->post('messageId');
            $message = $this->em('Company\Entity\Message')->find($messageId);
            if ($message) {
                $message->setSeen(1);
                $this->em()->persist($message);
                $this->em()->flush();
            }
        }
    }

    public function moveToArchiveAction()
    {

    }
} 