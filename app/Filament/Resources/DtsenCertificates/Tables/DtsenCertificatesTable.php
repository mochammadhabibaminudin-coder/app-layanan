<?php

namespace App\Filament\Resources\DtsenCertificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DtsenCertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('No. Surat SK')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum terbit')
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('serviceRequest.request_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('subject_name')
                    ->label('Nama yang Diterangkan')
                    ->searchable()
                    ->description(fn ($record) => 'NIK: '.$record->subject_nik.' ('.$record->relationship_to_applicant.')'),
                TextColumn::make('purpose.name')
                    ->label('Tujuan Penggunaan')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_registered')
                    ->label('Terdaftar SIKS-NG')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('decile')
                    ->label('Desil')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 2 => 'success',
                        $state <= 4 => 'info',
                        $state <= 5 => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state ? 'Desil '.$state : '-')
                    ->sortable(),
                TextColumn::make('issued_at')
                    ->label('Tgl Terbit')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('Belum terbit'),
                TextColumn::make('valid_until')
                    ->label('Berlaku s.d.')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('verification_code')
                    ->label('Kode Verifikasi QR')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signer.name')
                    ->label('Pejabat Penandatangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checker.name')
                    ->label('Petugas Cek SIKS-NG')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('dtsen_purpose_id')
                    ->label('Tujuan Penggunaan')
                    ->relationship('purpose', 'name'),
                TernaryFilter::make('is_registered')
                    ->label('Status di SIKS-NG')
                    ->placeholder('Semua')
                    ->trueLabel('Terdaftar')
                    ->falseLabel('Tidak Terdaftar'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
