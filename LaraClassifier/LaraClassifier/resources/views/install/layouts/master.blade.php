{{--
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
--}}
<!DOCTYPE html>
<html lang="{{ getLangTag(config('app.locale', 'en')) }}">
<head>
	<meta charset="{{ config('larapen.core.charset', 'utf-8') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow"/>
	<meta name="googlebot" content="noindex">
	<title>@yield('title')</title>
	
	@yield('before_styles')
	
	<link href="{{ url(mix('dist/public/styles.css')) }}" rel="stylesheet">
	
	@yield('after_styles')
	
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<script src="{{ url()->asset('assets/plugins/pace/0.4.17/pace.min.js') }}"></script>
</head>
<body>
<div id="wrapper">
	
	@section('header')
		@include('install.layouts.inc.header')
	@show
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-xl-12">
				<h1 class="text-center title-1 fw-bold mt-5 mb-3" style="text-transform: none;">
					{{ trans('messages.installer') }}
				</h1>
				
				@include('install.layouts.inc._steps')
				
				@if (isset($errors) && $errors->any())
					<div class="alert alert-danger mt-4">
						<ul class="list list-check">
							@foreach ($errors->all() as $error)
								<li>{!! $error !!}</li>
							@endforeach
						</ul>
					</div>
					<?php $paddingTopExists = true; ?>
				@endif
			</div>
		</div>
	</div>
	
	@include('common.spacer')
	<div class="main-container" style="min-height: 150px;">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-xl-12">
					<div class="inner-box">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
	</div>
	
	@section('footer')
		@include('install.layouts.inc.footer')
	@show
	
</div>

@yield('before_scripts')

<script>
	/* Init. vars */
	var siteUrl = '{{ url('/') }}';
	var languageCode = '{{ config('app.locale') }}';
	var countryCode = '{{ config('country.code', 0) }}';
	
	/* Init. Translation vars */
	var langLayout = {
		'hideMaxListItems': {
			'moreText': "{{ t('View More') }}",
			'lessText': "{{ t('View Less') }}"
		}
	};
</script>

<script src="{{ url(mix('dist/public/scripts.js')) }}"></script>
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
	<script src="{{ url('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
@endif

<script>
	$(document).ready(function () {
		/* Select Boxes */
		$(".selecter").select2({
			language: '{{ config('app.locale', 'en') }}',
			dropdownAutoWidth: 'true'
		});
	});
</script>

@yield('after_scripts')

</body>
</html>
