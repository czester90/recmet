<?php

namespace Advert\Components;


use Company\Entity\Company;

class AddOffer {

    public $amount;
    public $isOffer = false;
    public $isSent = false;
    public $text = "";
    public $companyName;
    public $companyRank;
    public $date;
    public $error;
    public $isError = false;

    private $db;

    public function __construct()
    {
        $this->setDate();
    }

    public function setOffer($amount, $isOffer = true)
    {
        $this->amount = $amount;
        $this->isOffer = $isOffer;
    }

    public function setDBCompany($company)
    {
        $this->companyName = $company->getName();
        $this->companyRank = Company::rank($company->getRank());
    }

    /**
     * @param boolean $isSent
     */
    public function setIsSent($isSent)
    {
        $this->isSent = $isSent;
    }

    /**
     * @return boolean
     */
    public function getIsSent()
    {
        return $this->isSent;
    }



    public function setError()
    {
        $this->error = 'Niepoprawna wartość';
        $this->isError = true;
    }

    public function isError()
    {
        return $this->isError;
    }

    public function getError()
    {
        return $this->error;
    }


    /**
     * @param mixed $companyRank
     */
    public function setCompanyRank($companyRank)
    {
        $this->companyRank = $companyRank;
    }

    /**
     * @return mixed
     */
    public function getCompanyRank()
    {
        return $this->companyRank;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setDate()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $isOffer
     */
    public function setIsOffer($isOffer)
    {
        $this->isOffer = $isOffer;
    }

    /**
     * @return mixed
     */
    public function getIsOffer()
    {
        return $this->isOffer;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }



} 