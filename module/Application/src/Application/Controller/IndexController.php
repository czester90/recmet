<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Application\Controller\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function supportAction()
    {
        if ($this->request->isPost()) {
            $mail = new Message('UTF-8');
            $mail->setFrom($this->post('email'), $this->post('name'));
            $mail->addTo('support@recmetals.com');

            $mail->setSubject($this->post('title'));

            $mail->setEncoding('UTF-8');
            $mail->setBody($this->post('description'));

            $transport = new SmtpTransport();
            $options = new SmtpOptions(array(
                'host' => 'recmetals.com',
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => 'support@recmetals.com',
                    'password' => 'qwedsazxc'
                ),
                'port' => 25,
            ));
            $transport->setOptions($options);
            $transport->send($mail);
        }

        return new ViewModel();
    }

    public function stockAction()
    {
        return new ViewModel();
    }
}
