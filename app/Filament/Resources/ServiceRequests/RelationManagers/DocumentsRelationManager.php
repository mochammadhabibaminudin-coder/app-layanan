<?php

namespace App\Filament\Resources\ServiceRequests\RelationManagers;

use App\Models\ServiceRequirement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen Persyaratan';

    protected static ?string $modelLabel = 'Dokumen Persyaratan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_requirement_id')
                    ->label('Jenis Persyaratan')
                    ->options(function () {
                        $serviceRequestId = $this->getOwnerRecord()?->service_type_id;

                        return ServiceRequirement::query()
                            ->when($serviceRequestId, fn ($q) => $q->where('service_type_id', $serviceRequestId))
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable(),
                FileUpload::make('file_path')
                    ->label('File Dokumen')
                    ->directory('service_documents')
                    ->storeFileNamesIn('original_name')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(5120)
                    ->required(),
                Select::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'valid' => 'Valid / Sesuai',
                        'revision_needed' => 'Perlu Perbaikan / Revisi',
                    ])
                    ->default('pending')
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan Petugas')
                    ->placeholder('Catatan kelengkapan atau alasan perbaikan jika tidak valid')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->columns([
                TextColumn::make('serviceRequirement.name')
                    ->label('Nama Persyaratan')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('original_name')
                    ->label('Nama File')
                    ->searchable()
                    ->icon(Heroicon::OutlinedDocumentText),
                TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'revision_needed' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'valid' => 'Valid / Sesuai',
                        'revision_needed' => 'Perlu Perbaikan',
                        default => 'Menunggu Verifikasi',
                    }),
                TextColumn::make('notes')
                    ->label('Catatan Petugas')
                    ->placeholder('-')
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Diunggah Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Dokumen'),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->color('info')
                    ->schema([
                        Select::make('verification_status')
                            ->label('Hasil Verifikasi')
                            ->options([
                                'valid' => 'Valid / Sesuai',
                                'revision_needed' => 'Perlu Perbaikan',
                                'pending' => 'Menunggu Verifikasi',
                            ])
                            ->default(fn ($record) => $record->verification_status)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan Verifikasi')
                            ->default(fn ($record) => $record->notes),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'verification_status' => $data['verification_status'],
                            'notes' => $data['notes'] ?? null,
                        ]);
                        Notification::make()
                            ->title('Status dokumen diperbarui')
                            ->success()
                            ->send();
                    }),
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->visible(fn ($record) => filled($record->file_path))
                    ->action(function ($record) {
                        if (Storage::exists($record->file_path)) {
                            return Storage::download($record->file_path, $record->original_name ?? 'dokumen');
                        }
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();
                    }),
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
