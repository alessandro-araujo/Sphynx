<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\User as UserObject;
use App\Models\User as  UserModel;

final readonly class User {
    public function __construct(private UserModel $user_model){}

    /**
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function register(UserObject $user_object): array {
        $hashed_password = password_hash($user_object->password, PASSWORD_DEFAULT);

        $user_with_hash = new UserObject(
            $user_object->email,
            $hashed_password,
            $user_object->username
        );

        return $this->user_model->register($user_with_hash);
    }
}
