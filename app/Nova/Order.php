<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Nova\OrderItem;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;

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

            Select::make('User Id')->options(User::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Select::make('Payment Method Id')->options(PaymentMethod::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Boolean::make('Payment Confirmed')->trueValue('1')->falseValue('0')->default(0)->rules('required'),

            Select::make('Status')->options([
                'confirmed' => 'Confrimed',
                'cancelled' => 'Cancelled',
                'pending' => 'Pending'
            ])->rules('required'),

            HasMany::make('Order Items', 'orderItems', OrderItem::class),

            Number::make('Amount')->rules('required')->min(0),

            Textarea::make('Addresses')->rules('required'),

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
}
