<?php

namespace App\Livewire\Candidate;

use App\Enums\Association;
use App\Models\Ileva\ConsultantTeamIleva;
use App\Services\CandidateService;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;



class Register extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public function getAssociationFields(): Fieldset
    {
        return
            Fieldset::make('Informações da Associação')
            ->schema([
                Select::make('association')
                    ->label('Associação')
                    ->options(Association::class)
                    ->live()
                    ->required(),
                Select::make('ileva_team_id')
                    ->label('Time')
                    ->searchable()
                    ->disabled(fn(Get $get) => empty($get('association')))
                    ->getSearchResultsUsing(
                        function (string $search, Get $get): array {
                            $databaseConnection = Association::from($get('association'))->getDatabaseConnection();

                            return ConsultantTeamIleva::on($databaseConnection)
                                ->where('equipe', 'like', "%{$search}%")
                                ->where('stats', 1)
                                ->limit(50)
                                ->pluck('equipe', 'id')
                                ->toArray();
                        }
                    )
                    ->getOptionLabelUsing(
                        function (int|string|null $teamId, Get $get): ?string {

                            $databaseConnection = Association::from($get('association'))->getDatabaseConnection();

                            return ConsultantTeamIleva::on($databaseConnection)
                                ->find($teamId)
                                ->equipe;
                        }
                    )
            ]);
    }

    public function getPersonalInformationFields(): Fieldset
    {
        return
            Fieldset::make('Informações Pessoais')
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->placeholder('Nome')
                    ->required(),
                TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99')
                    ->rule('cpf')
                    ->unique()
                    ->maxLength(14)
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->placeholder('E-mail')
                    ->unique()
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->label('Data de Nascimento')
                    ->placeholder('Data de Nascimento')
                    ->required(),
            ]);
    }

    public function getAddressFields(): Fieldset
    {
        return
            Fieldset::make('Endereço')
            ->schema([
                TextInput::make('cep')
                    ->label('CEP')
                    ->columnSpanFull()
                    ->mask('99999-999')
                    ->placeholder('CEP')
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => static::searchAddressByCep($state, $set))
                    ->required(),
                TextInput::make('address')
                    ->label('Rua')
                    ->placeholder('Endereço')
                    ->required(),
                TextInput::make('number')
                    ->label('Número')
                    ->placeholder('Número')
                    ->required(),
                TextInput::make('neighborhood')
                    ->label('Bairro')
                    ->columnSpanFull()
                    ->placeholder('Bairro')
                    ->required(),
                TextInput::make('city')
                    ->label('Cidade')
                    ->placeholder('Cidade')
                    ->required(),
                TextInput::make('state')
                    ->label('Estado')
                    ->placeholder('Estado')
                    ->required(),
            ]);
    }

    public static function searchAddressByCep($cep, callable $set)
    {
        if (!$cep) {
            return;
        }
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) === 8) {
            $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");

            if ($response) {
                $data = json_decode($response, true);

                if (!isset($data['erro'])) {
                    // Preenche os campos com os dados do endereço retornado
                    $set('address', $data['logradouro'] ?? '');
                    $set('neighborhood', $data['bairro'] ?? '');
                    $set('city', $data['localidade'] ?? '');
                    $set('state', $data['uf'] ?? '');
                }
            }
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getAssociationFields(),
                $this->getPersonalInformationFields(),
                $this->getAddressFields(),
            ])
            ->statePath('data');
    }

    public function submit(CandidateService $candidateService)
    {
        $this->data = $this->form->getState();

        $candiate = $candidateService->create($this->data);

        Notification::make()
            ->title('Candidato Cadastrado')
            ->success()
            ->persistent()
            ->body('Clique aqui para acessar sua plataforma.')
            ->actions([
                Action::make('redirect-to-dashboard')
                    ->label('Acessar Plataforma')
                    ->button()
                    ->color('success')
                    ->dispatch('redirect-to-dashboard', [$candiate->id])

            ])
            ->send();
    }

    #[On('redirect-to-dashboard')]
    public function redirectToDashboard(int $candiateId): void
    {
        Auth::guard('candidate')->loginUsingId($candiateId, true);

        Session::regenerate();

        $this->redirectIntended(default: route('candidate.dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.candidate.register');
    }
}
