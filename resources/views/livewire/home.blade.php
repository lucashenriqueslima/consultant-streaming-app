<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold ml-10 text-xl text-gray-100 leading-tight">
            Home
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
                            data-video-mp4="{{ $this->getStorageUrl($lesson->video) }}"
                            data-video-webm="{{ $this->getStorageUrl($lesson->video) }}"
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
                <span x-data="videoCloseBtn" @click.prevent="closeVideo($event)" class="absolute text-white top-2.5 right-5 cursor-pointer text-2xl z-10">&times;</span>
            </div>
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
@script
<script>

        Alpine.store('videoLightBox', {
            isOpen: false,
            toggle() {
                this.isOpen = !this.isOpen;
            }
        });

        Alpine.data('videoComponent', () => ({
            videoMp4: '',
            videoPlayer: document.createElement('video'),
            listenerVideoPlayer: function() {
                this.videoPlayer.addEventListener('timeupdate', () => {
                    if (this.videoPlayer.currentTime >= this.videoPlayer.duration - 10) {
                        $wire.dispatch('lessonCompleted', {
                            lessonId: lessonId
                        });
                    }
                });
            },

            setVideoSources(event) {


                //remove last event listener from video player
                if (this.videoPlayer) {
                    this.videoPlayer.removeEventListener('timeupdate', this.listenerVideoPlayer);
                }

                this.videoMp4 = event.currentTarget.getAttribute('data-video-mp4');

                this.videoPlayer.setAttribute('controls', 'true');
                this.videoPlayer.setAttribute('autoplay', 'true');

                this.videoPlayer.style.height = '100vh';
                this.videoPlayer.style.width = '100vw';

                const lightbox = document.getElementById('lightbox');

                lightbox.querySelector('.lightbox-content').appendChild(this.videoPlayer);

                const sourceMp4 = document.createElement('source');
                sourceMp4.setAttribute('src', this.videoMp4);
                sourceMp4.setAttribute('type', 'video/mp4');

                this.videoPlayer.appendChild(sourceMp4);

                $store.videoLightBox.toggle();

                lessonId = event.currentTarget.getAttribute('data-lesson-id');

                this.listenerVideoPlayer();

                $nextTick(() => {
                    $wire.dispatch('lessonStarted', {
                        lessonId: lessonId
                    });
                });

            }
        }));

        Alpine.data('videoCloseBtn', () => ({
            closeVideo(event) {
                let videoPlayer = document.querySelector('video')

                videoPlayer.pause();
                videoPlayer.parentNode.removeChild(videoPlayer);
                videoPlayer.remove();

                $store.videoLightBox.toggle();
            }
        }));

</script>
@endscript
</x-app-layout>






