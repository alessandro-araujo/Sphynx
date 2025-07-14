<?php
declare(strict_types=1);

namespace Integration\Services;

use App\DTO\User\UserUpdate as UserUpdateDTO;
use App\Models\User as UserModel;
use App\Services\User;
use PHPUnit\Framework\TestCase;

class UserUpdateTest extends TestCase {
    public function testPartialUpdateFlow(): void {
        $user_object = new UserUpdateDTO (
            email: "address@api.com",
            password: 'root',
            address: 'Mayor Torres Highway',
            number: 2591
        );

        $pass = $user_object->password ?? '';

        # What the model should return in the mock
        $expected = [
            'status' => 'success',
            'result' => 'true'
        ];

        $mockModel = $this->createMock(UserModel::class);
        $mockModel->expects($this->once())->method('update')
            ->with(24, $this->callback(
                /** @param array<string, string> $data */
                function (array $data) use ($pass) {
                    /** @phpstan-ignore-next-line  */
                    if (isset($data['password'])) return $this->password_verify_mock($pass, $data['password']);
                    return true;
                }
            ))->willReturn($expected);

        # Service with a mocked model
        $service = new User($mockModel);
        $result = $service->update($user_object, 24);
        $this->assertEquals($expected, $result);
    }
    private function password_verify_mock(string $password, string $hash): bool {
        if ( !password_verify($password, $hash)) {
            return false;
        }
        return true;
    }
}
