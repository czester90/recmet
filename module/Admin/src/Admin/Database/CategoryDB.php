<?php

namespace Admin\Database;

use Advert\Entity\Category;
use Application\View\Helper\getRepos;

class CategoryDB
{
    const TYPE = 'category';

    public $base;
    private $arr = array(
        'Złom' => array(
            'Żelazne' => array(
                'Żelazo',
                'Żeliwo',
                'Stal',
                'Staliwa',
                'Inne'
            ),
            'Nieżelazne' => array(
                'Aluminium',
                'Brąz',
                'Miedź',
                'Mosiądz',
                'Ołów/cynk',
                'Złom miedzionośny',
                'Metale rzadkie',
                'Metale szlachetne',
                'Elektronika',
                'Odpady tworzyw sztucznych'
            ),
        ),
        'Wyroby hutnicze' => array(
            'Blachy' => array(
                'Blachy jakościowe',
                'Blachy konstrukcyjne',
                'Blachy nierdzewne kwasoodporne',
                'Blachy ocynkowane',
                'Blachy perforowane',
                'Blachy powlekane/lakierowane',
                'Blachy stopowe',
                'Blachy trudnościeralne',
                'Blachy walcowane na gorąco',
                'Blachy walcowane na zimno',
                'Blachy z metali nieżelaznych',
                'Blachy ze stali narzędziowych',
                'Blachy żaroodporne',
                'Inne'
            ),

            'Druty' => array(
                'Druty ciągnione na zimno',
                'Druty kolczaste',
                'Druty nawojowe',
                'Druty nierdzewne kwasoodporne',
                'Druty ocynkowane',
                'Druty ogólnego przeznaczenia',
                'Druty powlekane',
                'Druty spawalnicze',
                'Druty sprężynowe',
                'Druty stalowe',
                'Druty z metali nieżelaznych',
                'Inne'
            ),
            'Konstrukcje' => array(
                'Konstrukcje budowlane i przemysłowe',
                'Konstrukcje dźwignicowe',
                'Konstrukcje mostowe i wiadukty',
                'Konstrukcje zbiorników',
                'Krążki',
                'Odkuwki matrycowe i swobodne',
                'Pierścienie',
                'Tarcze',
                'Walce',
                'Wały',
                'Inne'
            ),
            'Pręty' => array(
                'Pręty ciągnione na zimno',
                'Pręty gładkie',
                'Pręty kute',
                'Pręty walcowane na gorąco',
                'Pręty z metali nieżelaznych',
                'Pręty zbrojeniowe',
                'Pręty żebrowane',
                'Inne'
            ),
            'Profile' => array(
                'Profile ciągnione na gorąco',
                'Profile gięte na zimno',
                'Profile spawane',
                'Profile walcowane na gorąco',
                'Profile wyciskane na gorąco',
                'Profile wzmacniające',
                'Inne'
            ),
            'Rury' => array(
                'Przewody rurowe, profile drążone i tuleje',
                'Rury stalowe bez szwu',
                'Rury stalowe spawane',
                'Rury z metali kolorowych',
                'Rury żeliwne i akcesoria',
                'Węże elastyczne',
                'Złącza do rur',
                'Inne'
            ),
            'Odlewy' => array(
                'Odlewy artystyczne',
                'Odlewy precyzyjne',
                'Odlewy z metali lekkich',
                'Odlewy z metali nieżelaznych i ich stopów',
                'Odlewy żeliwa',
                'Odlewy ze staliwa',
                'Inne',
            ),
            'Pozostałe wyroby stalowe (hutnicze)' => array(
                'Artykuły ścierne',
                'Dennice',
                'Galanteria metalowa',
                'Kołnierze',
                'Kształtowniki',
                'Łańcuchy',
                'Materiały złączne',
                'Nity, kołki, gwoździe, kotwy',
                'Śruby i wyroby śrubowe',
                'Trójniki, czwórniki, łuki, kolana',
                'Walcówka, walce',
                'Zawory, zasuwy, przepustnice',
                'Inne'
            ),

            'Inne'

        ),
        'Rudy/Proszki, koncentraty, kruszywa, zgary, granulaty' => array(
            'Żelazne' => array(
                'Żelazo',
                'Żeliwo',
                'Stal',
                'Staliwa',
                'Inne'
            ),

            'Nieżelazne' => array(
                'Aluminium',
                'Brąz',
                'Miedź',
                'Mosiądz',
                'Ołów/cynk',
                'Złom miedzionośny',
                'Metale rzadkie',
                'Metale szlachetne'
            ),

            'Tworzywa sztuczne' => array(
                'Termoplastyczne',
                'Termoutwardzalne',
                'Chemoutwardzalne',
                'Inne'
            ),
        ),

        'Maszyny i urządenia dla hutnictwa, odlewnictwa i metalurgii' => array(
            'Maszyny i urządzenia dla hutnictwa' => array(
                'Ciągi technologiczne walcarek i przeciągarek',
                'Kompletne linie technologiczne',
                'Maszyny i urządzenia do cięcia wyrobów hutniczych na wymiar',
                'Maszynyi urządzenia do produkcji taśm ciętych z blachy foli i podobnych wyrobów',
                'Młoty do odkuwania wyrobów hutniczych',
                'Piece hutnicze do podgrzewania wlewków i wsadów',
                'Piece i urządzenia do obróbki cieplnej wyrobów hutniczych',
                'Prasy do prasowania, wytłaczania i wykrawania',
                'Wyposażenie i oprzyrządowanie maszyn i urządzeń hutniczych',
                'Zwijarki do drutów',
                'Inne'
            ),
            'Maszyny i urządzenia do formowania odlewów i rdzenie w masach formierskich' => array(
                'Akcesoria formierskie i rdzeniarskie',
                'Maszyny i urządzenia do formowania maszynowego odlewów',
                'Maszyny i urządzenia do wykonywania precyzyjnych odlewów',
                'Inne'
            ),
            'Maszyny i urządzenia do przygotowania mas formierskich' => array(
                'Maszyny i urządzenia do mieszania i regeneracji mas formierskich',
                'Urządzenia zasilające masą formierską stanowiska formierskie i rdzeniarskie',
                'Inne'
            ),
            'Maszyny i urządzenia do metalurgii proszków metali' => array(
                'Prasy do prasowania wyprasek z proszków metali',
                'Urządzenia do spiekania wyprasek z proszków metali',
                'Urządzenia do wytwarzania proszków metali'
            ),
            'Materiały i osprzęt' => array(
                'Bezpieczeństo i higiena pracy, odzież i sprzęt specjalistyczny',
                'Filtry i układy filtracji',
                'Materiały formierskie i pomocnicze do wykonywania form i rdzeni odlewniczych',
                'Materiały izolacyjne',
                'Materiały ogniotrwałe i żaroodporne',
                'Osprzęt dla metalurgii i odlewnictwa',
                'Środki chemiczne',
                'Inne'
            ),
            'Inne'
        ),
        'Maszyny, urządenia do obróbki metalu' => array(

            'Belownice',
            'Centra obróbcze',
            'Czyszczarki',
            'Dłutownice',
            'Dziurkarki',
            'Elektrodrążarki',
            'Foliowarki',
            'Frezarki',
            'Giętarki',
            'Krawędziarki',
            'Lasery',
            'Linie do profilowania',
            'Gilotyny, noże, nożyce gilotynowe',
            'Piły',
            'Plazmy',
            'Pomiarowe',
            'Prasy',
            'Prościarki',
            'Przecinarki',
            'Spawarki',
            'Strugarki',
            'Szlifierki',
            'Tłoczarki',
            'Tokarki',
            'Walcarki',
            'Wiertarki',
            'Wykrawarki',
            'Wyoblarki',
            'Wypalarki',
            'Wytaczarki',
            'Zaginiarki',
            'Znakowarki',
            'Zwijarki',
            'Zgrzewarki',
            'Żłobiarki',
            'Stoły obrotowe',
            'Części do maszyn',
            'Materiały eksploatacyjne',
            'Inne'
        ),

        'Usługi' => array(
            'Galwanizacja',
            'Obróbka plastyczna',
            'Obróbka spawaniem',
            'Odlewnictwo i tłoczenie',
            'Pozostała obróbka metalu',
            'Inne'
        ),

    );

    public function install($type, $_this)
    {
        $this->_base = $_this;
        if ($type == self::TYPE) {
            $this->arrayData($this->arr, null, 1);
        }
    }

    private function arrayData($data, $parent, $pos)
    {
        foreach ($data as $key => $innerArray) {
            if (is_array($innerArray)) {
                $cat = $this->createDB($key, $parent, $pos);
                $this->arrayData($innerArray, $cat, 1);
            } else {
                $this->createDB($innerArray, $parent, $pos, false);
            }
            $pos++;
        }
    }

    private function createDB($name, $categoryParent, $position, $have_child = true)
    {
        $category = new Category();
        $original = null;

        $parent = count($categoryParent) ? $categoryParent->getId() : null;
        if (count($categoryParent)) {
            $original = $categoryParent->getOriginalId() != null ? $categoryParent->getOriginalId() : $categoryParent->getId();
        }
        $category->buildForm($name, $original, $parent, $have_child, $position);

        $this->_base->em()->persist($category);
        $this->_base->em()->flush();

        return $category;
    }

    public function setup()
    {
        return $this->arr;
    }
} 