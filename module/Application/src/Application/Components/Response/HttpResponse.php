<?php

namespace Application\Components\Response;


class HttpResponse {

    const STATUS_SUCCESS = 202;
    const STATUS_NOT_FOUND = 404;
    const STATUS_NOT_ACCESS = 400;

    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'error';

    public function successResponse($data = null)
    {
        return $this->createSendData($data);
    }

    public function errorResponse($data = null, $status = self::STATUS_NOT_FOUND)
    {
        return $this->createSendData($data, $status);
    }

    private function createSendData($data, $status = self::STATUS_SUCCESS)
    {
        if (empty($data)) {
           $data = array();
        }

        $response = array(
            'status'    => ($status > self::STATUS_SUCCESS) ? self::TYPE_ERROR : self::TYPE_SUCCESS,
            'code'      => $status,
            'data'      => $data,
            'requestId' => $this->generateRequestId()
        );
        return json_encode($response);
    }

    private function generateRequestId()
    {
        return md5(sha1(new \DateTime('now')));
    }
} 