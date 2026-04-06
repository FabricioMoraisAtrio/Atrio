<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = ['school_id', 'key', 'value'];

    public static function getValue(int $schoolId, string $key, string $default = ''): string
    {
        return static::where('school_id', $schoolId)->where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(int $schoolId, string $key, string $value): void
    {
        static::updateOrCreate(
            ['school_id' => $schoolId, 'key' => $key],
            ['value' => $value]
        );
    }

    public static function getAllForSchool(int $schoolId): array
    {
        return static::where('school_id', $schoolId)->pluck('value', 'key')->toArray();
    }
}
