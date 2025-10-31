<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Brand;
use App\Nova\Category;
use App\Nova\Colour;
use App\Nova\Condition;
use App\Nova\Dashboards\Main;
use App\Nova\FulfillmentOption;
use App\Nova\Order;
use App\Nova\OrderItem;
use App\Nova\OrderItemSize;
use App\Nova\Product;
use App\Nova\ProductColour;
use App\Nova\ProductFulfillmentOption;
use App\Nova\ProductSize;
use App\Nova\Size;
use App\Nova\Organisation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Transaction;
use App\Nova\ShippingServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		parent::boot();
		Nova::withBreadcrumbs();

		Nova::mainMenu(function (Request $request) {

		$usersResources = [
			MenuItem::resource(\App\Nova\User::class),
			MenuItem::resource(Organisation::class),
		];

			$productsResources = [
				MenuItem::resource(Product::class),
				MenuItem::resource(Brand::class),
				MenuItem::resource(Category::class),
				MenuItem::resource(Colour::class),
				MenuItem::resource(Size::class),
				MenuItem::resource(Condition::class),
				MenuItem::resource(FulfillmentOption::class),
			];

			$additionalMappingTables = [
				MenuItem::resource(ProductColour::class),
				MenuItem::resource(ProductFulfillmentOption::class),
				MenuItem::resource(ProductSize::class),
				MenuItem::resource(ShippingServiceProvider::class),
			];

			$transactionsResources = [
				MenuItem::resource(Transaction::class),
			];

			$ordersResources = [
				MenuItem::resource(Order::class),
				MenuItem::resource(OrderItem::class),
				MenuItem::resource(OrderItemSize::class),
			];

			return [
				MenuSection::dashboard(Main::class),
				MenuSection::make('Users', $usersResources)->collapsable()->collapsedByDefault(),
				MenuSection::make('Products', $productsResources)->collapsable()->collapsedByDefault(),
				MenuSection::make('Orders', $ordersResources)->collapsable()->collapsedByDefault(),
				MenuSection::make('Transactions', $transactionsResources),
				MenuSection::make('Additional Mapping Tables', $additionalMappingTables)->collapsable()->collapsedByDefault(),
			];
		});
	}

	/**
	 * Register the configurations for Laravel Fortify.
	 */
	protected function fortify(): void
	{
		Nova::fortify()
			->features([
				Features::updatePasswords(),
				// Features::emailVerification(),
				// Features::twoFactorAuthentication(['confirm' => true, 'confirmPassword' => true]),
			])
			->register();
	}

	/**
	 * Register the Nova routes.
	 */
	protected function routes(): void
	{
		Nova::routes()
			->withAuthenticationRoutes(default: true)
			->withPasswordResetRoutes()
			->withoutEmailVerificationRoutes()
			->register();
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 */
	protected function gate(): void
	{
		Gate::define('viewNova', function (User $user) {
			return $user->role === 'admin';
			// return in_array($user->email, [
			// 	//
			// ]);
		});
	}

	/**
	 * Get the dashboards that should be listed in the Nova sidebar.
	 *
	 * @return array<int, \Laravel\Nova\Dashboard>
	 */
	protected function dashboards(): array
	{
		return [
			new \App\Nova\Dashboards\Main,
		];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array<int, \Laravel\Nova\Tool>
	 */
	public function tools(): array
	{
		return [];
	}

	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		parent::register();

		//
	}
}
