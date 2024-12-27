<?php
namespace App\Http\Controllers;

use App\Services\Google\Calendar\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleCalendarController extends Controller
{
    private $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleCalendarService->getAuthUrl();
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('admin')->with('error', 'Autorização falhou!');
        }

        $token = $this->googleCalendarService->fetchAccessTokenWithAuthCode($request->code);
        Session::put('google_access_token', $token);

        return redirect('/admin/candidates');
    }

    public function index()
    {
        $this->authenticate();

        $events = $this->googleCalendarService->listEvents();

        return view('livewire.calendar', ['events' => $events]);
    }

    public function createEvent(Request $request)
    {
        $this->authenticate();

        $this->googleCalendarService->createEvent(
            $request->input('summary'),
            $request->input('description'),
            $request->input('start_time'),
            $request->input('end_time')
        );

        return redirect()->route('calendar.index')->with('success', 'Evento criado com sucesso!');
    }

    private function authenticate()
    {
        if (!Session::has('google_access_token')) {
            return redirect()->route('google.login');
        }

        $this->googleCalendarService->setAccessToken(Session::get('google_access_token'));

        if ($this->googleCalendarService->isAccessTokenExpired()) {
            $refreshToken = $this->googleCalendarService->getRefreshToken();
            $newToken = $this->googleCalendarService->fetchAccessTokenWithRefreshToken($refreshToken);
            Session::put('google_access_token', $newToken);
        }
    }
}
