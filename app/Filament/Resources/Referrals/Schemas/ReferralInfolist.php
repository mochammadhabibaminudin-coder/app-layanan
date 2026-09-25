<?php

namespace App\Filament\Resources\Referrals\Schemas;

use App\Enums\ReferralStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ReferralInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Surat Rujukan')
                    ->components([
                        Grid::make(3)->components([
                            TextEntry::make('referral_number')
                                ->label('Nomor Rujukan')
                                ->copyable()
                                ->weight('bold')
                                ->icon(Heroicon::OutlinedDocumentText),
                            TextEntry::make('status')
                                ->label('Status Rujukan')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ReferralStatus ? $state->label() : (ReferralStatus::tryFrom((string) $state)?->label() ?? $state))
                                ->color(fn ($state) => $state instanceof ReferralStatus ? $state->color() : (ReferralStatus::tryFrom((string) $state)?->color() ?? 'gray')),
                            TextEntry::make('referral_date')
                                ->label('Tanggal Rujukan')
                                ->date('d F Y')
                                ->icon(Heroicon::OutlinedCalendar),
                        ]),
                        Grid::make(3)->components([
                            TextEntry::make('institution.name')
                                ->label('Lembaga Tujuan Rujukan')
                                ->weight('bold'),
                            TextEntry::make('institution.type')
                                ->label('Jenis Lembaga')
                                ->badge()
                                ->color('info'),
                            TextEntry::make('officer.name')
                                ->label('Petugas Pekerja Sosial')
                                ->icon(Heroicon::OutlinedUser),
                        ]),
                    ]),

                Section::make('Kasus & Hasil Assessment Asal')
                    ->components([
                        Grid::make(2)->components([
                            TextEntry::make('rehabilitationCase.case_number')
                                ->label('Nomor Kasus')
                                ->badge()
                                ->color('purple'),
                            TextEntry::make('rehabilitationCase.client.name')
                                ->label('Nama Klien Penerima Manfaat')
                                ->weight('bold'),
                        ]),
                        TextEntry::make('assessment.recommendation')
                            ->label('Rekomendasi Assessment yang Menjadi Dasar Rujukan')
                            ->columnSpanFull(),
                    ]),

                Section::make('Hasil Pelayanan dari Lembaga')
                    ->components([
                        TextEntry::make('service_result')
                            ->label('Hasil Akhir Pelayanan / Penanganan di Lembaga')
                            ->placeholder('(Layanan rujukan masih berlangsung)')
                            ->columnSpanFull(),
                        TextEntry::make('completed_at')
                            ->label('Waktu Selesai Penanganan')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('(Belum Selesai)'),
                    ]),
            ]);
    }
}
