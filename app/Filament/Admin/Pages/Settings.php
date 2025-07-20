<?php

namespace App\Filament\Admin\Pages;

use App\Services\SettingsManager;
use Arr;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Jackiedo\DotenvEditor\Exceptions\KeyNotFoundException;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use JsonException;

class Settings extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.admin.pages.settings';

    public ?array $data = [];

    public function mount(SettingsManager $settings): void
    {
        $savedSettings = Arr::undot($settings->all());

        $this->form->fill(
            array_replace_recursive(
                $savedSettings,
                [
                    'email' => [

                        'driver' => config('mail.default'),
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'scheme' => config('mail.mailers.smtp.scheme'),
                        'username' => config('mail.mailers.smtp.username'),
                        'password' => config('mail.mailers.smtp.password'),
                        'email' => config('mail.from.address'),
                        'name' => config('mail.from.name'),
                    ],

                    'payments' => [
                        'publishable' => config('payments.drivers.stripe.public_key'),
                        'secret' => config('payments.drivers.stripe.secret_key'),
                        //                        'webhook_secret' => config('payments.drivers.stripe.webhook_secret'),
                        //                        'webhook' => route('cashier.webhook')

                    ],

                ]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('general.site_name')
                                    ->label('Site Name')
                                    ->required(),
                                Forms\Components\TextInput::make('general.site_description')
                                    ->label('Site Description'),

                                Forms\Components\FileUpload::make('general.site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->visibility('public')
                                    ->directory('site'),
                                Forms\Components\FileUpload::make('general.site_favicon')
                                    ->label('Site Favicon')
                                    ->image()
                                    ->directory('site'),

                                //         TODO:                       add vimeo api key

                                Forms\Components\TextInput::make('general.socials.email')
                                    ->label('Contact Email'),

                                Forms\Components\TextInput::make('general.socials.twitter')
                                    ->label('Twitter Handle'),
                                Forms\Components\TextInput::make('general.socials.linkedin')
                                    ->label('LinkedIn Handle'),
                                Forms\Components\TextInput::make('general.socials.youtube')
                                    ->label('Youtube Handle'),

                                Forms\Components\TextInput::make('general.socials.github')
                                    ->label('Github Handle'),

                            ]),

                        Forms\Components\Tabs\Tab::make('Landing Page')
                            ->schema([
                                Forms\Components\TextInput::make('landing.hero_title')
                                    ->label('Hero Title')
                                    ->required(),
                                Forms\Components\TextInput::make('landing.hero_subtitle')
                                    ->label('Hero Subtitle')
                                    ->required(),
                                Forms\Components\TextInput::make('landing.hero_cta_text')
                                    ->label('Hero CTA Text')
                                    ->required(),
                                Forms\Components\FileUpload::make('landing.hero_image')
                                    ->label("Hero's Image")
                                    ->image()
                                    ->required()
                                    ->directory('site'),

                                Forms\Components\TextInput::make('landing.features.section.title')
                                    ->label('Feature Section Title')
                                    ->required(),
                                Forms\Components\TextInput::make('landing.features.section.subtitle')
                                    ->label('Feature Section Subtitle')
                                    ->required(),
                                Forms\Components\Repeater::make('landing.features.items')
                                    ->label('Feature items')
                                    ->required()
                                    ->schema([
                                        Forms\Components\TextInput::make('title'),
                                        Forms\Components\TextInput::make('description'),
                                        Forms\Components\TextInput::make('icon')
                                            ->hint(' visit https://icon-sets.iconify.design/'),
                                    ])
                                    ->defaultItems(0)
                                    ->columns(3),

                                Forms\Components\TextInput::make('landing.instructor.section.title')
                                    ->label("Instructor's Section Title")
                                    ->required(),
                                Forms\Components\TextInput::make('landing.instructor.name')
                                    ->label("Instructor's Name")
                                    ->required(),

                                Forms\Components\TextInput::make('landing.instructor.title')
                                    ->label("Instructor's  Title")
                                    ->required(),
                                Forms\Components\TextInput::make('landing.instructor.about')
                                    ->label("Instructor's About")
                                    ->required(),

                                Forms\Components\TextInput::make('landing.instructor.socials.twitter')
                                    ->label("Instructor's Twitter Handle"),
                                Forms\Components\TextInput::make('landing.instructor.socials.linkedin')
                                    ->label("Instructor's LinkedIn Handle"),
                                Forms\Components\TextInput::make('landing.instructor.socials.youtube')
                                    ->label("Instructor's Youtube Handle"),

                                Forms\Components\TextInput::make('landing.instructor.socials.github')
                                    ->label("Instructor's Github Handle"),

                                Forms\Components\TextInput::make('landing.instructor.socials.website')
                                    ->label("Instructor's Website"),
                                Forms\Components\FileUpload::make('landing.instructor.image')
                                    ->label("Instructor's Image")
                                    ->image()
                                    ->directory('site'),

                                Forms\Components\TextInput::make('landing.faq.title')
                                    ->label('FAQ Title')
                                    ->required(),
                                Forms\Components\TextInput::make('landing.faq.description')
                                    ->label('FAQ Description')
                                    ->required(),

                                Forms\Components\Repeater::make('landing.faq.items')
                                    ->schema([
                                        Forms\Components\TextInput::make('question')
                                            ->required(),
                                        Forms\Components\Textarea::make('answer')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Payments')
                            ->schema([
                                Forms\Components\TextInput::make('payments.publishable')
                                    ->label('Stripe Publishable Key')
                                    ->required(),

                                Forms\Components\TextInput::make('payments.secret')
                                    ->label('Stripe Secret Key')
                                    ->required(),

                                //                                Forms\Components\TextInput::make('payments.webhook_secret')
                                //                                    ->label(' Stripe Webhook Secret ')
                                //                                    ->required(),

                                //                                Forms\Components\TextInput::make('payments.webhook')
                                //                                    ->label('Webhook URL')
                                //                                    ->disabled()
                                //                                    ->suffixAction(CopyAction::make()),
                            ]),

                        Forms\Components\Tabs\Tab::make('Email Configuration')
                            ->schema([
                                Forms\Components\Select::make('email.driver')
                                    ->label('Mail Driver')
                                    ->options([
                                        'log' => 'Log',
                                        'smtp' => 'SMTP',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('email.host')
                                    ->label('SMTP Host'),

                                Forms\Components\TextInput::make('email.port')
                                    ->label('SMTP Port'),

                                Forms\Components\Select::make('email.scheme')
                                    ->label('SMTP Scheme')
                                    ->options([
                                        'null' => 'null',
                                        'smtp' => 'SMTP',
                                        'smtps' => 'SMTPS',
                                    ]),

                                Forms\Components\TextInput::make('email.name')
                                    ->label('From Name')
                                    ->required(),

                                Forms\Components\TextInput::make('email.email')
                                    ->label('From Email Address')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('email.username')
                                    ->label('SMTP Username'),

                                Forms\Components\TextInput::make('email.password')
                                    ->label('SMTP Password')
                                    ->password(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save')
                ->color('primary'),
        ];
    }

    /**
     * @throws KeyNotFoundException|JsonException
     */
    public function save(SettingsManager $settings): void
    {
        $data = $this->form->getState();

        if (! empty($data['email'])) {
            $d = $data['email'];

            $this->updateEnv([
                'MAIL_MAILER' => $d['driver'],
                'MAIL_SCHEME' => $d['scheme'],
                'MAIL_HOST' => $d['host'],
                'MAIL_PORT' => $d['port'],
                'MAIL_USERNAME' => $d['username'],
                'MAIL_PASSWORD' => $d['password'],
                'MAIL_FROM_ADDRESS' => $d['email'],
                'MAIL_FROM_NAME' => $d['name'],
            ]);
        }

        if (! empty($data['general'])) {

            $this->updateEnv([
                'APP_NAME' => $data['general']['site_name'],
            ]);
        }

        if (! empty($data['payments'])) {
            $d = $data['payments'];

            $this->updateEnv([
                'STRIPE_KEY' => $d['publishable'],
                'STRIPE_SECRET' => $d['secret'],
                //                'STRIPE_WEBHOOK_SECRET' => $d['webhook_secret'],

            ]);
        }

        $this->saveSettingsToDatabase($settings, $data);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();

    }

    /**
     * @throws KeyNotFoundException
     */
    protected function updateEnv(array $keys): void
    {
        $env = DotenvEditor::load();
        $changed = false;

        foreach ($keys as $key => $value) {
            if ($env->getValue($key) !== $value) {
                $env->setKey($key, $value);
                $changed = true;
            }
        }

        if ($changed) {
            $env->save();
        }
    }

    /**
     * @throws JsonException
     */
    protected function saveSettingsToDatabase(SettingsManager $settings, array $data): void
    {
        unset($data['email'], $data['payments']);

        foreach ($data as $group => $groupSettings) {
            foreach ($groupSettings as $key => $value) {
                $settingKey = "{$group}.{$key}";

                $type = match (true) {
                    is_bool($value) => 'bool',
                    is_int($value) => 'int',
                    is_array($value) => 'json',
                    default => 'string',
                };

                $settings->set($settingKey, $value, $type);
            }
        }
    }
}
