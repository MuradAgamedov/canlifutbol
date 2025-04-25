<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

use Illuminate\Support\Facades\File;
class Lang extends Model
{

    use HasFactory, SortableTrait, ImageTrait;
    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];
    protected static function boot()
    {
        parent::boot();

        // Yeni dil yaradılarkən `order` avtomatik artır
        static::creating(function ($language) {
            $language->order = Lang::max('order') + 1;
        });

        // Əsas dil yenilənərkən digər dillərin `is_main_lang` dəyərini sıfırla
        static::saving(function ($language) {
            if ($language->is_main_lang) {
                static::resetIsMain($language->id);
            }
        });

        // Dil yaradıldıqda JSON faylını yarat
        static::created(function ($language) {
            static::createJsonFile($language->code);
        });

        // `lang_code` dəyişdirilərkən JSON faylını adını dəyiş
        static::updating(function ($language) {
            if ($language->isDirty('code')) {
                static::renameJsonFile($language->getOriginal('code'), $language->code);
            }
        });

        // Dil silindikdə JSON faylını sil və yeni əsas dili təyin et
        static::deleting(function ($language) {
            $totalLanguages = Lang::count();

            // Əgər yalnız 1 dil varsa, silməyə icazə vermə
            if ($totalLanguages == 1) {
                return false;
            }

            // Əgər əsas dil silinirsə, başqa bir dili əsas təyin et
            if ($language->is_main_lang) {
                $randomLanguage = Lang::where('id', '!=', $language->id)->first();
                if ($randomLanguage) {
                    $randomLanguage->update(['is_main_lang' => true]);
                }
            }

            static::deleteJsonFile($language->code);
        });
    }

    /**
     * Reset the `is_main` flag for all languages except the given one.
     */
    protected static function resetIsMain($excludeId = null)
    {
        $query = static::query()->where('is_main_lang', true);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $query->update(['is_main_lang' => false]);
    }

    /**
     * Create a JSON file for the language.
     */
    protected static function createJsonFile($langCode)
    {
        $filePath = resource_path("lang/{$langCode}.json");
        if (!File::exists($filePath)) {
            File::put($filePath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Rename a JSON file when `lang_code` is updated.
     */
    protected static function renameJsonFile($oldLangCode, $newLangCode)
    {
        $oldFilePath = resource_path("lang/{$oldLangCode}.json");
        $newFilePath = resource_path("lang/{$newLangCode}.json");

        if (File::exists($oldFilePath)) {
            File::move($oldFilePath, $newFilePath);
        }
    }

    /**
     * Delete a JSON file when a language is deleted.
     */
    protected static function deleteJsonFile($langCode)
    {
        $filePath = resource_path("lang/{$langCode}.json");
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
