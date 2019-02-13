@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-home-page
@endsection

@section('page')

<div class="row">
    <div class="col-lg-10 col-md-11">
        <p class="trax-typo">
            @lang('trax-xapi-server::home.welcome_text')
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <trax-xapi-server-home-get-started></trax-xapi-server-home-get-started>
    </div>
    <div class="col-md-6">
        <trax-xapi-server-home-resources></trax-xapi-server-home-resources>
    </div>
</div>

<div class="row">
    <div class="col">
        <small>
            TRAX LRS version 1.0.0-rc.3 | <a href="http://fraysse.eu" target="_blank">&copy;2019 Sébastien FRAYSSE</a>
        </small>
    </div>
</div>


@endsection

@section('components')
    <script src="{{ mix('js/trax-xapi-server.js') }}"></script>
@endsection
