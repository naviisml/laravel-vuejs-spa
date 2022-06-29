@extends('layouts.default')

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-start-4 col-md-end-8">
				<img src="/assets/imaging/emoji/emoji_1f627.svg" />
				<div class="text-center py-3">
					<h2>@yield('code', '404')</h2>
					<p class="text-muted">@yield('message', 'Not Found')</p>
				</div>
			</div>
		</div>
	</div>
@endsection
