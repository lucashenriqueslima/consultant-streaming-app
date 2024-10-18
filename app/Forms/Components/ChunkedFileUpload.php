<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class ChunkedFileUpload extends Field
{
    protected string $view = 'forms.components.chunked-file-upload';

    public static function make(string $name): static
    {
        return parent::make($name)
            ->label('Vídeo');
    }
}
