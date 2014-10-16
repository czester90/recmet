<?php
/**
 * Created by PhpStorm.
 * User: szwester
 * Date: 16/10/14
 * Time: 18:52
 */

namespace Company\Validator;

class Company {

    private $error = array();

    public function __invoke($value) {
        if(!$this->validateNip($value['nip'])){
            $this->setError($value['nip'], 'Nie poprawny NIP');
        }elseif(!$this->validateRegon($value['regon'])){
            $this->setError($value['nip'], 'Nie poprawny Regon');
        }
    }

    public function getError() {
        return $this->error;
    }

    public function setError($key, $error){
        $this->error[$key]= $error;
    }

    private function validateNip($nip) {
        $nip_bez_kresek = preg_replace("/-/","",$nip);
        $reg = '/^[0-9]{10}$/';
        if(preg_match($reg, $nip_bez_kresek)==false)
            return false;
        else
        {
            $dig = str_split($nip_bez_kresek);
            $kontrola = (6*intval($dig[0]) + 5*intval($dig[1]) + 7*intval($dig[2]) + 2*intval($dig[3]) + 3*intval($dig[4]) + 4*intval($dig[5]) + 5*intval($dig[6]) + 6*intval($dig[7]) + 7*intval($dig[8]))%11;
            if(intval($dig[9]) == $kontrola)
                return true;
            else
                return false;
        }
    }

    private function validateRegon($regon) {
        $reg = '/^[0-9]{9}$/';
        if(preg_match($reg, $regon)==false)
            return false;
        else
        {
            $dig = str_split($regon);
            $kontrola = (8*intval($dig[0]) + 9*intval($dig[1]) + 2*intval($dig[2]) + 3*intval($dig[3]) + 4*intval($dig[4]) + 5*intval($dig[5]) + 6*intval($dig[6]) + 7*intval($dig[7]))%11;
            if($kontrola == 10) $kontrola = 0;
            if(intval($dig[8]) == $kontrola)
                return true;
            else
                return false;
        }
    }
} 