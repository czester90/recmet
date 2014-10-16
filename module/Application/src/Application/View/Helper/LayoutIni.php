<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class LayoutIni extends AbstractHelper implements ServiceManagerAwareInterface {

  protected $sm;
  protected $e;
  protected $event;
  protected $ini;
  protected $module;
  protected $dir = '/../../../../';
  protected $file = 'layout.ini';

  public function __construct($e, $sm) {
    $this->event = $e;
    $this->e = $e->getParam('application');
    $this->sm = $sm;
  }

  public function __invoke($module) {
    $this->module = $module;
    $reader = new \Zend\Config\Reader\Ini();
    $this->ini = $reader->fromFile(__DIR__ . $this->dir . "view/layout/config/" . $this->file);

    $this->checkModuleIni();
    return $this;
  }

  public function getModule($type) {
    $ini = $this->RecursiveParse($this->ParseIniAdvanced($this->ini));

    // var_dump($ini[$this->module]); die;

    if(!isset($ini[$this->module][$type])) {
      return array();
    }

    $modules = $ini[$this->module][$type];

    $output = array();
    foreach ($modules as $module) {
      $output[] = '/layout/modules/' . $type . DIRECTORY_SEPARATOR . $module . '.phtml';
    }

    return $output;
  }

  public function checkModuleIni() {
    $ini = $this->RecursiveParse($this->ParseIniAdvanced($this->ini));

    $param = $this->getParamsLayout();

    if(!isset($ini[$this->module])){
      if(isset($param['layouts'])){
        $this->module = $param['layouts'];
      }
      else{
        $this->module = 'kwik';
      }
    }
  }

  public function getLinkView($type) {
    $ini = $this->RecursiveParse($this->ParseIniAdvanced($this->ini));

    foreach ($ini[$this->module] as $moduleClass => $value) {
      if (strpos($moduleClass, $type) !== false) {
          $this->getViewLink($type, $value);
      }
    }
  }

  public function getParamsLayout() {
    return $this->event->getRouteMatch()->getParams();
  }

  private function getViewLink($type, $module) {
    switch ($type) {
      case 'link_view':
        echo "<script src='" . $module . "'></script>";
        break;
    }
  }

  public function getIniArray() {
    print_r($this->RecursiveParse($this->ParseIniAdvanced($this->ini)));
  }

  private function ParseIniAdvanced($array) {
    $returnArray = array();
    if (is_array($array)) {
      foreach ($array as $key => $value) {
        $e = explode(':', $key);
        if (!empty($e[1])) {
          $x = array();
          foreach ($e as $tk => $tv) {
            $x[$tk] = trim($tv);
          }
          $x = array_reverse($x, true);
          foreach ($x as $k => $v) {
            $c = $x[0];
            if (empty($returnArray[$c])) {
              $returnArray[$c] = array();
            }
            if (isset($returnArray[$x[1]])) {
              $returnArray[$c] = array_merge($returnArray[$c], $returnArray[$x[1]]);
            }
            if ($k === 0) {
              $returnArray[$c] = array_merge($returnArray[$c], $array[$key]);
            }
          }
        } else {
          $returnArray[$key] = $array[$key];
        }
      }
    }
    return $returnArray;
  }

  private function RecursiveParse($array) {
    $returnArray = array();
    if (is_array($array)) {
      foreach ($array as $key => $value) {
        if (is_array($value)) {
          $array[$key] = $this->RecursiveParse($value);
        }
        $x = explode('.', $key);
        if (!empty($x[1])) {
          $x = array_reverse($x, true);
          if (isset($returnArray[$key])) {
            unset($returnArray[$key]);
          }
          if (!isset($returnArray[$x[0]])) {
            $returnArray[$x[0]] = array();
          }
          $first = true;
          foreach ($x as $k => $v) {
            if ($first === true) {
              $b = $array[$key];
              $first = false;
            }
            $b = array($v => $b);
          }
          $returnArray[$x[0]] = array_merge_recursive($returnArray[$x[0]], $b[$x[0]]);
        } else {
          $returnArray[$key] = $array[$key];
        }
      }
    }
    return $returnArray;
  }

  /**
   * Retrieve service manager instance
   *
   * @return ServiceManager
   */
  public function getServiceManager() {
    return $this->sm->getServiceLocator();
  }

  /**
   * Set service manager instance
   *
   * @param ServiceManager $locator
   * @return void
   */
  public function setServiceManager(ServiceManager $serviceManager) {
    $this->sm = $serviceManager;
  }

}

?>