<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Quentin Lequeux">
        <meta name="description" content="FishingBuddy - Un compagnon pour la pêche !">
        <meta name="keywords" content="pêche, application, fish, fishing, poisson, pêcheur">

        <!-- OpenGraph -->

        <meta property="og:type" content="website">
        <meta property="og:sitename" content="FishingBuddy">
        <meta property="og:locale" content="fr_BE">
        <meta property="og:title" content="FishingBuddy">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:description" content="FishingBuddy - Un compagnon pour la pêche !">

        <!-- SEO -->

        @include('includes.jsonld-app')

        @if(request()->routeIs('profile'))
            @include('includes.jsonld-profile')
        @endif

        @if(request()->routeIs('feed'))
            @include('includes.jsonld-feed')
        @endif

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

        <!-- Adobe Fonts -->

        <link rel="stylesheet" href="https://use.typekit.net/bdz5dbg.css">

        @routes
        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-poppins antialiased">
        @inertia
    </body>
</html>
