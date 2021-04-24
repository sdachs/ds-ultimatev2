@extends('layouts.app')

@section('titel', $worldData->displayName(),': '.__('tool.animHistMap.title'))

@push('style')
    <link href="{{ asset('plugin/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <style>
        #map-popup {
            position: absolute;
            background-color: #ffffff90;
            padding: 5px;
            pointer-events: none;
        }
        #map-popup a {
            pointer-events: auto;
        }
    </style>
@endpush

@php
    if ($mode == 'edit'){
        $tabList = [
            'edit' => ['name' => __('tool.animHistMap.edit'), 'active' => true],
            'link' => ['name' => __('tool.animHistMap.links'), 'active' => false],
            'settings' => ['name' => __('tool.animHistMap.settings'), 'active' => false],
            'legend' => ['name' => __('tool.animHistMap.legend'), 'active' => false],
        ];
    }else{
        $tabList = [
            'legend' => ['name' => __('tool.animHistMap.legend'), 'active' => true],
        ];
    }
@endphp
@section('content')
    <div class="row justify-content-center">
        <!-- Titel für Tablet | PC -->
        <div class="col-12 p-lg-5 mx-auto my-1 text-center d-none d-lg-block">
            @auth
            <div class="col-2 position-absolute dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="ownedMaps" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ ucfirst(__('tool.animHistMap.fastSwitch')) }}
                </button>
                <div class="dropdown-menu" aria-labelledby="ownedMaps">
                    @foreach($ownMaps as $map)
                        <a class="dropdown-item" href="{{
                            route('tools.animHistMap.mode', [$map->id, 'edit', $map->edit_key])
                            }}">{{ $map->getTitle().' ['.$map->world->displayName().']' }}</a>
                    @endforeach
                </div>
            </div>
            @endauth
            <h1 class="font-weight-normal">{{ $wantedMap->getTitle().' ['.$worldData->displayName().']' }}</h1>
        </div>
        <!-- ENDE Titel für Tablet | PC -->
        <!-- Titel für Mobile Geräte -->
        <div class="p-lg-5 mx-auto my-1 text-center d-lg-none truncate">
            <h1 class="font-weight-normal">
                {{ $wantedMap->getTitle() }}
            </h1>
            <h4>
                {{ '['.$worldData->displayName().']' }}
            </h4>
        </div>
        <!-- ENDE Titel für Mobile Geräte -->
        <div class="col-12">
            @if($wantedMap->title === null && $mode == 'edit')
                <div class="card mt-2 p-3">
                    {{ __('tool.animHistMap.withoutTitle') }}
                </div>
            @endif
            <div class="card mt-2">
                <form id="mapEditForm" action="{{ route('tools.animHistMap.modePost', [$wantedMap->id, 'saveEdit', $wantedMap->edit_key]) }}" method="post">
                    @csrf
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($tabList as $key => $tab)
                            <li class="nav-item">
                                <a class="nav-link {{ ($tab['active'])?'active':'' }}" id="{{ $key }}-tab" data-toggle="tab" href="#{{ $key }}" role="tab" aria-controls="{{ $key }}" aria-selected="true">{{ $tab['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="card-body tab-content">
                        @foreach($tabList as $key => $tab)
                            @include('tools.animHistMap.map.'.$key, ['active' => $tab['active']])
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tools.animHistMap.modePost', [$wantedMap->id, 'render', $wantedMap->show_key]) }}" method="POST">
                        <div class="col-lg-12">
                            @csrf
                            <input type="submit" class="btn btn-sm btn-success float-right mb-3" value="{{ __('tool.animHistMap.renderNow')}}">
                        </div>
                    </form>
                    <div class="form-inline mb-2 col-lg-12">
                        <label for="previewSelect" class="col-lg-auto">{{ ucfirst(__('tool.animHistMap.previewSelect')) }}</label>
                        <input type="range" class="custom-range w-auto flex-lg-fill" min="0" max="{{ $histIdxs->count() - 1 }}" step="1" id="previewSelect" value="0" name="previewSelect">
                        <div id="previewSelectText" class="ml-4">{{ $histIdxs[0]->date }}</div>
                    </div>
                    <div id="previewContainer" class="text-center mt-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@if($mode == 'edit')
<script src="{{ asset('plugin/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script>
    $(function () {
        $('.colour-picker-map').on('colorpickerHide', store);
    });

    $('#mapEditForm').on('submit', function (e) {
        e.preventDefault();
        store();
    });

    var storing = false;
    var storeNeeded = false;
    function store() {
        if(storing) {
            storeNeeded = true;
            return;
        }
        storing = true;
        axios.post('{{ route('tools.animHistMap.modePost', [$wantedMap->id, 'save', $wantedMap->edit_key]) }}', $('#mapEditForm').serialize())
            .then((response) => {
                setTimeout(function() {
                    if(storeNeeded) {
                        storeNeeded = false
                        store();
                    }
                }, 400);
                storing = false;
                reloadMap();
            })
            .catch((error) => {

            });
    }
</script>
@endif
<script>
    var historyIds = [
        @foreach($histIdxs as $histIdx)
            [{{ $histIdx->id }}, "{{ $histIdx->date }}"],
        @endforeach
    ]
    
    $(function () {
        $('#previewSelect').on("input", function(slideEvt) {
            $("#previewSelectText").text(historyIds[parseInt(slideEvt.target.value)][1]);
            reloadMap();
        });
        $('#previewSelect').trigger("input");
    });
    
    function copy(type) {
        /* Get the text field */
        var copyText = $("#link-" + type);
        /* Select the text field */
        copyText.select();
        /* Copy the text inside the text field */
        document.execCommand("copy");
    }
    
    var reloading = false;
    var reloadNeeded = false;
    function reloadMap() {
        console.log("reloadMap #1", reloading, reloadNeeded);
        if(reloading) {
            reloadNeeded = true;
            return;
        }
        reloading = true;
        
        var elm = $('#previewContainer')[0];
        elm.style.widht = elm.clientWidth + "px";
        elm.style.height = elm.clientHeight + "px";
        
        var url = "{{ route('tools.animHistMap.preview', [$wantedMap->id, $wantedMap->show_key, 'histIdx','base64']) }}";
        url = url.replace("histIdx", historyIds[parseInt($("#previewSelect").val())][0]);
        
        $.ajax({
            type: "GET",
            url: url + "?" + Math.floor(Math.random() * 9000000 + 1000000),
            success: function(data){
                console.log("reloadMap #2", reloading, reloadNeeded);
                $('#previewContainer').html(
                    '<img id="previewImage" class="p-0" src="' + data + '" />'
                );

                setTimeout(function() {
                    console.log("reloadMap #3", reloading, reloadNeeded);
                    var elm = $('#previewContainer')[0];
                    elm.style.widht = "";
                    elm.style.height = "";
                    @if($mode == 'edit')
                        reloading = false;
                        if(reloadNeeded) {
                            reloadNeeded = false;
                            reloadMap();
                        }
                    @endif
                }, 500);
            },
        });
    };
</script>
@endpush
