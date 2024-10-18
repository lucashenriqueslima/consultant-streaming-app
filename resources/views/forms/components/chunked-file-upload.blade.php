<div wire:ignore>
    <input type="file" id="{{ $getId() }}" {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}">
</div>

<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<!-- Plugins for chunked uploads -->
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-metadata/dist/filepond-plugin-file-metadata.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-preview/dist/filepond-plugin-file-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

<!-- Load FilePond chunk upload plugin -->
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-metadata/dist/filepond-plugin-file-metadata.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputElement = document.querySelector('input[id="{{ $getId() }}"]');

        // Cria a instância do FilePond
        const pond = FilePond.create(inputElement);

        pond.setOptions({
            server: {
                process: {
                    url: '/api/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    // Definir URLs de manipulação de chunk
                    withCredentials: false,
                },
                revert: {
                    url: '/api/revert',
                    method: 'DELETE',
                },
                patch: '/api/upload/patch',
                load: '/api/upload/load',
                fetch: '/api/upload/fetch',
            },
            chunkUploads: true, // Ativar uploads em pedaços
            chunkSize: 5000000, // Tamanho de cada pedaço (5 MB)
            acceptedFileTypes: ['video/quicktime', 'video/mp4'],
            retry: {
                interval: 500, // Intervalo de retry para uploads falhos
                limit: 5,      // Número de tentativas de retry
            }
        });

        // Atualiza o estado no Livewire quando o upload for concluído
        pond.on('processfile', (error, file) => {
            if (!error) {
                @this.set('{{ $getStatePath() }}', file.serverId);
            }
        });
    });
</script>
