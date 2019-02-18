@extends('trax-ui::layouts.int')

@section('body-class')
    trax-xapi-server-statements-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-xapi-server-statements></trax-xapi-server-statements>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-xapi-server.js') }}"></script>
@endsection
