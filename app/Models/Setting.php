<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function applyMailConfig(): void
    {
        $mailer = static::get('mail_mailer');

        if (! $mailer) {
            return;
        }

        config(['mail.default' => $mailer]);

        if ($mailer === 'smtp') {
            config([
                'mail.mailers.smtp.host' => static::get('mail_host') ?: config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => static::get('mail_port') ?: config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => static::get('mail_username') ?: config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => static::get('mail_password') ?: config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.scheme' => static::get('mail_encryption') ?: null,
            ]);
        }

        config([
            'mail.from.address' => static::get('mail_from_address') ?: config('mail.from.address'),
            'mail.from.name' => static::get('mail_from_name') ?: config('mail.from.name'),
        ]);
    }
}
