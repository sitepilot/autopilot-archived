<?php

namespace App\Observers;

use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class VarsObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function creating(Model $item)
    {
        if (empty($item->name)) {
            if ($item->getTable() == 'server_apps') {
                $name = !empty($this->name) ? $this->name : $this->getRandomName($item);
            } else {
                $name = !empty($this->name) ? $this->name : $item->refid;
            }

            $item->name = $name;
        }

        $item->vars = array_merge($item->getDefaultVars(), is_array($item->vars) ? $item->vars : []);
    }

    /**
     * Handle the "updating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function updating(Model $item)
    {
        $originalVars = $item->getOriginal('vars');
        $updatedVars = array_merge($item->getDefaultVars(), is_array($item->vars) ? $item->vars : []);

        // Prevent name update
        if (isset($originalVars['name'])) $updatedVars['name'] = $originalVars['name'];
        if (isset($originalVars['hostname'])) $updatedVars['hostname'] = $originalVars['hostname'];

        $item->vars = $updatedVars;
    }

    /**
     * Returns a random name from space.
     * 
     * @return string $name
     */
    function getRandomName($item, $count = 2)
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
        $unique = true;
        while ($unique) {
            $names = $faker->unique()->randomElements($elements, $count);
            foreach ($names as $randomName) {
                $name .= '-' . $randomName;
            }
            $unique = $item->where('name', $name)->count();
        }

        return Str::slug($name);
    }
}
