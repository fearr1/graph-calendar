<?php

namespace Helpers;

use GuzzleHttp\Client;

class AuthHelper
{
    /** @var  string $authorizeUrl */
    private $authorizeUrl;

    /** @var  string $tokenUrl */
    private $tokenUrl;

    /** @var  Client */
    private $guzzleClient;

    public function __construct()
    {
        $this->authorizeUrl = API_URL . '/oauth2/v2.0/authorize';
        $this->tokenUrl     = API_URL . '/oauth2/v2.0/token';
        $this->guzzleClient = new Client();
    }

    public function getTokens(): array
    {
        return json_decode(file_get_contents(TOKENS_FILEPATH), true) ?: [];
    }

    public function saveTokens(array $tokens): void
    {
        file_put_contents(
            TOKENS_FILEPATH,
            json_encode(
                [
                    'access_token'  => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                ]
            )
        );
    }

    public function getTokensFromCode(string $code): array
    {
        $result = json_decode(
            $this->guzzleClient->post(
                $this->tokenUrl,
                [
                    'headers'     => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'client_id'     => CLIENT_ID,
                        'grant_type'    => 'authorization_code',
                        'redirect_uri'  => REDIRECT_URL,
                        'scope'         => GRAPH_SCOPE,
                        'code'          => $code,
                        'client_secret' => CLIENT_SECRET,
                    ],
                ]
            )->getBody()->getContents(),
            true
        );

        return $result;
    }

    public function refreshTokens(): void
    {
        $tokens = $this->getTokens();

        if (empty($tokens['refresh_token'])) {
            die('You should authorize this application first.');
        }

        $result = json_decode(
            $this->guzzleClient->post(
                $this->tokenUrl,
                [
                    'headers'     => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'client_id'     => CLIENT_ID,
                        'grant_type'    => 'refresh_token',
                        'redirect_uri'  => REDIRECT_URL,
                        'scope'         => GRAPH_SCOPE,
                        'client_secret' => CLIENT_SECRET,
                        'refresh_token' => $tokens['refresh_token'],
                    ],
                ]
            )->getBody()->getContents(),
            true
        );

        $this->saveTokens($result);
    }

    public function redirectToLogin(): void
    {
        $option = [
            'client_id'     => CLIENT_ID,
            'response_type' => 'code',
            'redirect_uri'  => REDIRECT_URL,
            'response_mode' => 'query',
            'scope'         => GRAPH_SCOPE,
        ];

        header('Location: ' . $this->authorizeUrl . '?' . http_build_query($option));
        exit;
    }
}