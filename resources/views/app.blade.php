<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Quentin Lequeux">
        <meta name="description" content="FishingBuddy - Un compagnon pour la pêche !">
        <meta name="keywords" content="pêche, application, fish, fishing, poisson, carte, pêcheur">

        <!-- SEO -->

        @verbatim
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "name": "FishingBuddy",
                "foundingDate": "2025",
                "description": "FishingBuddy - Un compagnon pour la pêche !",
                "sameAs": [
                    "https://github.com/QuentinLequeux",
                    "https://www.linkedin.com/in/quentin-lequeux-8a11a3192/"
                ],
                "alumni": [
                    {
                        "@type": "Person",
                        "name": "Quentin Lequeux"
                    }
                ]
            }
        </script>
        @endverbatim

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicons -->

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
