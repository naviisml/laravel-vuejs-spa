@extends('layouts.default')

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-start-4 col-md-end-8">
				<img src="/assets/imaging/emoji/emoji_1f600.svg" />
				<div class="text-center py-3">
					<h2>@yield('code', 'Welcome')</h2>
					<p class="text-muted">@yield('message', 'You are being logged in...')</p>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script>
    console.info('Post authentication')
    window.opener.postMessage({ token: "{{ $token }}" }, "{{ url('/') }}")
    window.close()
</script>
@endsection
