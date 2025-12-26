@php
    $user = request()->route('user');
    $url = url()->current();
    $asset = asset('storage/' . $user->avatar);
    echo <<<d
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
                "@type": "Person",
                "alternateName": "$user->name",
                "url": "$url",
                "image": "$asset"
        }
        </script>
        d;
@endphp
