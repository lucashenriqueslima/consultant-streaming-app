<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold ml-10 text-xl text-gray-100 leading-tight">
            Cursos
        </h2>
    </x-slot>

    <div class="py-12">
        @foreach ($courses as $course)
            <h1 class="flex mb-6 lg:px-6 md:px-10 px-5 lg:mx-40 md:mx-24 mx-5 font-bold text-3xl text-gray-100">
                {{ $course->title }}
            </h1>
            <div x-data="{
                onScroll(event) {
                    event.preventDefault();
                    this.$el.scrollLeft += event.deltaY;
                }
            }"
            @wheel="onScroll($event)"
        class="flex overflow-x-scroll pb-10 hide-scroll-bar">
                <div class="flex flex-nowrap lg:ml-40 md:ml-20 ml-10">
                    @foreach ($course->lessons as $lesson)
                    <div class="inline-block px-2.5">
                        <div
                            x-data="videoComponent"
                            class="relative w-64 h-86 max-w-xs overflow-hidden rounded-lg cursor-pointer bg-white"
                            data-ytb-video-id="{{ $lesson->video }}"
                            data-lesson-id="{{ $lesson->id }}"
                            @click.prevent="setVideoSources($event)"
                        >
                            <img src="{{ $this->getStorageUrl($lesson->image) }}" alt="" class="scale-110 transition-all duration-300 hover:scale-100">
                            <div class="absolute flex items-center top-5 right-3 text-center font-bold text-lg text-white">
                                {{ svg($this->getUserProgressStatusIcon($lesson->userProgress?->where('lesson_id', $lesson->id)->first()?->is_completed), 'h-6 w-6 mr-1') }}
                                <p> {{ $this->getUserProgressStatusLabel($lesson->userProgress?->where('lesson_id', $lesson->id)->first()?->is_completed) }} </p>
                            </div>
                            <div class="absolute  bottom-2.5 left-0 right-0 text-center font-bold text-gray-100 p-2">
                                {{ $lesson->title }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <div x-data x-show="$store.videoLightBox.isOpen" id="lightbox" class="lightbox" wire:ignore>
            <div class="lightbox-content" wire:ignore>
                <span x-data="videoCloseBtn" @click.prevent="closeVideo($event)" class="absolute text-white top-2 right-28 cursor-pointer text-5xl z-10">&times;</span>
                <div class="absolute text-white top-0 right-5 cursor-pointer text-4xl z-5 px-10 py-10"></div>
                <div class="absolute text-white top-0 left-2.5 cursor-pointer text-4xl z-5 px-32 py-10"></div>
                <div class="absolute text-white bottom-0 right-10 cursor-pointer text-4xl z-5 px-10 py-5"></div>
            </divc>
        </div>
    </div>
            <style>
            .hide-scroll-bar {
              -ms-overflow-style: none;
              scrollbar-width: none;
            }
            .hide-scroll-bar::-webkit-scrollbar {
              display: none;
            }
            .lightbox, .lightbox-content {
                position: fixed;
                bottom: 0;
                left: 50%;
                transform: translate(-50%);
                box-shadow: 0 0 20px #0000004d;
                text-align: center;
                width: 100%;
                height: 100%;
                background-color: #000000e5;
            }

            </style>
<script src="https://www.youtube.com/iframe_api"></script>
<script>

document.addEventListener('livewire:init', () => {
    // window.addEventListener("contextmenu", e => e.preventDefault());
});


</script>
@script
<script>



        Alpine.store('videoLightBox', {
            isOpen: false,
            toggle() {
                this.isOpen = !this.isOpen;
            }
        });

        Alpine.data('videoComponent', () => ({
            ytbIframe: document.createElement('iframe'),
            listenerYtbIframe: function() {

                if(player) {
                    player.destroy();
                }

                var player;
                var videoDuration = 0;
                var watched90Percent = false;


                player = new YT.Player('player', {
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });


                // Função chamada quando o player está pronto
                function onPlayerReady(event) {
                    videoDuration = player.getDuration(); // Obtém a duração total do vídeo
                    player.playVideo(); // Inicia o vídeo
                }

                // Função para controlar mudanças no estado do player
                function onPlayerStateChange(event) {
                    console.log(event)
                    if (event.data == YT.PlayerState.PLAYING) {

                        // Iniciar monitoramento do progresso do vídeo a cada 1 segundo
                        var interval = setInterval(function() {
                            var currentTime = player.getCurrentTime(); // Tempo atual do vídeo
                            if (currentTime / videoDuration >= 0.90 && !watched90Percent) {
                                watched90Percent = true;
                                $wire.dispatch('lessonCompleted', {
                                    lessonId: lessonId
                                });
                            }

                            // Parar o intervalo se o vídeo terminar ou o usuário fechar o vídeo
                            if (player.getPlayerState() != YT.PlayerState.PLAYING) {
                                clearInterval(interval);
                            }
                        }, 1000);
                    }
                }
            },

            setVideoSources(event) {

                let ytbVideoId = event.currentTarget.getAttribute('data-ytb-video-id');

                console.log(`https://www.youtube-nocookie.com/embed/${ytbVideoId}?showinfo=0&rel=0&enablejsapi=1`)

                this.ytbIframe.id = 'player';
                this.ytbIframe.src = `https://www.youtube-nocookie.com/embed/${ytbVideoId}?showinfo=0&rel=0&enablejsapi=1`;
                this.ytbIframe.frameBorder = '0';
                this.ytbIframe.start = '1'
                this.ytbIframe.allowFullscreen = false;
                this.ytbIframe.allow = 'autoplay';

                this.ytbIframe.style.height = '100vh';
                this.ytbIframe.style.width = '100vw';

                const lightbox = document.getElementById('lightbox');

                lightbox.querySelector('.lightbox-content').appendChild(this.ytbIframe);

                $store.videoLightBox.toggle();

                lessonId = event.currentTarget.getAttribute('data-lesson-id');

                this.listenerYtbIframe();

                $nextTick(() => {
                    $wire.dispatch('lessonStarted', {
                        lessonId: lessonId
                    });
                });

            }
        }));

        Alpine.data('videoCloseBtn', () => ({
            closeVideo(event) {
                let videoPlayer = document.querySelector('iframe')

                videoPlayer.remove();

                $store.videoLightBox.toggle();
            }
        }));

</script>
@endscript
</x-app-layout>






