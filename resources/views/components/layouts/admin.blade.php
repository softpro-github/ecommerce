@props(['title' => 'Admin'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} &mdash; CityStyleWears Admin</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-neutral-100 text-neutral-900 antialiased min-h-screen flex">

    <aside class="w-56 bg-black text-white shrink-0 min-h-screen">
        <div class="p-4 flex items-center gap-2">
            <img src="{{ asset('images/logo-horizontal-white.png') }}" alt="CityStyleWears" class="h-8 w-auto">
            <span class="text-xs font-semibold tracking-wide uppercase text-white/50">Admin</span>
        </div>
        <nav class="mt-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-accent' : '' }}">Dashboard</a>
            <a href="{{ route('admin.products') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.products') ? 'bg-white/10 text-accent' : '' }}">Products</a>
            <a href="{{ route('admin.categories') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.categories') ? 'bg-white/10 text-accent' : '' }}">Categories</a>
            <a href="{{ route('admin.orders') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.orders') ? 'bg-white/10 text-accent' : '' }}">Orders</a>
            <a href="{{ route('admin.customers') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.customers') ? 'bg-white/10 text-accent' : '' }}">Customers</a>
            <a href="{{ route('admin.coupons') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.coupons') ? 'bg-white/10 text-accent' : '' }}">Coupons</a>
            <a href="{{ route('admin.slides') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.slides') ? 'bg-white/10 text-accent' : '' }}">Slides</a>
            <a href="{{ route('admin.faqs') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.faqs') ? 'bg-white/10 text-accent' : '' }}">FAQs</a>
            <a href="{{ route('admin.settings') }}" class="block px-6 py-3 hover:bg-white/10 {{ request()->routeIs('admin.settings') ? 'bg-white/10 text-accent' : '' }}">Settings</a>
            <div class="border-t border-white/10 mt-4 pt-4">
                <a href="{{ route('documentation') }}" target="_blank" class="block px-6 py-3 hover:bg-white/10 text-white/60">Documentation</a>
                <a href="{{ route('home') }}" class="block px-6 py-3 hover:bg-white/10 text-white/60">View Store</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-6 py-3 hover:bg-white/10 text-white/60">Logout</button>
                </form>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
