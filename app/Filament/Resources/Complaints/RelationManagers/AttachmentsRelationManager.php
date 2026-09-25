<?php

namespace App\Filament\Resources\Complaints\RelationManagers;

use App\Enums\ComplaintAttachmentType;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Foto & Dokumen Bukti Pendukung';

    protected static ?string $modelLabel = 'Lampiran';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipe Lampiran')
                    ->options(ComplaintAttachmentType::class)
                    ->default(ComplaintAttachmentType::Photo)
                    ->required(),
                FileUpload::make('file_path')
                    ->label('File Foto / Dokumen')
                    ->directory('complaint_attachments')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                    ->maxSize(5120)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_path')
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview Foto')
                    ->visibility('private')
                    ->circular()
                    ->defaultImageUrl('/images/document-placeholder.png'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ComplaintAttachmentType ? $state->label() : (ComplaintAttachmentType::tryFrom((string) $state)?->label() ?? $state))
                    ->color(fn ($state) => $state === ComplaintAttachmentType::Photo ? 'info' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Diunggah Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Lampiran'),
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->action(function ($record) {
                        if (Storage::exists($record->file_path)) {
                            return Storage::download($record->file_path);
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
