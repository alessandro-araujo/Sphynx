<?php
declare(strict_types=1);
namespace App\Services;

use App\Helpers\Token;
use App\DTO\User\{UserAuth as UserAuthDTO, UserCreate as UserCreateDTO, UserRegister as UserRegisterDTO,
    UserUpdate as UserUpdateDTO};
use App\Mappers\User as UserMapper;
use App\Models\User as UserModel;

final readonly class User {
    public function __construct(private UserModel $user_model){}

    /**
     * @param UserRegisterDTO $user_object
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function register(UserRegisterDTO $user_object): array {
        $user_object->password = $this->hashPassword($user_object->password);
        $insert = UserMapper::register($user_object);
        return $this->user_model->register($insert);
    }

    /**
     * @param UserCreateDTO $user_object
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function create(UserCreateDTO $user_object): array {
        $user_object->password = $this->hashPassword($user_object->password);
        $insert = UserMapper::create($user_object);
        return $this->user_model->create($insert);
    }


    /**
     * @param UserUpdateDTO $user_object
     * @param int $id
     * @return array{status: 'success', result: string} | array{status: 'error', message: string}
     */
    public function update(UserUpdateDTO $user_object, int $id): array {
        if (isset($user_object->password)) {
            $user_object->password = $this->hashPassword($user_object->password);
        }
        $update = UserMapper::update($user_object);
        return $this->user_model->update($id, $update);
    }
    /**
     * @param string $password
     * @return string
     */
    private function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * @param UserAuthDTO $user_object
     * @return array<int, array{id: int, email: string, username: string, password: string}>
     */
    public function login(UserAuthDTO $user_object): array {
        $login = UserMapper::login($user_object);
        return $this->user_model->login($login);
    }


    /**
     * @return array{status: string, message?: string}
     */
    public function profile(): array {
        $token = Token::get();
        $parts = explode('.', $token);
        if (count($parts) !== 3) return [];
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        return $this->user_model->show((int)$payload['sub']);
    }
}
