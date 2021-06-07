<?php

namespace App\Factory;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Program|Proxy createOne(array $attributes = [])
 * @method static Program[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Program|Proxy find($criteria)
 * @method static Program|Proxy findOrCreate(array $attributes)
 * @method static Program|Proxy first(string $sortedField = 'id')
 * @method static Program|Proxy last(string $sortedField = 'id')
 * @method static Program|Proxy random(array $attributes = [])
 * @method static Program|Proxy randomOrCreate(array $attributes = [])
 * @method static Program[]|Proxy[] all()
 * @method static Program[]|Proxy[] findBy(array $attributes)
 * @method static Program[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Program[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ProgramRepository|RepositoryProxy repository()
 * @method Program|Proxy create($attributes = [])
 */
final class ProgramFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
           'title' => self::faker()->name,
            'summary' => self::faker()->text,
            'poster' => self::faker()->imageUrl($width = 600, $height = 480),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Program $program) {})
        ;
    }

    protected static function getClass(): string
    {
        return Program::class;
    }
}
