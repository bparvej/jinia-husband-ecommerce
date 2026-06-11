<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HomeI — Cozy Living' }}</title>
    <meta name="description" content="HomeI brings you handcrafted wooden furniture and home décor. Discover bookshelves, dining sets, bedroom furniture, and more.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://unpkg.com/htmx.org@2.0.4" defer></script>
</head>
<body>
    @yield('content')
    <script src="/js/script.js"></script>
</body>
</html>