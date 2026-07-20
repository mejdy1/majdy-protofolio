<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Site settings';

    protected static ?string $slug = 'site-settings';

    protected string $view = 'filament.admin.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'seo_title'       => SiteSetting::get('seo.title'),
            'seo_description' => SiteSetting::get('seo.description'),
            'accent'          => SiteSetting::get('theme.accent', '#7C5CFF'),
            'accent_2'        => SiteSetting::get('theme.accent_2', '#22D3EE'),
            'contact_email'   => SiteSetting::get('contact.email'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('SEO defaults')->schema([
                Grid::make(2)->schema([
                    TextInput::make('seo_title')->label('Default title')->maxLength(120),
                    TextInput::make('contact_email')->label('Contact email')->email(),
                    Textarea::make('seo_description')->label('Meta description')->rows(2)->maxLength(280)->columnSpanFull(),
                ]),
            ]),
            Section::make('Theme')->description('Used for both the public site and the admin theme accent.')->schema([
                Grid::make(2)->schema([
                    ColorPicker::make('accent')->label('Primary accent'),
                    ColorPicker::make('accent_2')->label('Secondary accent'),
                ]),
            ]),
        ])->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->icon('heroicon-o-check')
                ->keyBindings(['mod+s'])
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        SiteSetting::put('seo.title',       $data['seo_title'],       'seo', 'text');
        SiteSetting::put('seo.description', $data['seo_description'], 'seo', 'textarea');
        SiteSetting::put('theme.accent',    $data['accent'],          'theme', 'color');
        SiteSetting::put('theme.accent_2',  $data['accent_2'],        'theme', 'color');
        SiteSetting::put('contact.email',   $data['contact_email'],   'contact', 'email');
        Notification::make()->title('Settings saved')->success()->send();
    }
}
