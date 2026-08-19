<div>
    <h1 class="text-2xl font-bold mb-6">Site Settings</h1>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">{{ session('status') }}</div>
    @endif

    <form wire:submit="save" class="bg-white rounded shadow p-6 space-y-6 max-w-2xl" enctype="multipart/form-data">
        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Site Name</label>
            <input type="text" wire:model="site_name" class="w-full border rounded px-3 py-2">
            @error('site_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Tagline</label>
            <input type="text" wire:model="tagline" class="w-full border rounded px-3 py-2">
            <p class="text-neutral-400 text-xs mt-1">Shown as the script-style tagline on the homepage and footer.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Logo (on dark background)</label>
                <input type="file" wire:model="logo_white" accept="image/*" class="w-full border rounded px-3 py-2">
                @error('logo_white') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                @if($logo_white)
                    <img src="{{ $logo_white->temporaryUrl() }}" class="mt-2 h-12 bg-black p-1 rounded">
                @elseif($logo_white_path)
                    <img src="{{ asset('storage/' . $logo_white_path) }}" class="mt-2 h-12 bg-black p-1 rounded">
                @else
                    <img src="{{ asset('images/logo-white.png') }}" class="mt-2 h-12 bg-black p-1 rounded">
                @endif
                <p class="text-neutral-400 text-xs mt-1">Used in the header, footer, and emails.</p>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Logo (on light background / favicon)</label>
                <input type="file" wire:model="logo" accept="image/*" class="w-full border rounded px-3 py-2">
                @error('logo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                @if($logo)
                    <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-12 rounded border p-1">
                @elseif($logo_path)
                    <img src="{{ asset('storage/' . $logo_path) }}" class="mt-2 h-12 rounded border p-1">
                @else
                    <img src="{{ asset('images/logo.png') }}" class="mt-2 h-12 rounded border p-1">
                @endif
                <p class="text-neutral-400 text-xs mt-1">Used as the browser tab icon.</p>
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Promo Bar Text</label>
            <input type="text" wire:model="promo_text" class="w-full border rounded px-3 py-2">
            <p class="text-neutral-400 text-xs mt-1">Shown in the scrolling bar above the header.</p>
            @error('promo_text') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Contact Phone</label>
            <input type="text" wire:model="contact_phone" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Contact Email</label>
            <input type="email" wire:model="contact_email" class="w-full border rounded px-3 py-2">
            @error('contact_email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Shipping Fee (₦)</label>
                <input type="number" step="0.01" wire:model="shipping_fee" class="w-full border rounded px-3 py-2">
                @error('shipping_fee') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">USD Exchange Rate (₦ per $1)</label>
                <input type="number" step="0.01" wire:model="usd_exchange_rate" class="w-full border rounded px-3 py-2">
                @error('usd_exchange_rate') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Instagram URL</label>
            <input type="text" wire:model="instagram_url" class="w-full border rounded px-3 py-2">
            @error('instagram_url') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">WhatsApp URL</label>
            <input type="text" wire:model="whatsapp_url" class="w-full border rounded px-3 py-2">
            @error('whatsapp_url') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">TikTok URL</label>
            <input type="text" wire:model="tiktok_url" class="w-full border rounded px-3 py-2">
            @error('tiktok_url') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="border-t pt-6">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-neutral-500 mb-4">Homepage Text</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">"New Arrivals" Section Heading</label>
                    <input type="text" wire:model="new_arrivals_heading" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">"Shop By Category" Section Heading</label>
                    <input type="text" wire:model="category_heading" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Brand Philosophy Paragraph</label>
                <textarea wire:model="brand_philosophy_text" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                <p class="text-neutral-400 text-xs mt-1">Shown in the black "brand philosophy" section near the bottom of the homepage.</p>
                @error('brand_philosophy_text') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="border-t pt-6">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-neutral-500 mb-4">Flutterwave</h2>

            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Public Key</label>
            <input type="text" wire:model="flutterwave_public_key" class="w-full border rounded px-3 py-2">
            <p class="text-neutral-400 text-xs mt-1">Safe to edit here — the Public Key isn't secret.</p>
            @error('flutterwave_public_key') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <p class="text-neutral-400 text-xs mt-4">
                The Secret Key, Encryption Key, and Secret Hash can charge/refund money and verify webhooks on your
                behalf, so for security they're kept in the server's <code>.env</code> file only, not editable here.
                Update them by editing <code>.env</code> directly on the server.
            </p>
        </div>

        <div class="border-t pt-6">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-neutral-500 mb-4">Mail</h2>

            @if(session('mail_error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4 text-sm">{{ session('mail_error') }}</div>
            @endif

            <div class="mb-4">
                <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Send Method</label>
                <select wire:model.live="mail_mailer" class="w-full border rounded px-3 py-2">
                    <option value="smtp">SMTP (Gmail, Zoho, SendGrid, etc.)</option>
                    <option value="sendmail">Sendmail (server default)</option>
                    <option value="log">Log only (for testing — no real emails sent)</option>
                </select>
                @error('mail_mailer') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            @if($mail_mailer === 'smtp')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">SMTP Host</label>
                        <input type="text" wire:model="mail_host" placeholder="smtp.gmail.com" class="w-full border rounded px-3 py-2">
                        @error('mail_host') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">SMTP Port</label>
                        <input type="text" wire:model="mail_port" placeholder="587" class="w-full border rounded px-3 py-2">
                        @error('mail_port') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Username</label>
                        <input type="text" wire:model="mail_username" class="w-full border rounded px-3 py-2">
                        @error('mail_username') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Password</label>
                        <input type="password" wire:model="mail_password" placeholder="{{ $mail_password_is_set ? '•••••••• (saved — leave blank to keep)' : 'Not set' }}" class="w-full border rounded px-3 py-2">
                        @error('mail_password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Encryption</label>
                        <select wire:model="mail_encryption" class="w-full border rounded px-3 py-2">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="">None</option>
                        </select>
                        @error('mail_encryption') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">"From" Email Address</label>
                    <input type="email" wire:model="mail_from_address" class="w-full border rounded px-3 py-2">
                    @error('mail_from_address') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">"From" Name</label>
                    <input type="text" wire:model="mail_from_name" class="w-full border rounded px-3 py-2">
                    @error('mail_from_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="button" wire:click="sendTestEmail" class="border border-black px-4 py-2 text-xs uppercase tracking-widest hover:bg-black hover:text-white transition-colors">
                Save &amp; Send Test Email
            </button>
            <p class="text-neutral-400 text-xs mt-2">Sends a test email to your own admin login address ({{ auth()->user()->email }}) using the settings above.</p>
        </div>

        <div class="border-t pt-6">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-neutral-500 mb-4">Live Chat</h2>

            <label class="block text-xs uppercase tracking-widest text-neutral-500 mb-1">Chat Widget Code</label>
            <textarea wire:model="live_chat_code" rows="8" class="w-full border rounded px-3 py-2 font-mono text-xs" placeholder="Paste the full <script> snippet from your live chat provider (e.g. Smartsupp)"></textarea>
            <p class="text-neutral-400 text-xs mt-1">Pasted as-is on every storefront page, just before the closing &lt;/body&gt; tag. Only paste code from a provider you trust.</p>
            @error('live_chat_code') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-black text-white px-6 py-2 text-sm uppercase tracking-widest">Save Settings</button>
    </form>
</div>
