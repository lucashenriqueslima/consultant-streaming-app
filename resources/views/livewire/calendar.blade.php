<div>
    <h1>Criar Evento no Google Calendar</h1>

    <form wire:submit.prevent="createEvent">
        <input type="text" wire:model="summary" placeholder="Título do Evento">
        @error('summary') <span>{{ $message }}</span> @enderror

        <input type="text" wire:model="description" placeholder="Descrição">
        @error('description') <span>{{ $message }}</span> @enderror

        <input type="datetime-local" wire:model="start_time" placeholder="Início">
        @error('start_time') <span>{{ $message }}</span> @enderror

        <input type="datetime-local" wire:model="end_time" placeholder="Fim">
        @error('end_time') <span>{{ $message }}</span> @enderror

        {{-- <button type="submit">Criar Evento</button> --}}
        <x-primary-button wire:target="submit">
            Cadastrar
        </x-primary-button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Evento</th>
                <th>Início</th>
                <th>Fim</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->summary }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($event->start->dateTime)) }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($event->end->dateTime)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (session()->has('message'))
        <p>{{ session('message') }}</p>
    @endif
</div>
