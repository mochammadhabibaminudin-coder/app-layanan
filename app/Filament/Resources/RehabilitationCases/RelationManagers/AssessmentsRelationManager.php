<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessments';

    protected static ?string $title = 'Hasil Assessment Klien';

    protected static ?string $modelLabel = 'Assessment';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->components([
                    DatePicker::make('assessment_date')
                        ->label('Tanggal Assessment')
                        ->default(now())
                        ->required(),
                    Select::make('officer_id')
                        ->label('Petugas Assessor')
                        ->options(User::pluck('name', 'id'))
                        ->default(auth()->id())
                        ->required()
                        ->searchable(),
                    Toggle::make('needs_referral')
                        ->label('Perlu Rujukan ke Lembaga Luar?')
                        ->helperText('Aktifkan jika penanganan memerlukan panti/balai/RS')
                        ->inline(false)
                        ->required(),
                ]),
                Textarea::make('result')
                    ->label('Hasil Assessment (Kondisi Fisik, Mental, Sosial)')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('service_needs')
                    ->label('Kebutuhan Pelayanan yang Diidentifikasi')
                    ->rows(2)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('recommendation')
                    ->label('Rencana Penanganan & Rekomendasi Tindakan')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('result')
            ->defaultSort('assessment_date', 'desc')
            ->columns([
                TextColumn::make('assessment_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas Assessor')
                    ->icon(Heroicon::OutlinedUser),
                IconColumn::make('needs_referral')
                    ->label('Perlu Rujukan')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('result')
                    ->label('Hasil Assessment')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('recommendation')
                    ->label('Rekomendasi Tindakan')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Catat Assessment Baru'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
