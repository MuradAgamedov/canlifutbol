<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WebsiteInfo extends Model
{
    use HasFactory, HasTranslations, ImageTrait;

    protected $guarded = [];

    // Sadəcə tərcüməyə açıq sahələri bura yaz:
    public $translatable = [
        'copyright_text',
        'logo_header_alt_text',
        'logo_footer_alt_text'
    ];
}
