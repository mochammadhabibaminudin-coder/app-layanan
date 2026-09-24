<?php

namespace App\Filament\Resources\InformationPages\Schemas;

use App\Enums\InformationPageCategory;
use App\Enums\PublishStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InformationPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('category')
                    ->options(InformationPageCategory::class)
                    ->default('program')
                    ->required(),
                Select::make('service_type_id')
                    ->relationship('serviceType', 'name'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('requirements')
                    ->columnSpanFull(),
                Textarea::make('procedure')
                    ->columnSpanFull(),
                TextInput::make('service_hours'),
                TextInput::make('location'),
                TextInput::make('contact'),
                Select::make('publish_status')
                    ->options(PublishStatus::class)
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
                Select::make('manager_id')
                    ->relationship('manager', 'name'),
            ]);
    }
}
