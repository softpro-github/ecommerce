@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — CityStyleWears' : 'CityStyleWears — Your Style Our Priority' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Sail&family=Buenard:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ \App\Models\Setting::get('logo_path') ? asset('storage/'.\App\Models\Setting::get('logo_path')) : asset('images/logo.png') }}">
</head>
<body class="bg-white text-ink antialiased min-h-screen flex flex-col">

    @php
        $mainCategories = \App\Models\Category::whereNull('parent_id')->where('enabled', true)->orderBy('sort_order')->get();
        $cartCount = collect(session('cart', []))->filter(fn ($line) => is_array($line))->sum('qty');
        $promoText = \App\Models\Setting::get('promo_text');
        $siteTagline = \App\Models\Setting::get('tagline', 'Your Style Our Priority');
        $logoWhiteUrl = ($logoWhitePath = \App\Models\Setting::get('logo_white_path'))
            ? asset('storage/'.$logoWhitePath)
            : asset('images/logo-white.png');
    @endphp

    <header class="bg-black text-white" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mobile bar: logo left, cart + hamburger right --}}
            <div class="flex md:hidden items-center justify-between h-14">
                <a href="{{ route('home') }}">
                    <img src="{{ $logoWhiteUrl }}" alt="CityStyleWears" class="h-11 w-auto">
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('cart.index') }}" class="relative hover:text-accent transition-colors" aria-label="Cart">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd" /></svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-accent text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Menu" class="hover:text-accent transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile collapsible menu --}}
            <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden border-t border-white/10 py-4 text-sm">
                <nav class="flex flex-col gap-3 font-accent uppercase tracking-wide">
                    <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Home</a>
                    <a href="{{ route('shop.index') }}" class="hover:text-accent transition-colors">Shop</a>
                    <a href="{{ route('page.faqs') }}" class="hover:text-accent transition-colors">FAQs</a>
                    <a href="{{ route('page.contact') }}" class="hover:text-accent transition-colors">Customer Care</a>
                    @auth
                        <a href="{{ route('account.orders') }}" class="hover:text-accent transition-colors">Account</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-accent transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-accent transition-colors">Login</a>
                    @endauth
                </nav>
                <div class="flex items-center gap-4 mt-5 pt-4 border-t border-white/10">
                    @if($whatsapp = \App\Models\Setting::get('whatsapp_url'))
                        <a href="{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="hover:text-accent transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.06L2 22l5.06-1.33A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm5.2 14.2c-.22.62-1.28 1.18-1.77 1.24-.45.06-1.02.08-1.65-.1-.38-.11-.87-.28-1.5-.55-2.64-1.14-4.36-3.8-4.5-3.98-.13-.18-1.08-1.44-1.08-2.75s.68-1.94.92-2.2c.24-.26.52-.32.7-.32h.5c.16 0 .38-.03.58.44.22.53.75 1.83.82 1.96.07.13.11.28.02.46-.09.18-.13.28-.26.43-.13.15-.28.34-.4.46-.13.13-.27.27-.12.53.16.26.7 1.16 1.51 1.88 1.04.93 1.91 1.22 2.17 1.36.26.13.41.11.56-.07.16-.18.66-.77.84-1.03.18-.26.35-.22.59-.13.24.09 1.53.72 1.79.85.26.13.44.19.5.3.06.11.06.62-.16 1.24z"/></svg>
                        </a>
                    @endif
                    @if($instagram = \App\Models\Setting::get('instagram_url'))
                        <a href="{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-accent transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        </a>
                    @endif
                    @if($tiktok = \App\Models\Setting::get('tiktok_url'))
                        <a href="{{ $tiktok }}" target="_blank" rel="noopener" aria-label="TikTok" class="hover:text-accent transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 2c.3 2.1 1.7 3.8 3.8 4.2v3.1c-1.4 0-2.7-.4-3.8-1.2v6.4c0 3.5-2.8 6.3-6.3 6.3S4 17.9 4 14.4s2.8-6.3 6.3-6.3c.4 0 .8 0 1.1.1v3.3c-.3-.1-.7-.2-1.1-.2-1.7 0-3.1 1.4-3.1 3.1s1.4 3.1 3.1 3.1 3.2-1.4 3.2-3.1V2h3z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Desktop header --}}
            <div class="hidden md:flex items-center justify-between h-12 border-b border-white/10 text-[11px]">
                <nav class="flex items-center gap-6 font-accent uppercase tracking-wide">
                    <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Home</a>
                    <a href="{{ route('shop.index') }}" class="hover:text-accent transition-colors">Shop</a>
                    <a href="{{ route('page.faqs') }}" class="hover:text-accent transition-colors">FAQs</a>
                    <a href="{{ route('page.contact') }}" class="hover:text-accent transition-colors">Customer Care</a>
                </nav>

                <div class="flex items-center gap-5 font-accent uppercase tracking-wide">
                    @auth
                        <a href="{{ route('account.orders') }}" class="hover:text-accent transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            <span class="hidden sm:inline">Account</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-accent transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-accent transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            <span class="hidden sm:inline">Login</span>
                        </a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="hover:text-accent transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd" /></svg>
                        <span>Cart ({{ $cartCount }})</span>
                    </a>

                    <div class="flex items-center gap-3 border-l border-white/10 pl-4">
                        @if($whatsapp = \App\Models\Setting::get('whatsapp_url'))
                            <a href="{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="hover:text-accent transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.38 5.06L2 22l5.06-1.33A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm5.2 14.2c-.22.62-1.28 1.18-1.77 1.24-.45.06-1.02.08-1.65-.1-.38-.11-.87-.28-1.5-.55-2.64-1.14-4.36-3.8-4.5-3.98-.13-.18-1.08-1.44-1.08-2.75s.68-1.94.92-2.2c.24-.26.52-.32.7-.32h.5c.16 0 .38-.03.58.44.22.53.75 1.83.82 1.96.07.13.11.28.02.46-.09.18-.13.28-.26.43-.13.15-.28.34-.4.46-.13.13-.27.27-.12.53.16.26.7 1.16 1.51 1.88 1.04.93 1.91 1.22 2.17 1.36.26.13.41.11.56-.07.16-.18.66-.77.84-1.03.18-.26.35-.22.59-.13.24.09 1.53.72 1.79.85.26.13.44.19.5.3.06.11.06.62-.16 1.24z"/></svg>
                            </a>
                        @endif
                        @if($instagram = \App\Models\Setting::get('instagram_url'))
                            <a href="{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-accent transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        @endif
                        @if($tiktok = \App\Models\Setting::get('tiktok_url'))
                            <a href="{{ $tiktok }}" target="_blank" rel="noopener" aria-label="TikTok" class="hover:text-accent transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 2c.3 2.1 1.7 3.8 3.8 4.2v3.1c-1.4 0-2.7-.4-3.8-1.2v6.4c0 3.5-2.8 6.3-6.3 6.3S4 17.9 4 14.4s2.8-6.3 6.3-6.3c.4 0 .8 0 1.1.1v3.3c-.3-.1-.7-.2-1.1-.2-1.7 0-3.1 1.4-3.1 3.1s1.4 3.1 3.1 3.1 3.2-1.4 3.2-3.1V2h3z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="hidden md:flex flex-col items-center gap-3 py-8">
                <a href="{{ route('home') }}">
                    <img src="{{ $logoWhiteUrl }}" alt="CityStyleWears" class="h-24 w-auto">
                </a>
            </div>
        </div>
    </header>

    <div class="bg-white border-b border-black/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('shop.index') }}" class="flex items-center gap-3 py-2.5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="flex-1 border-0 focus:ring-0 text-xs text-ink placeholder:text-muted p-0">
                <button type="submit" aria-label="Search" class="text-ink hover:text-accent transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    @if($promoText)
        <div class="bg-black text-white overflow-hidden">
            <div class="flex whitespace-nowrap animate-marquee py-2.5">
                @for($i = 0; $i < 2; $i++)
                    <span class="flex items-center shrink-0">
                        @for($j = 0; $j < 6; $j++)
                            <span class="mx-6 text-xs">
                                <span class="text-white/80">{{ $promoText }}</span>
                                <span class="text-accent font-semibold italic ml-2">Shop Now</span>
                            </span>
                        @endfor
                    </span>
                @endfor
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-black text-white mt-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12 text-center">
            <img src="{{ $logoWhiteUrl }}" alt="CityStyleWears" class="h-20 w-auto mx-auto mb-6">
            <p class="text-white/70 text-sm leading-relaxed mb-3">
                <span class="text-white font-semibold">CityStyleWears</span> is premium streetwear forged from the streets.
            </p>
            <p class="text-white/70 text-sm leading-relaxed mb-3">
                Built on ambition, discipline, and self-expression — every piece blends bold branding with everyday
                comfort, made for those who move with purpose. <span class="font-script text-accent text-base">{{ $siteTagline }}</span>
                isn't just a tagline, it's the standard.
            </p>
            <p class="text-white/70 text-sm leading-relaxed">
                This is streetwear without compromise.
            </p>
        </div>

        <nav class="text-center text-sm text-white/70 space-y-2 pb-10">
            <div><a href="{{ route('home') }}" class="hover:text-accent transition-colors">Home</a></div>
            <div><a href="{{ route('shop.index') }}" class="hover:text-accent transition-colors">Shop</a></div>
            <div><a href="{{ route('page.faqs') }}" class="hover:text-accent transition-colors">FAQs</a></div>
            <div><a href="{{ route('page.contact') }}" class="hover:text-accent transition-colors">Customer Care</a></div>
            @auth
                <div><a href="{{ route('account.orders') }}" class="hover:text-accent transition-colors">My Account</a></div>
            @else
                <div><a href="{{ route('login') }}" class="hover:text-accent transition-colors">Login</a></div>
            @endauth
            <div><a href="{{ route('cart.index') }}" class="hover:text-accent transition-colors">Cart</a></div>
        </nav>

        <div class="flex items-center justify-center gap-3 pb-12">
            <a href="{{ \App\Models\Setting::get('whatsapp_url', '#') }}" class="w-9 h-9 rounded-full border border-white/30 flex items-center justify-center text-[10px] font-accent uppercase hover:border-accent hover:text-accent transition-colors">WA</a>
            <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" class="w-9 h-9 rounded-full border border-white/30 flex items-center justify-center text-[10px] font-accent uppercase hover:border-accent hover:text-accent transition-colors">IG</a>
            <a href="{{ \App\Models\Setting::get('tiktok_url', '#') }}" class="w-9 h-9 rounded-full border border-white/30 flex items-center justify-center text-[10px] font-accent uppercase hover:border-accent hover:text-accent transition-colors">TT</a>
        </div>

        <div class="border-t border-white/10 py-6 text-center text-xs text-white/40 tracking-wide">
            &copy; {{ date('Y') }} CityStyleWears. All rights reserved. &middot; Payments secured by Flutterwave
        </div>
    </footer>

    @if($liveChatCode = \App\Models\Setting::get('live_chat_code'))
        {!! $liveChatCode !!}
    @endif

</body>
</html>
