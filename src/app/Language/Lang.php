<?php /** @noinspection SpellCheckingInspection */

namespace App\Language;

class Lang {
    protected string $lang;

    /** @var array<string,array<string,array<string,string>>> $messages */
    protected array $messages = [
        'pt-BR' => [
            'error' => [
                'not_found' => '{resource} não encontrado.',
                'not_provided' => '{resource} não fornecido.',
                'not_provided_s' => '{resource} não fornecidos.',
                'invalid' => '{resource} inválidos.',
                'authentication' => 'Erro na autenticação',
                'not_allowed' => '{resource} não permitido',
                'not_allowed_s' => '{resource} não permitidos',
                'type_error' => 'Erro no tipo do(s) {resource}'
            ],
            'success' => [
                'created' => '{resource} criado com sucesso.',
                'updated' => '{resource} foi atualizado com sucesso.',
                'successful' => '{resource} bem-sucedido!'
            ],
            'message' => [
                'not_found' => '{resource} não encontrado.',
            ],
            'default' => [
                'not_found' => 'Mensagem não encontrada.',
            ],
        ],
        'en-US' => [
            'error' => [
                'not_found' => '{resource} not found.',
                'not_provided' => '{resource} not provided.',
                'not_provided_s' => '{resource} not provided(s).',
                'invalid' => '{resource} invalid.',
                'authentication' => 'Authentication error',
                'not_allowed' => '{resource} not allowed',
                'not_allowed_s' => "{resource} not allowed(s)",
                'type_error' => 'Type error in received data {resource}',
            ],
            'success' => [
                'created' => '{resource} successfully created.',
                'updated' => '{resource} has been updated successfully.',
                'successful' => '{resource} successfully!.'
            ],
            'message' => [
                'not_found' => '{resource} not found(s).',
            ],
            'default' => [
                'not_found' => 'Message not found.',
            ],
        ],
    ];

    /** @var array<string, array<string, string>> $resources */
    protected array $resources = [
        'pt-BR' => [
            'user' => 'Usuário',
            'parameters' => 'Parâmetros',
            'product' => 'Produto',
            'register' => 'Registro',
            'data' => 'Dado',
            'login' => 'Login',
            'email_password' => 'E-mail ou Senha',
            'username_password' => 'Usuário ou Senha',
            'email_password_username' => 'E-mail, Senha ou Usuário'
        ],
        'en-US' => [
            'user' => 'User',
            'login' => 'Login',
            'register' => 'Register',
            'parameters' => 'Parameters',
            'product' => 'Product',
            'data' => 'Data',
            'email_password' => 'E-mail or Password',
            'username_password' => 'User or Password',
            'email_password_username' => 'E-mail, Password or Username'
        ],
    ];

    public function __construct(string $lang = 'pt-BR') {
        $this->lang = $lang;
    }

    /**
     * @param string $message_key
     * @return array<string, string>
     */
    public function get(string $message_key): array {
        $parts = explode('.', $message_key);

        if (count($parts) < 2) return [$parts[0] => $this->getDefaultMessage()];

        $resourceKey = $parts[2] ?? null;
        $resourceName = $this->getResourceName($resourceKey);

        $message = $this->findMessage(array_slice($parts, 0, 2));

        return [$parts[0] => str_replace('{resource}', $resourceName, $message)];
    }

    protected function getResourceName(?string $resourceKey): string {
        return $this->resources[$this->lang][$resourceKey] ?? 'Object';
    }

    /**
     * @param array<int, string> $keys
     * @return string
     */
    protected function findMessage(array $keys): string {
        $message = $this->messages[$this->lang] ?? [];

        foreach ($keys as $key) {
            if (!isset($message[$key])) {
                return $this->getDefaultMessage();
            }
            $message = $message[$key];
        }
        /** @var string $message */
        return $message;
    }

    protected function getDefaultMessage(): string {
        return $this->messages[$this->lang]['default']['not_found'];
    }
}

## "User not fount [pt-br]."
# $lang = new Lang('error.not_found.user');
# $message = $lang->getMessage('error.not_found.user');

## "Product successfully created."
# $langEn = new Lang('success.created.product', 'en-US');
# $messageEn = $langEn->getMessage('success.created.product');