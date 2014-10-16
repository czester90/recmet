<?php

namespace Company\Controller;

use Application\Controller\BaseController;
use Company\Entity\City;
use Company\Entity\Province;
use Zend\View\Model\ViewModel;
use Library\MessageStatus;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Parameters;
use Company\Entity\Company;
use User\Entity\User;

class CompanyController extends BaseController {
  
  public function companiesListAction() {
    return new ViewModel(array(
      
    ));
  }
  
  public function priceTableAction() {
    return new ViewModel();
  }
  
  public function registerAction() {
      set_time_limit(7000);
    $request = $this->getRequest();
    $message = new MessageStatus();
      $this->convert();
    
    if($request->isPost()){
        try{
            $company = new Company();
            $company->setData($request->getPost());
            $this->em()->persist($company);
            $this->em()->flush();

            $bcrypt = new Bcrypt;
            $bcrypt->setCost(14);

            $user = new User();
            $user->setUsername($request->getPost('username'));
            $user->setPassword($bcrypt->create($request->getPost('password')));
            $user->setEmail($request->getPost('email'));
            $user->setFirstName($request->getPost('first_name'));
            $user->setLastName($request->getPost('last_name'));
            $user->setCompany_id($company->id);
            $roles = $this->em('User\Entity\Role')->find(1);
            $user->getRolesAdd()->add($roles);
            $this->em()->persist($user);
            $this->em()->flush();

            $request->setPost(new Parameters(array(
                'identity' => $request->getPost('email'),
                'credential' => $request->getPost('password'),
                'remember' => 1
            )));

            $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
            $this->UserAuthentication()->getAuthService()->clearIdentity();
            $adapter = $this->UserAuthentication()->getAuthAdapter();
            $adapter->prepareForAuthentication($this->getRequest());

            $this->UserAuthentication()->getAuthService()->authenticate($adapter);
        }catch(\Exception $e){
            $this->flashMessenger()->addErrorMessage('Wystapił błąd Firma nie może zostać zarejestrowana.');
        }
    }
    
    return new ViewModel(array(
      'status' => $message->getMessage()
    ));
  }

    public function convert() {
    $xml = simplexml_load_file(getcwd().'/public/simc.xml');
    //$terc = simplexml_load_file(getcwd().'/public/terc.xml');
    ini_set("memory_limit","1024M");

    function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
        $arrData = array();

        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }

        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }

    /*$terc2 = objectsIntoArray( $terc );

        for ($i = 0;$i <4128; $i++){
            if($terc2['catalog']['row'][$i]['col'][5] == 'województwo'){
                $woj = new Province();
                $woj->number = $terc2['catalog']['row'][$i]['col'][0];
                $woj->name = $terc2['catalog']['row'][$i]['col'][4];
                $this->em()->persist($woj);
                $this->em()->flush();

            }
        }*/

        $xml2 = objectsIntoArray( $xml );

        for ($i = 0;$i <103178; $i++){
            $city = new City();
            $pro = $this->em('Company\Entity\Province')->findOneBy(array('number' => $xml2['catalog']['row'][$i]['col'][0]));
            $city->province_id = $pro->id;
            $city->name = $xml2['catalog']['row'][$i]['col'][6];
            $this->em()->persist($city);
            $this->em()->flush();
        }
    }
}
