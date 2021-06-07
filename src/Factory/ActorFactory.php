<?php

namespace App\Factory;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Actor|Proxy createOne(array $attributes = [])
 * @method static Actor[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Actor|Proxy find($criteria)
 * @method static Actor|Proxy findOrCreate(array $attributes)
 * @method static Actor|Proxy first(string $sortedField = 'id')
 * @method static Actor|Proxy last(string $sortedField = 'id')
 * @method static Actor|Proxy random(array $attributes = [])
 * @method static Actor|Proxy randomOrCreate(array $attributes = [])
 * @method static Actor[]|Proxy[] all()
 * @method static Actor[]|Proxy[] findBy(array $attributes)
 * @method static Actor[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Actor[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ActorRepository|RepositoryProxy repository()
 * @method Actor|Proxy create($attributes = [])
 */
final class ActorFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->name
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Actor $actor) {})
        ;
    }

    protected static function getClass(): string
    {
        return Actor::class;
    }
}
