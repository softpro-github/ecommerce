<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    public string $site_name = '';

    public string $tagline = '';

    public string $contact_phone = '';

    public string $contact_email = '';

    public string $shipping_fee = '';

    public string $usd_exchange_rate = '';

    public string $instagram_url = '';

    public string $whatsapp_url = '';

    public string $tiktok_url = '';

    public string $promo_text = '';

    public string $flutterwave_public_key = '';

    public string $live_chat_code = '';

    public string $mail_mailer = '';

    public string $mail_host = '';

    public string $mail_port = '';

    public string $mail_username = '';

    public string $mail_password = '';

    public bool $mail_password_is_set = false;

    public string $mail_encryption = '';

    public string $mail_from_address = '';

    public string $mail_from_name = '';

    public $logo = null;

    public ?string $logo_path = null;

    public $logo_white = null;

    public ?string $logo_white_path = null;

    public string $brand_philosophy_text = '';

    public string $new_arrivals_heading = '';

    public string $category_heading = '';

    public function mount(): void
    {
        $this->site_name = Setting::get('site_name', 'CityStyleWears');
        $this->tagline = Setting::get('tagline', 'Your Style Our Priority');
        $this->contact_phone = Setting::get('contact_phone', '08075636910');
        $this->contact_email = Setting::get('contact_email', '');
        $this->shipping_fee = Setting::get('shipping_fee', '0');
        $this->usd_exchange_rate = Setting::get('usd_exchange_rate', '1600');
        $this->instagram_url = Setting::get('instagram_url', '');
        $this->whatsapp_url = Setting::get('whatsapp_url', '');
        $this->tiktok_url = Setting::get('tiktok_url', '');
        $this->promo_text = Setting::get('promo_text', 'Free delivery on orders above ₦100,000 — Shop the new drop now');
        $this->flutterwave_public_key = Setting::get('flutterwave_public_key', (string) config('flutterwave.public_key'));
        $this->live_chat_code = Setting::get('live_chat_code', '');

        $this->mail_mailer = Setting::get('mail_mailer', (string) config('mail.default'));
        $this->mail_host = Setting::get('mail_host', (string) config('mail.mailers.smtp.host'));
        $this->mail_port = Setting::get('mail_port', (string) config('mail.mailers.smtp.port'));
        $this->mail_username = Setting::get('mail_username', (string) config('mail.mailers.smtp.username'));
        $this->mail_password_is_set = filled(Setting::get('mail_password'));
        $this->mail_encryption = Setting::get('mail_encryption', (string) config('mail.mailers.smtp.scheme'));
        $this->mail_from_address = Setting::get('mail_from_address', (string) config('mail.from.address'));
        $this->mail_from_name = Setting::get('mail_from_name', (string) config('mail.from.name'));

        $this->logo_path = Setting::get('logo_path');
        $this->logo_white_path = Setting::get('logo_white_path');
        $this->brand_philosophy_text = Setting::get(
            'brand_philosophy_text',
            'CityStyleWears exists for those who wear their story. Every piece is built with premium materials, bold branding, and an unapologetic streetwear DNA.'
        );
        $this->new_arrivals_heading = Setting::get('new_arrivals_heading', 'New Arrivals');
        $this->category_heading = Setting::get('category_heading', 'Shop By Category');
    }

    public function save(): void
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'shipping_fee' => 'required|numeric|min:0',
            'usd_exchange_rate' => 'required|numeric|min:0',
            'instagram_url' => 'nullable|url',
            'whatsapp_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'promo_text' => 'nullable|string|max:255',
            'flutterwave_public_key' => 'nullable|string|max:255',
            'live_chat_code' => 'nullable|string|max:5000',
            'mail_mailer' => 'required|in:smtp,sendmail,log',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|numeric',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:,tls,ssl',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'logo_white' => 'nullable|image|max:2048',
            'brand_philosophy_text' => 'nullable|string|max:2000',
            'new_arrivals_heading' => 'nullable|string|max:255',
            'category_heading' => 'nullable|string|max:255',
        ]);

        foreach ([
            'site_name', 'tagline', 'contact_phone', 'contact_email', 'shipping_fee',
            'usd_exchange_rate', 'instagram_url', 'whatsapp_url', 'tiktok_url', 'promo_text',
            'flutterwave_public_key', 'live_chat_code',
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_encryption',
            'mail_from_address', 'mail_from_name',
            'brand_philosophy_text', 'new_arrivals_heading', 'category_heading',
        ] as $key) {
            Setting::set($key, $this->$key);
        }

        if (filled($this->mail_password)) {
            Setting::set('mail_password', $this->mail_password);
            $this->mail_password = '';
        }

        $this->mail_password_is_set = filled(Setting::get('mail_password'));

        if ($this->logo) {
            $oldPath = $this->logo_path;
            $this->logo_path = $this->logo->store('branding', 'public');
            Setting::set('logo_path', $this->logo_path);
            $this->logo = null;
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        if ($this->logo_white) {
            $oldPath = $this->logo_white_path;
            $this->logo_white_path = $this->logo_white->store('branding', 'public');
            Setting::set('logo_white_path', $this->logo_white_path);
            $this->logo_white = null;
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        session()->flash('status', 'Settings saved.');
    }

    public function sendTestEmail(): void
    {
        $this->save();

        Setting::applyMailConfig();

        $to = auth()->user()->email;

        try {
            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email from CityStyleWears to confirm your mail settings are working.',
                fn ($message) => $message->to($to)->subject('CityStyleWears — Test Email')
            );

            session()->flash('status', "Test email sent to {$to}. Check your inbox (and spam folder).");
        } catch (\Throwable $e) {
            session()->flash('mail_error', 'Failed to send test email: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}
