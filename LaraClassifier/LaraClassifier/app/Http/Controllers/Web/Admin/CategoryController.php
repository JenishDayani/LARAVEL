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

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Categories\AdjacentToNested;
use App\Helpers\Files\Upload;
use App\Models\Category;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;
use Prologue\Alerts\Facades\Alert;

class CategoryController extends PanelController
{
	public $parentId = 0;
	public $entryId = null;
	
	public function setup()
	{
		// Get the Parent ID & Current entry ID (If exists)
		$isSubEntry = (request()->segment(4) == 'subcategories');
		$this->parentId = $isSubEntry ? request()->segment(3) : null;
		$this->entryId = $isSubEntry ? request()->segment(5) : request()->segment(3);
		
		// Finding the good URL
		if (request()->segment(4) == 'edit' || request()->segment(6) == 'edit') {
			$entry = Category::find($this->entryId);
			if (!empty($entry)) {
				if (!empty($entry->parent)) {
					if (empty($this->parentId)) {
						$goodId = $entry->parent->id;
						$goodChildId = $entry->id;
					} else {
						if ($this->parentId != $entry->parent->id) {
							$goodId = $entry->parent->id;
							$goodChildId = $entry->id;
						}
					}
					if (isset($goodId, $goodChildId)) {
						$uri = 'categories/' . $goodId . '/subcategories/' . $goodChildId . '/edit';
						
						redirectUrl(admin_url($uri), 301, config('larapen.core.noCacheHeaders'));
					}
				} else {
					if (!empty($this->parentId)) {
						$goodId = $this->entryId;
						$uri = 'categories/' . $goodId . '/edit';
						
						redirectUrl(admin_url($uri), 301, config('larapen.core.noCacheHeaders'));
					}
				}
			}
		}
		
		// Get Parent Category name
		$parent = Category::find($this->parentId);
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel(Category::class);
		$this->xPanel->with(['children', 'fields']);
		$this->xPanel->withoutAppends();
		if (empty($parent)) {
			$this->xPanel->addClause('where', function ($query) {
				$query->root();
			});
			$this->xPanel->setRoute(admin_uri('categories'));
			$this->xPanel->setEntityNameStrings(trans('admin.category'), trans('admin.categories'));
			$this->xPanel->allowAccess(['reorder', 'details_row']);
		} else {
			$this->xPanel->setRoute(admin_uri('categories/' . $this->parentId . '/subcategories'));
			$this->xPanel->setEntityNameStrings(
				trans('admin.subcategory') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>',
				trans('admin.subcategories') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>'
			);
			$this->xPanel->enableParentEntity();
			$this->xPanel->setParentKeyField('parent_id');
			$this->xPanel->addClause('where', 'parent_id', '=', $this->parentId);
			if (!empty($parent->parent)) {
				$this->xPanel->setParentRoute(admin_uri('categories/' . $parent->parent->id . '/subcategories'));
			} else {
				$this->xPanel->setParentRoute(admin_uri('categories'));
			}
			$this->xPanel->setParentEntityNameStrings('parent ' . trans('admin.category'), 'parent ' . trans('admin.categories'));
			$this->xPanel->allowAccess(['reorder', 'details_row', 'parent']);
		}
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'rebuild_nested_set_nodes_button', 'rebuildNestedSetNodesButton', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_activation_button', 'bulkActivationButton', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deactivation_button', 'bulkDeactivationButton', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deletion_button', 'bulkDeletionButton', 'end');
		
		// Filters
		// -----------------------
		$this->xPanel->disableSearchBar();
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'name',
				'type'  => 'text',
				'label' => mb_ucfirst(trans('admin.Name')),
			],
			false,
			fn ($value) => $this->xPanel->addClause('where', 'name', 'LIKE', "%$value%")
		);
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'catType',
				'type'  => 'dropdown',
				'label' => mb_ucfirst(trans('admin.type')),
			],
			[
				'classified'  => 'Classified',
				'job-offer'   => 'Job Offer',
				'job-search'  => 'Job Search',
				'not-salable' => 'Not-Salable',
			],
			fn ($value) => $this->xPanel->addClause('where', 'type', '=', $value)
		);
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'status',
				'type'  => 'dropdown',
				'label' => trans('admin.Status'),
			],
			[
				1 => trans('admin.Activated'),
				2 => trans('admin.Unactivated'),
			],
			function ($value) {
				if ($value == 1) {
					$this->xPanel->addClause('where', 'active', '=', 1);
				}
				if ($value == 2) {
					$this->xPanel->addClause('where', fn ($query) => $query->columnIsEmpty('active'));
				}
			}
		);
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'      => 'id',
			'label'     => '',
			'type'      => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'name',
			'label'         => trans('admin.Name'),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'subcategories',
			'label'         => mb_ucfirst(trans('admin.subcategories')),
			'type'          => 'model_function',
			'function_name' => 'subCategoriesButton',
		]);
		$this->xPanel->addColumn([
			'name'          => 'fields',
			'label'         => mb_ucfirst(trans('admin.custom fields')),
			'type'          => 'model_function',
			'function_name' => 'customFieldsButton',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans('admin.Active'),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);
		
		
		// FIELDS
		$skippedId = (is_numeric($this->entryId)) ? $this->entryId : -1;
		$this->xPanel->addField([
			'name'              => 'parent_id',
			'label'             => 'Parent',
			'type'              => 'select2_from_array',
			'options'           => Category::selectBoxTree($skippedId),
			'allows_null'       => false,
			'value'             => $this->parentId,
			'wrapperAttributes' => [
				'class' => 'col-md-12',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans('admin.Name'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Name'),
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'slug',
			'label'             => trans('admin.Slug'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Will be automatically generated from your name, if left empty'),
			],
			'hint'              => trans('admin.Will be automatically generated from your name, if left empty'),
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$defaultFontIconSet = config('larapen.core.defaultFontIconSet', 'bootstrap');
		$this->xPanel->addField([
			'name'    => 'icon_class',
			'label'   => trans('admin.Icon'),
			'type'    => 'icon_picker',
			'iconset' => config('larapen.core.fontIconSet.' . $defaultFontIconSet . '.key'),
			'version' => config('larapen.core.fontIconSet.' . $defaultFontIconSet . '.version'),
			'hint'    => trans('admin.Used in the categories area on the home and sitemap pages'),
		]);
		$this->xPanel->addField([
			'name'   => 'picture',
			'label'  => trans('admin.Picture'),
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
			'hint'   => trans('admin.category_picture_icon_hint'),
		]);
		
		$wysiwygEditor = config('settings.other.wysiwyg_editor');
		$wysiwygEditorViewPath = '/views/admin/panel/fields/' . $wysiwygEditor . '.blade.php';
		$this->xPanel->addField([
			'name'              => 'description',
			'label'             => trans('admin.Description'),
			'type'              => ($wysiwygEditor != 'none' && file_exists(resource_path() . $wysiwygEditorViewPath))
				? $wysiwygEditor
				: 'textarea',
			'attributes'        => [
				'id'   => 'description',
				'rows' => 5,
			],
			'hint'              => trans('admin.cat_description_hint'),
			'wrapperAttributes' => [
				'class' => 'col-md-12',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'hide_description',
			'label'             => trans('admin.hide_cat_description_label'),
			'type'              => 'checkbox_switch',
			'hint'              => trans('admin.hide_cat_description_hint'),
			'wrapperAttributes' => [
				'class' => 'col-md-12 mb-4',
				'style' => 'margin-top: -15px;',
			],
		]);
		
		$this->xPanel->addField([
			'name'  => 'type',
			'label' => mb_ucfirst(trans('admin.type')),
			'type'  => 'enum',
			'hint'  => trans('admin.category_types_info'),
		]);
		$this->xPanel->addField([
			'name'  => 'is_for_permanent',
			'label' => trans('admin.for_permanent_listings'),
			'type'  => 'checkbox_switch',
			'hint'  => trans('admin.for_permanent_listings_hint'),
		]);
		
		$this->xPanel->addField([
			'name'  => 'seo_tags',
			'type'  => 'custom_html',
			'value' => '<br><h4 style="margin-bottom: 0;">' . trans('admin.seo_tags') . '</h4>',
		]);
		$this->xPanel->addField([
			'name'  => 'seo_start',
			'type'  => 'custom_html',
			'value' => '<hr style="border: 1px dashed #EFEFEF;" class="mt-0 mb-1">',
		]);
		$this->xPanel->addField([
			'name'  => 'dynamic_variables_hint',
			'type'  => 'custom_html',
			'value' => trans('admin.dynamic_variables_hint'),
		]);
		$this->xPanel->addField([
			'name'       => 'seo_title',
			'label'      => trans('admin.Title'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin.Title'),
			],
			'hint'       => trans('admin.seo_title_hint'),
		]);
		$this->xPanel->addField([
			'name'       => 'seo_description',
			'label'      => trans('admin.Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Description'),
			],
			'hint'       => trans('admin.seo_description_hint'),
		]);
		$this->xPanel->addField([
			'name'       => 'seo_keywords',
			'label'      => trans('admin.Keywords'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Keywords'),
			],
			'hint'       => trans('admin.comma_separated_hint') . ' ' . trans('admin.seo_keywords_hint'),
		]);
		
		$this->xPanel->addField([
			'name'  => 'seo_end',
			'type'  => 'custom_html',
			'value' => '<hr style="border: 1px dashed #EFEFEF;">',
		]);
		
		$this->xPanel->addField([
			'name'    => 'active',
			'label'   => trans('admin.Active'),
			'type'    => 'checkbox_switch',
			'default' => '1',
		], 'create');
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans('admin.Active'),
			'type'  => 'checkbox_switch',
		], 'update');
		$this->xPanel->addField([
			'name'  => 'activateChildren',
			'label' => trans('admin.activate_children'),
			'type'  => 'checkbox_switch',
		], 'update');
	}
	
	public function store(StoreRequest $request)
	{
		$request = $this->uploadFile($request);
		
		return parent::storeCrud($request);
	}
	
	public function update(UpdateRequest $request)
	{
		$request = $this->uploadFile($request);
		
		return parent::updateCrud($request);
	}
	
	private function uploadFile($request)
	{
		$attribute = 'picture';
		$destPath = 'app/categories/custom';
		$file = $request->hasFile($attribute) ? $request->file($attribute) : $request->input($attribute);
		
		$request->request->set($attribute, Upload::image($destPath, $file, 'cat'));
		
		return $request;
	}
	
	/**
	 * Convert Adjacent List to Nested Set
	 *
	 * NOTE:
	 * - The items' order is reset, using the adjacent list's primary key order
	 * - Need to use the adjacent list model to add, update or delete nodes
	 */
	public function rebuildNestedSetNodes()
	{
		$errorFound = false;
		
		$params = [
			'adjacentTable' => 'categories',
			'nestedTable'   => 'categories',
		];
		
		$transformer = new AdjacentToNested($params);
		
		try {
			$transformer->getAndSetAdjacentItemsIds();
			$transformer->convertChildrenRecursively(0);
			$transformer->setNodesDepth();
		} catch (\Throwable $e) {
			Alert::error($e->getMessage())->flash();
			$errorFound = true;
		}
		
		// Check if error occurred
		if (!$errorFound) {
			$message = trans('admin.action_performed_successfully');
			Alert::success($message)->flash();
		}
		
		return redirect()->back();
	}
}