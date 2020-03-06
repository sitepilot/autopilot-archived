<?php

namespace App\Traits;

use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait UniqueName
{
    /**
     * Returns a random name from space.
     * 
     * @return string $name
     */
    function getRandomName($prefix = '', $column = 'name', $count = 2)
    {
        $faker = Factory::create();

        $elements = [
            'Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune',
            'Moon', 'Luna', 'Deimos', 'Phobos', 'Ganymede', 'Callisto', 'Io', 'Europa', 'Titan', 'Rhea', 'Iapetus', 'Dione', 'Tethys', 'Hyperion', 'Ariel', 'Puck', 'Oberon', 'Umbriel', 'Triton', 'Proteus',
            'Milky Way', 'Andromeda', 'Triangulum', 'Whirlpool', 'Blackeye', 'Sunflower', 'Pinwheel', 'Centaurus', 'Messier',
            'Lagoon', 'Nebula', 'Eagle', 'Triffid', 'Dumbell', 'Orion', 'Ring', 'Bodes', 'Owl',
            'Orion', 'Mercury', 'Gemini', 'Apollo', 'Enterprise', 'Columbia', 'Challenger', 'Discovery', 'Atlantis', 'Endeavour',
            'Aarhus', 'Abee', 'Adelie', 'Land', 'Adhi', 'Bogdo', 'Agen', 'Albareto', 'Allegan', 'Allende', 'Ambapur', 'Nagla', 'Andura', 'Angers', 'Angra', 'Ankober', 'Anlong', 'Annaheim', 'Appley', 'Bridge', 'Arbol', 'Solo', 'Archie', 'Arroyo', 'Aguiar', 'Assisi', 'Atoka', 'Avanhandava', 'Bacubirito', 'Beardsley', 'Bellsbank', 'Bench', 'Crater', 'Benton', 'Blithfield', 'Block', 'Island', 'Bovedy', 'Brachina', 'Brahin', 'Brenham', 'Buzzard', 'Coulee', 'Campo', 'Cielo', 'Canyon', 'Diablo', 'Cape', 'York', 'Carancas', 'Chambord', 'Chassigny', 'Chelyabinsk', 'Chergach', 'Chinga', 'Chinguetti', 'Claxton', 'Coahuila', 'Cranbourne', 'Orbigny', 'Dronino', 'Eagle', 'Station', 'Elbogen', 'Ensisheim', 'Esquel', 'Gancedo', 'Gebel', 'Kamil', 'Gibeon', 'Goose', 'Lake', 'Grant', 'Hadley', 'Rille', 'Heat', 'Shield', 'Rock', 'Hoba', 'Homestead', 'Hraschina', 'Huckitta', 'Imilac', 'Itqiy', 'Kaidun', 'Kainsaz', 'Karoonda', 'Kesen', 'Krasnojarsk', 'Aigle', 'Dodon', 'Lake', 'Murray', 'Loreto', 'Los', 'Angeles', 'Mackinac Island', 'Mbozi', 'Middlesbrough', 'Mineo', 'Monte Milone', 'Moss', 'Mundrabilla', 'Muonionalusta', 'Murchison', 'Nakhla', 'Nantan', 'Neuschwanstein', 'Norton', 'County', 'Novato', 'Oileán Ruaidh (Martian)', 'Old', 'Oldenburg', 'Omolon', 'Ornans', 'Osseo', 'Ourique', 'Pallasovka', 'Paragould', 'Park',  'Forest', 'Pavlovka', 'Peace', 'River', 'Peekskill', 'Penouille', 'Polonnaruwa', 'High Possil', 'Pribram', 'Pultusk', 'Qidong', 'Richardton', 'Seymchan', 'Shelter', 'Island', 'Shergotty', 'Sikhote', 'Alin', 'Sołtmany', 'Springwater', 'Robert', 'Stannern', 'Sulagiri', 'Sutter', 'Mill', 'Sylacauga', 'Tagish Lake', 'Tamdakht', 'Tenham', 'Texas Fireball', 'Tissint', 'Tlacotepec', 'Toluca', 'Treysa', 'Twannberg', 'Veliky Ustyug', 'Vermillion', 'Weston', 'Willamette', 'Winona', 'Wold', 'Cottage', 'Yardymly', 'Zagami', 'Zaisho', 'Zaklodzie',
            'Antares', 'Ariane', 'Atlas', 'Diamant', 'Dnepr', 'Delta', 'Electron', 'Energia', 'Europa', 'Falcon', 'Falcon Heavy', 'Juno', 'Long March', 'Mercury', 'Redstone', 'Minotaur', 'Pegasus', 'Proton', 'PSLV', 'Safir', 'Shavit', 'Saturn IV', 'Semiorka', 'Soyouz', 'Titan', 'Vega', 'Veronique', 'Zenit',
            'ants', 'bats', 'bears', 'bees', 'birds', 'buffalo', 'cats', 'chickens', 'cattle', 'dogs', 'dolphins', 'ducks', 'elephants', 'fishes', 'foxes', 'frogs', 'geese', 'goats', 'horses', 'kangaroos', 'lions', 'monkeys', 'owls', 'oxen', 'penguins', 'people', 'pigs', 'rabbits', 'sheep', 'tigers', 'whales', 'wolves', 'zebras', 'banshees', 'crows', 'black cats', 'chimeras', 'ghosts', 'conspirators', 'dragons', 'dwarfs', 'elves', 'enchanters', 'exorcists', 'sons', 'foes', 'giants', 'gnomes', 'goblins', 'gooses', 'griffins', 'lycanthropes', 'nemesis', 'ogres', 'oracles', 'prophets', 'sorcerers', 'spiders', 'spirits', 'vampires', 'warlocks', 'vixens', 'werewolves', 'witches', 'worshipers', 'zombies', 'druids'
        ];

        $name = '';
        while ($this->nameIsUsed($name, $column)) {
            $name = $prefix;
            $names = $faker->unique()->randomElements($elements, $count);
            foreach ($names as $randomName) {
                $name .= '-' . $randomName;
            }
        }

        return Str::slug($name);
    }

    /**
     * Returns a random numeric name.
     * 
     * @return string $name
     */
    public function getRandomNumericName($prefix = '', $column = 'name')
    {
        $faker = Factory::create();

        $name = '';
        while ($this->nameIsUsed($name, $column)) {
            $name = $prefix . $faker->numberBetween(10000, 99999);
        }

        return Str::slug($name);
    }

    public function getNextInGroupName($prefix = '', $column = 'group_id', $offset = 10)
    {
        $name = '';
        while ($this->nameIsUsed($name)) {
            $name = $prefix . ($this->query($column, $this->$column)->count() + $offset);
            $offset--;
        }

        return Str::slug($name);
    }

    /**
     * Checks if a name is already used.
     * 
     * @return boolean
     */
    function nameIsUsed($name, $column = 'name')
    {
        if (!empty($name) && DB::table($this->getTable())->where($column, $name)->count() < 1) {
            return false;
        } else {
            return true;
        }
    }
}
