<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
         .modal-backdrop.show {
            opacity: 0.9;
            /*background-color: #000;*/
        }

        /*.modal-form {*/
        /*    background-color: transparent;*/
        /*    display:flex;*/
        /*    justify-content: center;*/
        /*    align-items: center*/
        /*}*/
        /*    h1 {*/
        /*        color: #fff;*/
        /*        font-size: 22px;*/
        /*        font-weight:700;*/
        /*        font-family: Arial, Helvetica, sans-serif;*/
        /*        margin: 10px;*/
        /*    }*/

            /*img {*/
            /*    width: 140.95px;*/
            /*    height: 63.47;*/
            /*}*/

            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                width: 100vw;
                height: 100vh;
            }

            form {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            input {
                width: 268px;
                height: 37px;
                outline: none;
                border: none;
                font-family: Arial, Helvetica, sans-serif;
                border-radius: 5px;
            }

            .campo-login{
                font-size: 18px;
                color: #000;
            }

            input[type="submit"] {
                background-color: #1BCF57;
                color: #FFFFFF;
            }




        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/scss/custom.scss', 'resources/js/app.js'])

    </head>
    <body class="bg-black text-white front">
        @include('includes/header')

        <section id="banner" class="m-auto xl:max-w-[1920px] xl:px-[50px]">
            <div class="m-auto xl:max-w-[1280px] px-6 xl:px-[50px]">
                <h1>O Streaming do Consultor.</br>
                    <span>Assista de onde quiser.</span>
                </h1>
            </div>
        </section>

       <!--<div class="modal fade" id="exampleModalToggle" aria-labelledby="exampleModalToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">-->

       <!--     <div class="modal-dialog modal-dialog-centered">-->

       <!--        <div class="modal-content modal-form">-->

       <!--             <img src="assets/img/Frame.png" alt="logo-login">-->
       <!--             <br>-->
       <!--             <h1>Efetue seu login</h1>-->
       <!--             <br>-->
       <!--             <form action="" method="post" id="loginForm">-->
       <!--                 <input class="campo-login" type="text" name="email" id="email">-->
       <!--                 <br>-->
       <!--                 <input type="submit" value="Entrar" >-->
       <!--             </form>-->

       <!--        </div>-->
       <!--   </div>-->
       <!-- </div>-->

        <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color:transparent;">
            </div>
          </div>
        </div>
         <!--<button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Open first modal</button>-->

        <section id="trilha" class="m-auto xl:max-w-[1280px] py-16 px-6 pr-0 xl:px-[50px]">
            <div class="title-sections py-12">{{ $trilha->name }}</div>
            <!-- Small cards slider -->
            <div class="slider">
                @if($trilhas)
                    @foreach($trilhas as $key => $trilha)
                        <a href="javascript:void(0)" data-id="trilha-{{ $key }}-{{ $trilha->id }}" class="slider__item relative hover:text-[#FAC435]" data-video-mp4="{{ asset('uploads/'.$trilha->mp4) }}" data-video-webm="{{ asset('uploads/'.$trilha->webm) }}">
                            <img src="{{ asset('uploads/'.$trilha->image) }}" alt="">
                            <div class="titulo absolute left-1/2 transform translate-x-[-50%] bottom-8">{{ $trilha->title }}</div>
                        </a>
                    @endforeach
                @endif
            </div>
        </section>
        @include('includes/footer')
        <!-- Elemento do lightbox -->
        <div id="lightbox" class="lightbox">
            <div class="lightbox-content">
                <span id="close-btn" class="close-btn">&times;</span>
            </div>
        </div>
    </body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        //  document.addEventListener('DOMContentLoaded', function() {
        //     // Verifique se o usuário já está logado
        //     var isLoggedIn = localStorage.getItem('isLoggedIn');

        //     if (isLoggedIn === 'true') {
        //         // Não exibe o modal se o usuário já estiver logado
        //         return;
        //     }

        //     var myModal = new bootstrap.Modal(document.getElementById('exampleModalToggle'), {
        //         backdrop: 'static',
        //         keyboard: false
        //     });

        //     myModal.show();

        //     var loginForm = document.getElementById('loginForm');

        //     loginForm.onsubmit = function(event) {
        //         event.preventDefault();

        //         var email = document.getElementById("email").value;

        //         if (email.trim() === "") {

        //             alert("Preencha o campo.");

        //         } else if (email === "test@gmail.com") {

        //             myModal.hide(); // Fecha o modal após o login

        //             localStorage.setItem('isLoggedIn', 'true'); // Define o estado de login no localStorage
        //         } else {
        //             alert("Email inválido.");
        //         }
        //     };

        // });


    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const sliderItems = document.querySelectorAll('.slider a');
        const lightbox = document.getElementById('lightbox');
        const closeBtn = document.getElementById('close-btn');
        let videoPlayer; // Declare videoPlayer aqui

        sliderItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const videoMp4 = this.getAttribute('data-video-mp4');
                const videoWebm = this.getAttribute('data-video-webm');

                // Crie o elemento videoPlayer se ele não existir
                if (!videoPlayer) {
                    videoPlayer = document.createElement('video');
                    videoPlayer.setAttribute('controls', 'true');
                    videoPlayer.setAttribute('id', 'video-player');
                    document.querySelector('.lightbox-content').appendChild(videoPlayer);
                }

                // Carrega o vídeo no player
                videoPlayer.innerHTML = `
                    <source src="${videoMp4}" type="video/mp4">
                    <source src="${videoWebm}" type="video/webm">
                `;

                // Exibe o lightbox
                lightbox.style.display = 'block';
                videoPlayer.play();
                body.classList.add('overflow-hidden');
            });
        });

            // Fecha o lightbox ao clicar no botão de fechar
            closeBtn.addEventListener('click', function () {
                lightbox.style.display = 'none';
                body.classList.remove('overflow-hidden');
                // Pausa o vídeo ao fechar o lightbox
                if (videoPlayer) {
                    videoPlayer.pause();
                    videoPlayer.parentNode.removeChild(videoPlayer); // Remove o elemento videoPlayer do DOM
                    videoPlayer = null; // Limpa a referência ao elemento videoPlayer
                }
            });

            // Fecha o lightbox ao clicar fora da área do vídeo
            lightbox.addEventListener('click', function (event) {
                body.classList.remove('overflow-hidden');
                if (event.target === this) {
                    lightbox.style.display = 'none';
                    // Pausa o vídeo ao fechar o lightbox
                    if (videoPlayer) {
                        videoPlayer.pause();
                        videoPlayer.parentNode.removeChild(videoPlayer); // Remove o elemento videoPlayer do DOM
                        videoPlayer = null; // Limpa a referência ao elemento videoPlayer
                    }
                }
            });
        });



    </script>
    <script>
        $(document).ready(function() {

            /* Small card slider */
            $('.slider').slick({
                arrows: false,
                dots: false,
                infinite: true,
                initialSlide: 0,
                slidesToShow: 5,
                variableWidth: true,
                draggable: true,
                responsive: [
                    {
                        breakpoint: 1024, // Por exemplo, ajuste este valor conforme necessário
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 600, // Por exemplo, ajuste este valor conforme necessário
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 480, // Por exemplo, ajuste este valor conforme necessário
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });

            const slider = $(".slider");
            var scrollCount = null;
            var scroll= null;

            slider.on('wheel', (function(e) {
                e.preventDefault();

                clearTimeout(scroll);
                scroll = setTimeout(function(){scrollCount=0;}, 200);
                if(scrollCount) return 0;
                    scrollCount=1;

                if (e.originalEvent.deltaY < 0) {
                    $(this).slick('slickNext');
                } else {
                    $(this).slick('slickPrev');
                }
            }));
        });
    </script>
</html>
