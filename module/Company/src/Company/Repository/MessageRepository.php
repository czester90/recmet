<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 09/03/15
 * Time: 21:32
 */

namespace Company\Repository;


use User\Entity\Message;

class MessageRepository {

    public function create()
    {
        $message = new Message();
    }

} 