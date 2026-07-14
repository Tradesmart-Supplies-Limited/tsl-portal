<!-- SEO Meta -->
<meta name="description" content="@yield('description', config('app.name', 'Tradesmart Client Portal'))">

<!-- Open Graph / Facebook / WhatsApp / LinkedIn -->
<meta property="og:type" content="website">
<meta property="og:title" content="@yield('title', config('app.name', 'Tradesmart Client Portal'))">
<meta property="og:description" content="@yield('description', 'Tradesmart Client Portal')">
<meta property="og:url" content="{{ url()->current() }}">

<meta property="og:image" content="https://misc.tradesmartzm.com/logo.png">
<meta property="og:image:secure_url" content="https://misc.tradesmartzm.com/logo.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', config('app.name', 'Tradesmart Client Portal'))">
<meta name="twitter:description" content="@yield('description', 'Tradesmart Client Portal')">
<meta name="twitter:image" content="https://misc.tradesmartzm.com/logo.png">