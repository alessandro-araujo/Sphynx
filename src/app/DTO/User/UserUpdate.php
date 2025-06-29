<?php
declare(strict_types=1);
namespace App\DTO\User;

class UserUpdate {
    public function __construct(
        public ?string $email = null,
        public ?string $username = null,
        public ?string $password = null,
        public ?string $address = null,
        public ?int $number = null,
    ) {}

    /**
     * @param UserUpdate $object
     * @return void
     */
    private static function clear_properties_null(UserUpdate $object): void {
        foreach (get_object_vars($object) as $prop => $value) {
            if ($value === null) unset($object->$prop);
        }
    }

    /**
     * @param array{email: string, username: string, password: string} $request
     * @return UserUpdate
     */
    public static function set(array $request): UserUpdate {
        $object = new self();
        foreach (['email', 'username', 'password', 'address', 'number'] as $field) {
            if (array_key_exists($field, $request)) $object->$field = $request[$field];
        }
        self::clear_properties_null($object);
        return $object;
    }
}