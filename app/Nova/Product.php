<?php

namespace App\Nova;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;
use App\Nova\FulfillmentOption;
use App\Models\User;
use Dab\TestField\TestField;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product>
     */
    public static $model = \App\Models\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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

            Boolean::make('Is Featured')->trueValue('1')->falseValue('0'),

            Select::make('Type')->options([
                'sale' => 'Sale',
                'hire' => 'Hire'
            ])->required(),

            Text::make('Name')->rules('required'),

            Select::make('User Id')->options(User::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Select::make('Category Id')->options(Category::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Select::make('Condition Id')->options(Condition::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Select::make('Brand Id')->options(Brand::pluck('name', 'id'))->rules('required')->sortable()->displayUsingLabels(),

            Textarea::make('Description')->rules('required'),

            Number::make('Price')->rules('required')->min(0),

            BelongsToMany::make('Fulfillment Options', 'fulfillmentOptions', FulfillmentOption::class),

            HasMany::make('Sizes', 'productSizes', ProductSize::class),

            BelongsToMany::make('Colours'),
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
