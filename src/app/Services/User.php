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
     * @return array{status: 'error', message: string}|array{status: 'success', result: mixed}
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
     * @return array<int, array<string, mixed>>
     */
    public function login(UserAuthDTO $user_object): array {
        $login = UserMapper::login($user_object);
        return $this->user_model->login($login);
    }


    /**
     * @return array<int, array<string, mixed>>
     */
    public function profile(): array {
        $token = Token::get();
        $parts = explode('.', (string)$token);

        if (count($parts) !== 3) {
            #TODO Implements Result::Auth or AuthToken
            print("error");
            // $http_code = 500;
            // $response = [
            //    'status' => $http_code,
            //    # 'message' => self::$lang->get("error.auth")['error'],
            //];
            # self::$http->response($response, $http_code);
        }
        /** @var array{sub: int|string} $payload */
        $payload  = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        $user_id = (int)$payload['sub'];
        return $this->user_model->show($user_id);
    }
}
