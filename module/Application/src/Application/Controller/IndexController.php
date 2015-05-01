<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function supportAction()
    {
        if ($this->request->isPost()) {
            $mail = new Message();
            if($this->isUser())
            $mail->setFrom('support@recmetals.com', $this->post('name'));
            $mail->addTo('support@recmetals.com');
            $mail->setEncoding('UTF-8');

            $mail->setSubject($this->post('title'));
            $mail->setBody($this->post('description'));

            $transport = new SmtpTransport();
            $options = new SmtpOptions(array(
                'host' => 'mymark.nazwa.pl',
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => 'support@mymark.nazwa.pl',
                    'password' => 'Qwedsazxc123'
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
