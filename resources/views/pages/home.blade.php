@extends('layouts.default')

@section('body')
	<div id="app"></div>
@endsection

@section('javascript')
	<script src="/assets/js/fontawesome.js"></script>
	<script src="{{ mix('/dist/js/app.js') }}"></script>
@endsection
