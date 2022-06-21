<script type="application/ld+json"> {
        "@context": "https://schema.org/",
        "@type": "LocalBusiness",
        "@id": "{{ getUrlLink('/', 0) }}",
        "name": "Doithuongthecao.com",
        "image": "{{ url(getSiteSetting('site_logo')) }}",
        "url": "{{ getUrlLink('/', 0) }}",
        "telephone": "+0836014654",
        "priceRange": "Competitive",
        "sameAs": [
            "{{getSiteSetting('site_facebook')}}",
            "{{getSiteSetting('site_twitter')}}",
            "{{getSiteSetting('site_pinterest')}}",
            "{{getSiteSetting('site_youtube')}}",
            "{{getSiteSetting('site_linkedin')}}",
            "{{getSiteSetting('site_tumblr')}}",
            "{{getSiteSetting('site_instagram')}}"
        ],
        "hasMap": "https://goo.gl/maps/NJbWHh3aFtQZNaf78",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "60 Nguyễn Du",
            "addressLocality": "Quận Hai Bà Trưng, TP.Hà Nội",
            "postalCode": "100000",
            "addressCountry": "VN"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 10.7744132,
            "longitude": 106.666769
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday"
            ],
            "opens": "00:00",
            "closes": "23:59"
        }
    }
</script>
<script type="application/ld+json"> {
        "@context": "https://schema.org/",
        "@type": "WebSite",
        "name": "Doithuongthecao.com",
        "alternateName": "{{ strip_quotes(getSiteSetting('site_description')) }}",
        "url": "{{ getUrlLink('/', 0) }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "{search_term_string}"
            },
            "query-input": {
                "@type": "PropertyValueSpecification",
                "valueRequired": "http://schema.org/True",
                "valueName": "search_term_string"
            }
        }
}
</script>
<script type="application/ld+json"> {
        "@context": "https://schema.org/",
        "@type": "Organization",
        "name": "Đổi Thưởng thẻ cào",
        "url": "{{ getUrlLink('/', 0) }}",
        "logo": "{{ url('web/images/logo-gamebaitop1.png') }}",
        "sameAs": [
            "{{getSiteSetting('site_facebook')}}",
            "{{getSiteSetting('site_twitter')}}",
            "{{getSiteSetting('site_pinterest')}}",
            "{{getSiteSetting('site_youtube')}}",
            "{{getSiteSetting('site_linkedin')}}",
            "{{getSiteSetting('site_tumblr')}}",
            "{{getSiteSetting('site_instagram')}}"
        ]
}
</script>
