<?php

namespace Company\Controller;

use Application\Controller\BaseController;
use Company\Entity\BundlePayments;
use Company\Entity\City;
use Library\Przelewy24;
use Zend\Json\Server\Exception\HttpException;
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

    public function rulesAction() {
        return new ViewModel(array(

        ));
    }

  public function paymentsAction() {
      session_start();

      if(isset($_GET["ok"]) && $_GET["ok"]==2){
          if(file_exists ("parametry.txt")){
              $result = file_get_contents("parametry.txt");

              $X = explode("&", $result);

              foreach($X as $val) {
                  $Y = explode("=", $val);
                  $FIL[trim($Y[0])] = urldecode(trim($Y[1]));
              }

              $P24 = new Przelewy24($_POST["p24_merchant_id"],$_POST["p24_pos_id"],$FIL['p24_crc'],$FIL['env']);

              foreach($_POST as $k=>$v) $P24->addValue($k,$v);

              $P24->addValue('p24_currency',$FIL['p24_currency']);
              $P24->addValue('p24_amount',$FIL['p24_amount']);
              $res = $P24->trnVerify();
              if($res["error"] ==0)
              {
                  $msg = 'Transakcja zosta³a zweryfikowana poprawnie';
              }
              else{
                  $msg = 'B³êdna weryfikacja transakcji';
              }
          }
          else{
              $msg = 'Brak pliku parametry.txt';
          }

          file_put_contents("weryfikacja.txt",date("H:i:s").": ".$msg." \n\n",FILE_APPEND);
          exit;
      }


      if(isset($_POST["submit_test"])) {
          echo '<h2>Wynik:</h2>';
          $test = ($_POST["env"]==1?true:false);
          $salt = $_POST["salt"];
          $P24 = new Przelewy24($_POST["p24_merchant_id"],
              $_POST["p24_pos_id"],
              $salt,
              $test
          );

          $RET = $P24->testConnection();
          echo '<pre>RESPONSE:'.print_r($RET,true).'</pre>';

      }elseif(isset($_POST["submit_send"])) {
          echo '<h2>Wynik:</h2>';
          $test = ($_POST["env"]==1?true:false);
          $salt = $_POST["salt"];

          $P24 = new Przelewy24($_POST["p24_merchant_id"],
              $_POST["p24_pos_id"],
              $salt,
              $test);

          foreach($_POST as $k=>$v) $P24->addValue($k,$v);

          file_put_contents("parametry.txt","p24_crc=".$_POST['salt']."&p24_amount=".$_POST['p24_amount']."&p24_currency=".$_POST['p24_currency']."&env=".$_POST['env']);


          $bool = ($_POST["redirect"]=="on")? true:false;
          $res = $P24->trnRegister($bool);

          echo '<pre>RESPONSE:'.print_r($res,true).'</pre>';

          if($res["error"]=="0") {

              echo '<br/><a href="'.$P24->getHost()."trnRequest/".$res["token"].'">'.$P24->getHost()."trnRequest/".$res["token"].'</a>';


          }

      }


      $protocol = ( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' )? "https://":"http://";
      session_regenerate_id();

      return new ViewModel(array(
          'version' => Przelewy24::P24_VERSION,
          'protocol' => $protocol
      ));
  }

  public function settingsAction()
  {
      $method = $this->getParam('method');
      $action = $this->params('action');

      $view = new ViewModel(array(
          'company' => $this->em('Company\Entity\Company')->find($this->getCompanyId()),
          'action' => $action,
          'method' => $method
      ));

      if($method){
          $view->setTemplate('company/company/settings/' . $method);
      }else{
          $view->setTemplate('company/company/settings/' . $action);
      }

      return $view;
  }

  public function representativesAction() {

  }
  
  public function registerAction() {
    //set_time_limit(7000);
    $request = $this->getRequest();
    $message = new MessageStatus();
      //$this->convert();
    
    if($request->isPost()){
        $request->getPost()->rank = 0;

        try{
            $validate_nip = $this->em('Company\Entity\Company')->findOneBy(array('nip' => $request->getPost('nip')));
            $validate_regon = $this->em('Company\Entity\Company')->findOneBy(array('regon' => $request->getPost('regon')));
            if(count($validate_nip)){
                return new ViewModel(array(
                    'error_message' => 'Firma o podanym NIPie już istnieje w naszym systemie.'
                ));

            }
            if(count($validate_regon)){
                return new ViewModel(array(
                    'error_message' => 'Firma o podanym REGONie już istnieje w naszym systemie.'
                ));
            }

            $bundle = $this->em('Company\Entity\BundleDetails')->find($request->getPost('bundle_details_id'));
            if(!$bundle) {
                return new ViewModel(array(
                    'error_message' => 'Wystąpił błąd z wyborem pakietu prosze spróbować ponownie bądź skonsultować się z Administratorem.'
                ));
            }

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
            $user->setLocale('pl_PL');
            $roles = $this->em('User\Entity\Role')->find(1);
            $user->getRolesAdd()->add($roles);
            $this->em()->persist($user);

            $amount = $bundle->getAmount();

            $bundle_pay = new BundlePayments();
            $bundle_pay->setCompanyId($company->id);
            $bundle_pay->setAmount(-$amount);
            $bundle_pay->setPaid(false);
            $bundle_pay->setPakiet($bundle->getId());
            $this->em()->persist($bundle_pay);
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

            $this->redirect()->toRoute('user/profile');
        }catch(\Exception $e){
            throw new HttpException($e->getMessage());

            return new ViewModel(array(
                'error_message' => 'Przepraszamy wystapił błąd. Firma nie może zostać zarejestrowana. Spróbuj ponownie, bądź skontaktuj się z nami.'
            ));
        }
    }
    
    return new ViewModel();
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
