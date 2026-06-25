<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

#[Fillable(['group', 'key', 'value', 'type', 'label', 'description'])]
class Setting extends Model
{
    // ──────────────────────────────────────────────
    // Static Methods
    // ──────────────────────────────────────────────

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return $setting->value;
    }

    public static function set(string $key, mixed $value): void
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create(['key' => $key, 'value' => $value]);
        }
    }

    public static function getGroup(string $group): Collection
    {
        return static::where('group', $group)->get();
    }

    // ──────────────────────────────────────────────
    // Accessors & Mutators
    // ──────────────────────────────────────────────

    public function getValueAttribute(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this->type) {
            'encrypted' => rescue(fn () => Crypt::decryptString($value), $value),
            'boolean'   => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number'    => is_numeric($value) ? (str_contains($value, '.') ? (float) $value : (int) $value) : $value,
            default     => $value,
        };
    }

    public function setValueAttribute(mixed $value): void
    {
        $this->attributes['value'] = $this->type === 'encrypted'
            ? Crypt::encryptString((string) $value)
            : (string) $value;
    }
}
