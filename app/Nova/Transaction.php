<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use App\Nova\OrderTransaction;
use App\Nova\PayoutTransaction;
use App\Nova\RefundTransaction;

class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Transaction>
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Stripe Payment ID', 'stripe_payment_id')
                ->sortable()
                ->rules('required')
                ->copyable(),

            Select::make('Type')
                ->options([
                    'payment' => 'Payment',
                    'refund' => 'Refund',
                    'order' => 'Order',
                    'payout' => 'Payout',
                ])
                ->sortable()
                ->rules('required'),

            Number::make('Amount')
                ->sortable()
                ->rules('required')
                ->displayUsing(fn($value) => '£' . $value)
                ->help('Amount in pence (will be displayed in pounds)'),

            HasMany::make('Order Transactions', 'orderTransactions', OrderTransaction::class)
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(function ($request, $resource) {
                    return $resource->type === 'order';
                }),

            HasOne::make('Payout Transaction', 'payoutTransaction', PayoutTransaction::class)
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(function ($request, $resource) {
                    return $resource->type === 'payout';
                }),

            HasOne::make('Refund Transaction', 'refundTransaction', RefundTransaction::class)
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(function ($request, $resource) {
                    return $resource->type === 'refund';
                }),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @return bool
     */
    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }
}
