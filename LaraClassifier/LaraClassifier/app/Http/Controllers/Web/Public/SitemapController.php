<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Web\Public;

use App\Models\Category;
use App\Models\City;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Larapen\LaravelMetaTags\Facades\MetaTag;

class SitemapController extends FrontController
{
	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index()
	{
		$categoriesLimit = getNumberOfItemsToTake('categories');
		$citiesLimit = getNumberOfItemsToTake('cities');
		
		$data = [];
		
		// Get Categories
		$cacheId = 'categories.take.' . $categoriesLimit . '.' . config('app.locale');
		$cats = cache()->remember($cacheId, $this->cacheExpiration, function () use ($categoriesLimit) {
			return Category::root()
				->with([
					'parent',
					'children' => fn (Builder $query) => $query->orderBy('lft')->limit($categoriesLimit),
					'children.parent'
				])
				->orderBy('lft')
				->take($categoriesLimit)
				->get();
		});
		$cats = collect($cats)->keyBy('id');
		
		$col = round($cats->count() / 3, 0, PHP_ROUND_HALF_EVEN);
		$col = ($col > 0) ? $col : 1;
		$data['cats'] = $cats->chunk($col);
		
		// Get Cities
		$cacheId = config('country.code') . '.cities.take.' . $citiesLimit;
		$cities = cache()->remember($cacheId, $this->cacheExpiration, function () use ($citiesLimit) {
			return City::query()
				->inCountry()
				->take($citiesLimit)
				->orderByDesc('population')
				->orderBy('name')
				->get();
		});
		
		$col = round($cities->count() / 4, 0, PHP_ROUND_HALF_EVEN);
		$col = ($col > 0) ? $col : 1;
		$data['cities'] = $cities->chunk($col);
		
		// Meta Tags
		[$title, $description, $keywords] = getMetaTag('sitemap');
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);
		
		return appView('sitemap.index', $data);
	}
}