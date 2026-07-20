<?php

namespace App\Filament\Admin\Pages;

use App\Models\Profile;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProfileSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Studio';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Profile';

    protected static ?string $slug = 'profile';

    protected string $view = 'filament.admin.pages.profile-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Profile::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')->description('What people see first.')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required()->maxLength(120),
                    TextInput::make('headline')->required()->maxLength(160),
                    TextInput::make('tagline')->maxLength(220)->columnSpanFull(),
                    TagsInput::make('roles')->placeholder('Add a role')->columnSpanFull(),
                ]),
            ]),
            Section::make('About')->schema([
                RichEditor::make('bio')->columnSpanFull(),
            ]),
            Section::make('Contact')->schema([
                Grid::make(2)->schema([
                    TextInput::make('location'),
                    TextInput::make('email')->email(),
                    TextInput::make('phone'),
                    Toggle::make('available_for_hire')->inline(false),
                ]),
            ]),
            Section::make('Media')->schema([
                Grid::make(2)->schema([
                    TextInput::make('avatar_url')->url()->prefixIcon('heroicon-o-user'),
                    TextInput::make('hero_image_url')->url()->prefixIcon('heroicon-o-photo'),
                    FileUpload::make('resume_path')
                        ->label('CV / Résumé (PDF)')
                        ->disk('public')
                        ->directory('cv')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),
                ]),
            ]),
        ])->statePath('data')->model(Profile::current());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save profile')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->keyBindings(['mod+s'])
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Profile::current()->update($data);
        Notification::make()->title('Profile saved')->success()->send();
    }
}
