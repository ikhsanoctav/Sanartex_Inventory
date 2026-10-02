<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PanduanPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Panduan Pengguna';
    protected static ?string $title = 'Panduan Pengguna';
    protected static ?int $navigationSort = 99;

    protected ?string $subheading = 'Pelajari cara menggunakan sistem inventori Sanartex secara lengkap.';

    protected string $view = 'filament.pages.panduan-page';

    public string $activeSection = 'overview';

    public function setSection(string $section): void
    {
        $this->activeSection = $section;
    }
}
