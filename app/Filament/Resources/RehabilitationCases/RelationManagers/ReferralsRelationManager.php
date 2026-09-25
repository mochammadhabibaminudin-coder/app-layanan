<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Enums\ReferralStatus;
use App\Models\Assessment;
use App\Models\ReferralInstitution;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrals';

    protected static ?string $title = 'Rujukan ke Lembaga / Mitra';

    protected static ?string $modelLabel = 'Rujukan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->components([
                    TextInput::make('referral_number')
                        ->label('Nomor Rujukan')
                        ->placeholder('(Otomatis dibuat sistem)')
                        ->disabled()
                        ->dehydrated(fn ($state) => filled($state)),
                    Select::make('referral_institution_id')
                        ->label('Lembaga Tujuan Rujukan')
                        ->options(ReferralInstitution::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    Select::make('assessment_id')
                        ->label('Dasar Assessment')
                        ->options(function () {
                            $case = $this->getOwnerRecord();

                            return Assessment::where('rehabilitation_case_id', $case?->id)
                                ->get()
                                ->mapWithKeys(fn ($item) => [$item->id => "Assessment tgl {$item->assessment_date->format('d/m/Y')} — ".substr($item->recommendation, 0, 40).'...']);
                        })
                        ->searchable()
                        ->required(),
                ]),
                Grid::make(3)->components([
                    DatePicker::make('referral_date')
                        ->label('Tanggal Surat Rujukan')
                        ->default(now())
                        ->required(),
                    Select::make('officer_id')
                        ->label('Petugas Pendamping')
                        ->options(User::pluck('name', 'id'))
                        ->default(auth()->id())
                        ->required()
                        ->searchable(),
                    Select::make('status')
                        ->label('Status Rujukan')
                        ->options(ReferralStatus::class)
                        ->default(ReferralStatus::Draft)
                        ->required(),
                ]),
                Textarea::make('service_result')
                    ->label('Hasil Pelayanan dari Lembaga Rujukan')
                    ->placeholder('Catat hasil pelayanan setelah klien selesai ditangani di lembaga tujuan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referral_number')
            ->defaultSort('referral_date', 'desc')
            ->columns([
                TextColumn::make('referral_number')
                    ->label('No. Rujukan')
                    ->weight('bold')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('institution.name')
                    ->label('Lembaga Tujuan')
                    ->description(fn ($record) => $record->institution?->type ?? '')
                    ->searchable(),
                TextColumn::make('referral_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->icon(Heroicon::OutlinedUser),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ReferralStatus ? $state->label() : (ReferralStatus::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state instanceof ReferralStatus ? $state->color() : (ReferralStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                TextColumn::make('service_result')
                    ->label('Hasil Pelayanan')
                    ->placeholder('(Dalam proses penanganan)')
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Rujukan Baru'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('update_status')
                    ->label('Perbarui Status')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('info')
                    ->schema([
                        Select::make('status')
                            ->label('Status Rujukan')
                            ->options(ReferralStatus::class)
                            ->required(),
                        Textarea::make('service_result')
                            ->label('Catatan Perkembangan / Hasil')
                            ->default(fn ($record) => $record->service_result),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->transitionTo($data['status'], $data['service_result'] ?? null);

                        if ($data['status'] === ReferralStatus::Completed->value) {
                            $record->update(['completed_at' => now()]);
                        }

                        Notification::make()
                            ->title('Status rujukan berhasil diperbarui')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
