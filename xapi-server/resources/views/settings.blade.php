@extends('trax-ui::layouts.int')

@section('body-class')
    trax-xapi-server-settings-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-xapi-server-settings debug="{{ config('app.debug') }}"></trax-xapi-server-settings>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-xapi-server.js') }}"></script>
@endsection
