<?php

namespace App;

use App\Client;
use Faker\Factory;
use App\ServerUser;
use App\Traits\HasVars;
use App\Traits\HasState;
use App\Traits\Encryptable;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;

class ServerApp extends Model
{
    use HasVars;
    use HasState;
    use Actionable;
    use Encryptable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'description'
    ];


    /**
     * The attributes that should be encrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'vars',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vars' => 'array',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (ServerApp $app) {
            if (empty($app->name)) {
                $app->name = $app->getRandomName($app);
            }
        });
    }

    /**
     * Returns an array with default app variables.
     *
     * @return array
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->name,
            'domain' => $this->name . '.' . config('autopilot.default_domain'),
            'aliases' => [],
            "ssl" => false
        ];
    }

    /**
     * Returns all default and optional vars.
     *
     * @return array
     */
    public function getAllVars()
    {
        return array_merge($this->getDefaultVars(), [
            'aliases' => [],
            'php' => [
                'version' => '74'
            ],
            'wordpress' => [
                'db_name' => '',
                'admin_user' => 'captain',
                'admin_pass' => Str::random(12),
                'admin_email' => 'website@sitepilot.io',
                'update_core' => true,
                'update_plugins' => true,
                'update_themes' => true,
                'update_exclude' => []
            ]
        ]);
    }

    /**
     * Returns the app user.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(ServerUser::class, 'user_id');
    }

    /**
     * Returns the app host.
     *
     * @return void
     */
    public function host()
    {
        return $this->user->host();
    }

    /**
     * Returns the app databases.
     *
     * @return HasMany
     */
    public function databases()
    {
        return $this->hasMany(ServerDatabase::class, 'app_id');
    }

    /**
     * Returns the client.
     *
     * @return Client
     */
    public function getClientAttribute()
    {
        if ($this->user) {
            return $this->user->client;
        }

        return null;
    }

    /**
     * Returns the domain variable.
     *
     * @return void
     */
    public function getDomainAttribute()
    {
        return $this->getVar('domain');
    }

    /**
     * Returns the WordPress update state.
     *
     * @return boolean
     */
    public function getWordPressStateOkAttribute()
    {
        if ($this->getVar('wordpress.state.has_update', false) == false) {
            return true;
        }

        return false;
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
