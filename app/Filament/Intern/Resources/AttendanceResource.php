<?php

namespace App\Filament\Intern\Resources;

use App\Filament\Intern\Resources\AttendanceResource\Pages;
use App\Filament\Intern\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Carbon\Carbon;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    // Disable Create, Edit, and Delete buttons for Interns
    public static function canCreate(): bool 
    {
        return false;
    }
    
    // Global Scope: Show ONLY the logged-in intern's data
    public static function getEloquentQuery(): Builder
    {
        // The authenticated user in the Intern panel is the Intern model
        return parent::getEloquentQuery()
            ->where('intern_id', auth()->id()); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('date')
                        ->date('D, d M Y')
                        ->extraAttributes(['class' => 'text-lg font-bold text-primary-600 dark:text-primary-400']),
                    
                    BadgeColumn::make('status')
                        ->colors([
                            'success' => 'present',
                            'danger' => 'absent',
                            'warning' => 'late',
                            'primary' => 'leave',
                        ])
                        ->extraAttributes(['class' => 'my-2 w-fit']),

                    TextColumn::make('note')
                        ->size('sm')
                        ->color('gray')
                        ->prefix('Note: ')
                        ->placeholder('No notes for this day') 
                    ->toggleable(isToggledHiddenByDefault: false),
                ]),
            ])
            // This transforms the table rows into a 3-column grid of cards
            ->contentGrid([
                'md' => 2,
                'xl' => 5,
            ])
            ->defaultSort('date', 'desc')
            ->recordAction(null) // Disables clicking into a view page if not needed
            ->recordUrl(null)
            ->filters([
                SelectFilter::make('month')
                    ->label('Filter by Month')
                    ->options([
                        '01' => 'January',
                        '02' => 'February',
                        '03' => 'March',
                        '04' => 'April',
                        '05' => 'May',
                        '06' => 'June',
                        '07' => 'July',
                        '08' => 'August',
                        '09' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $date): Builder => $query->whereMonth('date', $date),
                        );
                    }),

                // 2. WEEK FILTER
                Filter::make('week')
                    ->label('Filter by Week')
                    ->form([
                        Select::make('week_offset')
                            ->label('Time Period')
                            ->options([
                                '0' => 'This Week',
                                '1' => 'Last Week',
                                '2' => '2 Weeks Ago',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['week_offset'] !== null,
                            function (Builder $query) use ($data): Builder {
                                $startOfWeek = Carbon::now()->subWeeks((int) $data['week_offset'])->startOfWeek();
                                $endOfWeek = Carbon::now()->subWeeks((int) $data['week_offset'])->endOfWeek();
                                
                                return $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
                            },
                        );
                    })
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
