<?php

namespace App\Services\Google\Calendar;

use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class GoogleCalendarService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('google.client.id'));
        $this->client->setClientSecret(config('google.client.secret'));
        $this->client->setRedirectUri(config('google.callback_url'));
        $this->client->addScope(Calendar::CALENDAR);
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function fetchAccessTokenWithAuthCode($code)
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function getRefreshToken()
    {
        return $this->client->getRefreshToken();
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function isAccessTokenExpired()
    {
        return $this->client->isAccessTokenExpired();
    }

    public function fetchAccessTokenWithRefreshToken($refreshToken)
    {
        return $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
    }

    public function listEvents()
    {
        $this->authenticate();

        $calendarService = new Calendar($this->client);
        $events = $calendarService->events->listEvents('primary');

        return $events->getItems();
    }

    public function createEvent($summary, $description, $startTime, $endTime)
    {
        $this->authenticate();

        $calendarService = new Calendar($this->client);
        $event = new Calendar\Event([
            'summary' => $summary,
            'description' => $description,
            'start' => ['dateTime' => $startTime, 'timeZone' => 'America/Sao_Paulo'],
            'end' => ['dateTime' => $endTime, 'timeZone' => 'America/Sao_Paulo'],
        ]);

        $calendarService->events->insert('primary', $event);
    }

    private function authenticate()
    {
        if (!Session::has('google_access_token')) {
            return Redirect::route('google.login');
        }

        $this->client->setAccessToken(Session::get('google_access_token'));

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            Session::put('google_access_token', $newToken);
        }
    }
}
