<?php

namespace Admin\Database;

use Advert\Entity\Category;
use Application\View\Helper\getRepos;

class CategoryDB
{
    const TYPE = 'category';

    private $base;
    private $_array = array(
        'Metale' => array(
            'Żelazne' => array(
                'żelazo',
                'żeliwo',
                'stal',
                'staliwa',
                'inne',
            ),
            'Nieżelazne' => array(
                'aluminium',
                'brąz',
                'cynk',
                'cyna',
                'magnez',
                'miedź',
                'molibden',
                'mosiądz',
                'nikiel',
                'ołów',
                'tytan',
                'wolfram',
                'inne'
            ),
        ),
        'Wyroby hutnicze' => array(
            'blachy',
            'druty',
            'konstrukcje',
            'pręty',
            'profile',
            'rury',
            'odlewy',
            'kształtowniki',
            'łańcuchy',
            'materiały złączne',
            'nity, kołki, gwoździe, kotwy',
            'śruby, wyroby śrubowe',
            'trójniki, czwórniki, łuki, kolana',
            'walcówka, walce',
            'zawory, zasuwy, przepustnice'
        ),
        'Złom' => array(
            'Metale nieżelazne',
            'Metale żelazne' => array(
                'stal',
                'staliwo',
                'żeliwo',
                'żelazo'
            ),
            'Maszyny'
        ),
        'Rudy' => array(
            'Żelazne' => array(
                'żelazo',
                'żeliwo',
                'stal',
                'staliwa',
                'inne'
            ),
            'Nieżelazne' => array(
                'aluminium',
                'brąz',
                'cynk',
                'cyna',
                'magnez',
                'miedź',
                'molibden',
                'mosiądz',
                'nikiel',
                'ołów',
                'tytan',
                'wolfram',
                'inne'
            ),
        ),

        'Tworzywa sztuczne' => array(
            'Granulaty' => array(
                'Polietyleny' => array(
                    'LDPE do wytłaczania folii',
                    'LDPE do aplikacji wtryskowych',
                    'LLDPE do wytłaczania folii',
                    'LLDPE do formowania rotacyjnego',
                    'MDPE do wytłaczania folii',
                    'HDPE do wytłaczania folii',
                    'HDPE do aplikacji wtryskowych',
                    'HDPE do wytłaczania z rozdmuchem',
                    'HDPE do wytłaczania rur'
                ),
                'Polipropyleny' => array(
                    'PP HOMO (homopolimery)',
                    'PP COPO (kopolimery blokowe)',
                    'PP RANDOM (kopolimery statystyczne)',
                    'PP do wytłaczania rur'
                ),
                'Tworzywa styrenowe' => array(
                    'GPPS (standardowe polistyreny)',
                    'HIPS (wysokoudarowe polistyreny)'
                ),
                'Tworzywa konstrukcyjne',
                'Wypełniacze mineralne' => array(
                    'CACOLIN - wypełniacze na bazie CaCO3',
                    'ALCOLIN - wypełniacze na bazie talku'
                ),
                'Wypełniacze barwiące' => array(
                    'czarne',
                    'białe',
                    'kolorowe'
                ),
                'Środki pomocnicze' => array(
                    'środki czyszczace',
                    'stabilizatory UV',
                    'antystatyki',
                    'antyblokingi i antyblokingi z poslizgiem',
                    'środki antyposlizgowe',
                    'środki poslizgowe i smarne',
                    'antyutleniacze',
                    'uniepalniacze',
                    'nablyszczacze powierzchni'
                ),
                'Środki do form' => array(
                    'silikonowe',
                    'bezsilikonowe',
                    'do konserwacji form',
                    'do mycia form'
                ),
            ),
        ),
    );

    public function install($type, $_this)
    {
        $this->_base = $_this;
        if($type == self::TYPE){
            $this->arrayData($this->_array, null, 1);
        }
    }

    private function arrayData($data, $parent, $pos) {
        foreach ($data as $key => $innerArray) {
            if (is_array($innerArray)){
                $this->createDB($key, $parent, $pos);
                $this->arrayData($innerArray, $key, 1);
            }else{
                $this->createDB($innerArray, $parent, $pos);
            }
            $pos++;
        }
    }

    private function createDB($name, $parent, $position) {
        $_parent = $this->_base->em('Advert\Entity\Category')->findOneBy(array('name' => $parent));
        $id = count($_parent) ? $_parent->getId() : null;
        $category = new Category();
        $category->buildForm($name, $id, $position);
        $this->_base->em()->persist($category);
        $this->_base->em()->flush();
    }

    public function setup()
    {
        return $this->_array;
    }
} 