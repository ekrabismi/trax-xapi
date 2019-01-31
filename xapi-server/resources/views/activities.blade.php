@extends('trax-ui::layouts.int')

@section('body-class')
    trax-xapi-server-activities-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-xapi-server-activities></trax-xapi-server-activities>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-xapi-server.js') }}"></script>
@endsection
