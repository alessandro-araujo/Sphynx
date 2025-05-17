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
                'invalid' => '{resource} inválidos.',
                'authentication' => 'Erro na autenticação'
            ],
            'success' => [
                'created' => '{resource} criado com sucesso.',
                'successful' => '{resource} bem-sucedido!'
            ],
            'default' => [
                'not_found' => 'Mensagem não encontrada.',
            ],
        ],
        'en-US' => [
            'error' => [
                'not_found' => '{resource} not found.',
                'not_provided' => '{resource} not provided.',
                'invalid' => '{resource} invalid.',
                'authentication' => 'Authentication error'
            ],
            'success' => [
                'created' => '{resource} successfully created.',
                'successful' => '{resource} successfully!.'
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
            'product' => 'Produto',
            'register' => 'Registro',
            'data' => 'Dado',
            'login' => 'Login',
            'email_password' => 'E-mail ou Senha'
        ],
        'en-US' => [
            'user' => 'User',
            'login' => 'Login',
            'register' => 'Register',
            'product' => 'Product',
            'data' => 'Data',
            'email_password' => 'E-mail ou Password'
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