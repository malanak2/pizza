<?php
namespace Enums {
    enum Base {
        case Ketchup;
        case Mustard;
        case Mayo;
        case None;
        case Pineapple;
        case Nachos;
        case Bricks;
        case ElectricBlastFurnace;
        public static function fromName(string $name): Base
        {
            foreach (self::cases() as $status) {
                if( $name === $status->name ){
                    return $status;
                }
            }
            throw new \ValueError("$name is not a valid backing value for enum " . self::class );
        }
        public static function tryFromName(string $name): ?Base
        {
            try {
                return self::fromName($name);
            } catch (\ValueError $error) {
                return null;
            }
        }

    }

    enum Topping {
        case Mushrooms;
        case EidamCheese;
        case Ham;
        case Salami;
        case BlueCheese;
        case WarCrimesFromSyria;
        case LiquidHateForMicrosoftTeams;
        case None;
        public static function fromName(string $name): Topping
        {
            foreach (self::cases() as $status) {
                if( $name === $status->name ){
                    return $status;
                }
            }
            throw new \ValueError("$name is not a valid backing value for enum " . self::class );
        }
        public static function tryFromName(string $name): ?Topping
        {
            try {
                return self::fromName($name);
            } catch (\ValueError $error) {
                var_dump($error);
                return null;
            }
        }

    }
}
