<?php

namespace App\Http\Livewire;

use Google\Client;
use Google\Service\Calendar as CalendarGoogle;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Calendar extends Component
{
    public $summary;
    public $description;
    public $start_time;
    public $end_time;

    protected $rules = [
        'summary' => 'required|string',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
    ];

    public function createEvent()
    {
        dd('Testeee');
        $this->validate();

        $client = new Client();
        $client->setClientId(config('google.client.id'));
        $client->setClientSecret(config('google.client.secret'));
        $client->setRedirectUri(config('google.callback_url'));
        $client->setAccessToken(Session::get('google_access_token'));

        if ($client->isAccessTokenExpired()) {
            return redirect()->route('google.login');
        }

        $calendarService = new CalendarGoogle($client);
        $event = new CalendarGoogle\Event([
            'summary' => $this->summary,
            'description' => $this->description,
            'start' => ['dateTime' => $this->start_time, 'timeZone' => 'America/Sao_Paulo'],
            'end' => ['dateTime' => $this->end_time, 'timeZone' => 'America/Sao_Paulo'],
        ]);

        $calendarService->events->insert('primary', $event);

        session()->flash('message', 'Evento criado com sucesso!');
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
