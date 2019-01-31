@extends('trax-ui::layouts.int')

@section('body-class')
    trax-xapi-server-agents-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-xapi-server-agents></trax-xapi-server-agents>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-xapi-server.js') }}"></script>
@endsection
