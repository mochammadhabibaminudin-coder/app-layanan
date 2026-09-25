<?php

namespace App\Filament\Resources\DtsenCertificates;

use App\Filament\Resources\DtsenCertificates\Pages\CreateDtsenCertificate;
use App\Filament\Resources\DtsenCertificates\Pages\EditDtsenCertificate;
use App\Filament\Resources\DtsenCertificates\Pages\ListDtsenCertificates;
use App\Filament\Resources\DtsenCertificates\Pages\ViewDtsenCertificate;
use App\Filament\Resources\DtsenCertificates\Schemas\DtsenCertificateForm;
use App\Filament\Resources\DtsenCertificates\Schemas\DtsenCertificateInfolist;
use App\Filament\Resources\DtsenCertificates\Tables\DtsenCertificatesTable;
use App\Models\DtsenCertificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DtsenCertificateResource extends Resource
{
    protected static ?string $model = DtsenCertificate::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Prioritas';

    protected static ?string $navigationLabel = 'Surat Keterangan DTSEN';

    protected static ?string $modelLabel = 'SK DTSEN';

    protected static ?string $pluralModelLabel = 'SK DTSEN';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return DtsenCertificateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DtsenCertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DtsenCertificatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ApprovalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDtsenCertificates::route('/'),
            'create' => CreateDtsenCertificate::route('/create'),
            'view' => ViewDtsenCertificate::route('/{record}'),
            'edit' => EditDtsenCertificate::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
