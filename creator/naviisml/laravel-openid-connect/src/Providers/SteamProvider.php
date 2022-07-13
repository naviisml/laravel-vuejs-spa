<?php

namespace Naviisml\Laravel\OpenID\Providers;

use Exception;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;

class SteamProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['user:email'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://steamcommunity.com/openid/', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUser($token)
    {
        $userUrl = "http://steamcommunity.com/openid/id/{$token}";

        $response = $this->getHttpClient()->get($userUrl);

        $user = json_decode($response->getBody(), true);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['steamID64'],
            'nickname' => $user['personaname'],
            'name' => Arr::get($user, 'personaname'),
            'email' => Arr::get($user, 'email'),
            'avatar' => $user['avatar'],
        ]);
    }
}
