<!DOCTYPE html>
<html lang="uk" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Технічне обслуговування</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
</head>

<body class="bg-zinc-900 text-white">
    <header class="relative h-screen w-full bg-cover bg-center"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">
        <div class="absolute inset-0 bg-black/70"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-5">
            <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="w-40 lg:w-50 mb-10 drop-shadow-xl"
                alt="logo">
            <h1 class="text-3xl md:text-5xl font-bold uppercase font-[Russo_One] drop-shadow-xl">
                Сайт на <span class="text-orange-500">технічному</span><br>обслуговуванні
            </h1>
            <p class="mt-5 text-lg text-zinc-200 max-w-md text-balance">
                Ми оновлюємо сайт, щоб зробити його кращим. Будь ласка, зайдіть трохи пізніше.
            </p>

            {{-- Countdown --}}
            <div x-data="countdown('{{ $settings->maintenance_until }}')" class="mt-10 flex gap-6 text-center text-2xl md:text-4xl font-[Russo_One]"
                x-init="init()">
                <div>
                    <div class="font-bold text-orange-500" x-text="days"></div>
                    <div class="text-sm text-zinc-300">Днів</div>
                </div>
                <div>
                    <div class="font-bold" x-text="hours"></div>
                    <div class="text-sm text-zinc-300">Годин</div>
                </div>
                <div>
                    <div class="font-bold" x-text="minutes"></div>
                    <div class="text-sm text-zinc-300">Хвилин</div>
                </div>
                <div>
                    <div class="font-bold" x-text="seconds"></div>
                    <div class="text-sm text-zinc-300">Секунд</div>
                </div>
            </div>
        </div>
    </header>

    <script>
        function countdown(endDate) {
            return {
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                interval: null,
                init() {
                    const target = new Date(endDate).getTime();
                    this.updateCountdown(target);
                    this.interval = setInterval(() => this.updateCountdown(target), 1000);
                },
                updateCountdown(target) {
                    const now = new Date().getTime();
                    const distance = target - now;

                    if (distance <= 0) {
                        clearInterval(this.interval);
                        this.days = this.hours = this.minutes = this.seconds = '00';
                        return;
                    }

                    this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            }
        }
    </script>
</body>

</html>
