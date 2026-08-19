<x-layouts.storefront title="Customer Care">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="page-title text-center mb-12">Customer Care</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="text-xs uppercase tracking-widest text-muted mb-4">Get In Touch</h2>
                <p class="text-muted mb-2">Phone / WhatsApp: <span class="text-accent font-medium">{{ \App\Models\Setting::get('contact_phone', '08075636910') }}</span></p>
                @if($email = \App\Models\Setting::get('contact_email'))
                    <p class="text-muted mb-2">Email: <span class="text-accent font-medium">{{ $email }}</span></p>
                @endif
                <p class="text-muted mt-6">We're here to help with orders, sizing, and anything else you need. Reach out on WhatsApp/phone directly, or send us a message using the form.</p>
            </div>

            <div>
                <h2 class="text-xs uppercase tracking-widest text-muted mb-4">Send Us A Message</h2>

                @if(session('status'))
                    <div class="mb-6 rounded-2xl border border-accent bg-accent/5 text-accent text-sm px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-300 bg-red-50 text-red-600 text-sm px-4 py-3">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('page.contact.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-muted mb-1.5">Message</label>
                        <textarea name="message" rows="5" class="w-full rounded-xl border border-black/15 px-4 py-3 focus:border-accent focus:ring-accent">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full">Send Message</button>
                </form>
            </div>
        </div>
    </div>

</x-layouts.storefront>
