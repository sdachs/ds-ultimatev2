@extends('layouts.app')

@section('titel', $worldData->displayName(),': '.__('tool.map.title'))

@section('style')
    <link href="{{ asset('plugin/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugin/select2/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugin/drawerJS/drawerJs.min.css') }}" rel="stylesheet" />
    <style>
        .select2-container{
            width: 1%!important;
            flex: 1 1 auto;
        }
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
@stop

@section('content')
    <?php
        function generateHTMLSelector($type, $id, $defaultContent=null) {
            if($type == 'ally' || $type == 'player') {
                if($defaultContent != null) {
                    $defName = $defaultContent['name'];
                    $defCol = $defaultContent['colour'];
                    $defShowText = ($defaultContent['text'])?('checked="checked"'):("");
                    $defHighLight = ($defaultContent['highlight'])?('checked="checked"'):("");
                } else {
                    $defName = '';
                    $defCol = 'FFFFFF';
                    $defShowText = "";
                    $defHighLight = "";
                }?>
                <div id="{{ "$type-mark-$id-div" }}" class="input-group mb-2 mr-sm-2">
                    <div class="colour-picker-map input-group-prepend">
                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                        <input name="{{ "mark[$type][$id][colour]" }}" type="hidden" value="{{ $defCol }}"/>
                    </div>
                    <select id="{{ "$type-mark-$id-id" }}" name="{{ "mark[$type][$id][id]" }}"
                        class="form-control mr-1 data-input-map select2-{{ $type }} select2-single">
                        @if($defaultContent != null)
                        <option value="{{ $defaultContent['id'] }}" selected="selected">{{ $defaultContent['name'] }}</option>
                        @endif
                    </select>
                    <div class="form-check ml-2 mt-2">
                        <input name="{{ "mark[$type][$id][textHere]" }}" type="hidden" value="true" />
                        <input type="checkbox" class="form-check-input position-static showText-{{ $type }} showTextBox" name="{{ "mark[$type][$id][text]" }}"
                               data-toggle="tooltip" title="{{ ucfirst(__('tool.map.showText')) }}" {{$defShowText}}>
                    </div>
                    <div class="form-check ml-2 mt-2">
                        <input name="{{ "mark[$type][$id][hLightHere]" }}" type="hidden" value="true" />
                        <input type="checkbox" class="form-check-input position-static highlight-{{ $type }} showTextBox" name="{{ "mark[$type][$id][hLight]" }}"
                               data-toggle="tooltip" title="{{ ucfirst(__('tool.map.highlight')) }}" {{$defHighLight}}>
                    </div>
                </div>
                <?php
            } else if($type == 'village') {
                if($defaultContent != null) {
                    $defX = $defaultContent['x'];
                    $defY = $defaultContent['y'];
                    $defCol = $defaultContent['colour'];
                    $defHighLight = ($defaultContent['highlight'])?('checked="checked"'):("");
                } else {
                    $defX = '';
                    $defY = '';
                    $defCol = 'FFFFFF';
                    $defHighLight = "";
                }?>
                <div id="{{ "$type-mark-$id-div" }}" class="input-group mb-2 mr-sm-2">
                    <div class="colour-picker-map input-group-prepend">
                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                        <input name="{{ "mark[$type][$id][colour]" }}" type="hidden" value="{{ $defCol }}"/>
                    </div>
                    <input id="{{ "$type-mark-$id-id" }}" name="{{ "mark[$type][$id][id]" }}" type="hidden"/>
                    <input id="{{ "$type-mark-$id-x" }}" name="{{ "mark[$type][$id][x]" }}" class="form-control mr-1 checked-data-input-map data-input-map" placeholder="500" type="text" value="{{ $defX }}"/>|
                    <input id="{{ "$type-mark-$id-y" }}" name="{{ "mark[$type][$id][y]" }}" class="form-control ml-1 checked-data-input-map data-input-map" placeholder="500" type="text" value="{{ $defY }}"/>
                    <div class="form-check ml-2 mt-2">
                        <input name="{{ "mark[$type][$id][hLightHere]" }}" type="hidden" value="true" />
                        <input type="checkbox" class="form-check-input position-static highlight-{{ $type }} showTextBox" name="{{ "mark[$type][$id][hLight]" }}"
                               data-toggle="tooltip" title="{{ ucfirst(__('tool.map.highlight')) }}" {{$defHighLight}}>
                    </div>
                </div>
                <?php
            }
        }
    ?>
    <div class="row justify-content-center">
        <!-- Titel für Tablet | PC -->
        <div class="col-12 p-lg-5 mx-auto my-1 text-center d-none d-lg-block">
            @auth
            <div class="col-2 position-absolute dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="ownedMaps" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ ucfirst(__('tool.map.fastSwitch')) }}
                </button>
                <div class="dropdown-menu" aria-labelledby="ownedMaps">
                    @foreach($ownMaps as $map)
                        <a class="dropdown-item" href="{{ 
                            route('tools.mapToolMode', [$map->id, 'edit', $map->edit_key])
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
        @if($mode == 'edit')
        <div class="col-12">
            @if($wantedMap->title === null)
            <div class="card mt-2 p-3">
                {{ __('tool.map.withoutTitle') }}
            </div>
            @endif
            <div class="card mt-2">
                <form id="mapEditForm">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="true">{{ ucfirst(__('tool.map.edit')) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="drawing-tab" data-toggle="tab" href="#drawing" role="tab" aria-controls="drawing" aria-selected="false">{{ ucfirst(__('tool.map.drawing')) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">{{ ucfirst(__('tool.map.settings')) }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="link-tab" data-toggle="tab" href="#link" role="tab" aria-controls="link" aria-selected="false">{{ ucfirst(__('tool.map.links')) }}</a>
                        </li>
                    </ul>
                    <div class="card-body tab-content">
                        <div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                            <div class="col-12 text-center">
                                <b id="title-show" class="h3 card-title">{{ ($wantedMap->title === null)? __('ui.noTitle'): $wantedMap->title }}</b>
                                <input id="title-input" onfocus="this.select();" class="form-control mb-3" style="display:none" name="title" type="text">
                                <a id="title-edit" onclick="titleEdit()" style="cursor:pointer;"><i class="far fa-edit text-muted h5 ml-2"></i></a>
                                <a id="title-save" onclick="titleSave()" style="cursor:pointer; display:none"><i class="far fa-save text-muted h5 ml-2"></i></a>
                                <hr>
                            </div>
                            <div class="row pt-3">
                                @foreach(['ally', 'player', 'village'] as $type)
                                    <div id="main-{{$type}}" class="col-lg-4">
                                        {{ ucfirst(__('tool.map.'.$type)) }}<br>
                                        @if($type != 'village')
                                            <div class="form-check form-check-inline float-right mr-0">
                                                <label class="form-check-label mr-2" for="showTextAll-{{ $type }}">{{ ucfirst(__('tool.map.showAllText')) }}</label>
                                                /
                                                <label class="form-check-label ml-2 mr-2" for="highlightAll-{{ $type }}">{{ ucfirst(__('tool.map.highlightAll')) }}</label>
                                                <input class="form-check-input change-all showTextBox mr-2" type="checkbox" aria-for="showText-{{ $type }}"
                                                       id="showTextAll-{{ $type }}" data-toggle="tooltip" title="{{ ucfirst(__('tool.map.showAllText')) }}">
                                                <input class="form-check-input change-all highlightBox ml-2" type="checkbox" aria-for="highlight-{{ $type }}"
                                                       id="highlightAll-{{ $type }}" data-toggle="tooltip" title="{{ ucfirst(__('tool.map.highlightAll')) }}">
                                            </div>
                                        @else
                                            <div class="form-check form-check-inline float-right mr-0">
                                                <label class="form-check-label mr-2" for="highlightAll-{{ $type }}">{{ ucfirst(__('tool.map.highlightAll')) }}</label>
                                                <input class="form-check-input change-all highlightBox ml-2" type="checkbox" aria-for="highlight-{{ $type }}"
                                                       id="highlightAll-{{ $type }}" data-toggle="tooltip" title="{{ ucfirst(__('tool.map.highlightAll')) }}">
                                            </div>
                                        @endif
                                        <br>
                                        @foreach($defaults[$type] as $num=>$defValues)
                                            {!! generateHTMLSelector($type, $num, $defValues) !!}
                                        @endforeach
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <input type="submit" class="btn btn-sm btn-success float-right">
                                </div>
                            </div>
                            <div id="model" style="display: none">
                                @foreach(['ally', 'player', 'village'] as $type)
                                    <textarea id="{{ $type }}-mark-model-area">
                                        {!! generateHTMLSelector($type, "model") !!}
                                    </textarea>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="drawing" role="tabpanel" aria-labelledby="drawing-tab">
                            <div class="row pt-3">
                                <div class="form-group float-left" style="margin-left: calc(50% - 500px);">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteDrawing()">{{ ucfirst(__('tool.map.deleteDrawing')) }}</button>
                                </div>
                                <div class="col-12 text-center">
                                    <img id="canvas-bg-img" src="">
                                    <div id="canvas-container" style="position: absolute; left: 0px; top: 0px; margin-left: calc(50% - 500px);">
                                        <div id="canvas-editor"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="link" role="tabpanel" aria-labelledby="link-tab">
                            <div class="row pt-3">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-2">{{ ucfirst(__('tool.map.editLink')) }}</label>
                                        <div class="col-1">
                                            <a class="btn btn-primary btn-sm" onclick="copy('edit')">{{ ucfirst(__('tool.map.copy')) }}</a>
                                        </div>
                                        <div class="col-9">
                                            <input id="link-edit" type="text" class="form-control-plaintext form-control-sm disabled" value="{{ route('tools.mapToolMode', [$wantedMap->id, 'edit', $wantedMap->edit_key]) }}" />
                                            <small class="form-control-feedback">{{ ucfirst(__('tool.map.editLinkDesc')) }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-2">{{ ucfirst(__('tool.map.showLink')) }}</label>
                                        <div class="col-1">
                                            <a class="btn btn-primary btn-sm" onclick="copy('show')">{{ ucfirst(__('tool.map.copy')) }}</a>
                                        </div>
                                        <div class="col-9">
                                            <input id="link-show" type="text" class="form-control-plaintext form-control-sm disabled" value="{{ route('tools.mapToolMode', [$wantedMap->id, 'show', $wantedMap->show_key]) }}" />
                                            <small class="form-control-feedback">{{ ucfirst(__('tool.map.showLinkDesc')) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            <div id='default-background-div' class='col-12 input-group mb-2 mr-sm-2'>
                                <div class='colour-picker-map input-group-prepend'>
                                    <span class='input-group-text colorpicker-input-addon'><i></i></span>
                                    <input name='default[background]' id="bg-colour" type='hidden' value='{{ $wantedMap->getBackgroundColour() }}'/>
                                </div>
                                <label class='form-control'>{{ __('tool.map.defaultBackground') }}</label>
                            </div>
                            <div class="form-inline mb-2">
                                <div class="form-check col-lg-auto ml-auto">
                                    <input id="checkbox-show-player-hid" name="showPlayerHere" type="hidden" value="true" />
                                    <input id="checkbox-show-player" name="showPlayer" type="checkbox" class="form-check-input" {{ ($wantedMap->playerEnabled())?('checked="checked"'):('') }}/>
                                    <label class="form-check-label" for="checkbox-show-player">{{ __('tool.map.showPlayer') }}</label>
                                </div>
                                <div id='default-player-div' class='col-lg-9 input-group'>
                                    <div class='colour-picker-map input-group-prepend'>
                                        <span class='input-group-text colorpicker-input-addon'><i></i></span>
                                        <input name='default[player]' id="player-colour" type='hidden' value='{{ $wantedMap->getDefPlayerColour() }}'/>
                                    </div>
                                    <label class='form-control'>{{ __('tool.map.defaultPlayer') }}</label>
                                </div>
                            </div>
                            <div class="form-inline mb-2">
                                <div class="form-check col-lg-auto ml-auto">
                                    <input id="checkbox-show-barbarian-hid" name="showBarbarianHere" type="hidden" value="true" />
                                    <input id="checkbox-show-barbarian" name="showBarbarian" type="checkbox" class="form-check-input" {{ ($wantedMap->barbarianEnabled())?('checked="checked"'):('') }}/>
                                    <label class="form-check-label" for="checkbox-show-barbarian">{{ __('tool.map.showBarbarian') }}</label>
                                </div>
                                <div id='default-barbarian-div' class='col-lg-9 input-group'>
                                    <div class='colour-picker-map input-group-prepend'>
                                        <span class='input-group-text colorpicker-input-addon'><i></i></span>
                                        <input name='default[barbarian]' type='hidden' id="barbarian-colour" value='{{ $wantedMap->getDefBarbarianColour() }}'/>
                                    </div>
                                    <label class='form-control'>{{ __('tool.map.defaultBarbarian') }}</label>
                                </div>
                            </div>
                            <div class="form-inline mb-2">
                                <div class="col-lg-6 input-group">
                                    <label for="map-zoom-value" class="col-lg-4">{{ __('tool.map.zoom') }}</label>
                                    <select class="form-control col-lg-2" id="map-zoom-value" name="zoomValue">
                                        <option value="1000"{{ ($mapDimensions['w'] == 1000)?(' selected="selected"'):('') }}>0</option>
                                        <option value="599"{{ ($mapDimensions['w'] == 599)?(' selected="selected"'):('') }}>1</option>
                                        <option value="359"{{ ($mapDimensions['w'] == 359)?(' selected="selected"'):('') }}>2</option>
                                        <option value="215"{{ ($mapDimensions['w'] == 215)?(' selected="selected"'):('') }}>3</option>
                                        <option value="129"{{ ($mapDimensions['w'] == 129)?(' selected="selected"'):('') }}>4</option>
                                        <option value="77"{{ ($mapDimensions['w'] == 77)?(' selected="selected"'):('') }}>5</option>
                                        <option value="46"{{ ($mapDimensions['w'] == 46)?(' selected="selected"'):('') }}>6</option>
                                        <option value="28"{{ ($mapDimensions['w'] == 28)?(' selected="selected"'):('') }}>7</option>
                                        <option value="16"{{ ($mapDimensions['w'] == 16)?(' selected="selected"'):('') }}>8</option>
                                        <option value="10"{{ ($mapDimensions['w'] == 10)?(' selected="selected"'):('') }}>9</option>
                                    </select>
                                </div>
                                <div id="center-pos-div" class="input-group col-lg-6 mb-2">
                                    <label for="center-pos-x" class="col-lg-4">{{ __('tool.map.center') }}</label>
                                    <input id="center-pos-x" name="centerX" class="form-control mr-1" placeholder="500" type="text" value="{{ $mapDimensions['cx'] }}"/>|
                                    <input id="center-pos-y" name="centerY" class="form-control ml-1" placeholder="500" type="text" value="{{ $mapDimensions['cy'] }}"/>
                                </div>
                            </div>
                            <div class="form-inline mb-2 col-lg-6">
                                <label for="markerFactor" class="col-lg-auto">{{ ucfirst(__('tool.map.markerFactor')) }}</label>
                                <input type="range" class="custom-range w-auto flex-lg-fill" min="0" max="0.4" step="0.01" id="markerFactor" value="{{ $wantedMap->makerFactor }}" name="markerFactor">
                                <div id="markerFactorText" class="ml-4">{{ intval($wantedMap->markerFactor*100) }}%</div>
                            </div>
                            <div class="form-inline mb-2">
                                <div class="form-check col-lg-auto">
                                    <input id="checkbox-continent-numbers-hid" name="continentNumbersHere" type="hidden" value="true" />
                                    <input id="checkbox-continent-numbers" name="continentNumbers" type="checkbox" class="form-check-input" {{ ($wantedMap->continentNumbersEnabled())?('checked="checked"'):('') }}/>
                                    <label class="form-check-label" for="checkbox-continent-numbers">{{ __('tool.map.showContinentNumbers') }}</label>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <input type="submit" class="btn btn-sm btn-success">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        <div class="col-12 mt-2">
            <div class="card">
                @auth
                    @if($wantedMap->user_id != Auth::user()->id)
                        @if($wantedMap->follows()->where('user_id', Auth::user()->id)->count() > 0)
                            <div class="float-right position-absolute" style="right: 10px; top: 10px"><i id="follow-icon" style="cursor:pointer; text-shadow: 0 0 15px #000;" onclick="changeFollow()" class="fas fa-star h4 text-warning"></i></div>
                        @else
                            <div class="float-right position-absolute" style="right: 10px; top: 10px"><i id="follow-icon" style="cursor:pointer" onclick="changeFollow()" class="far text-muted fa-star h4 text-muted"></i></div>
                        @endif
                    @endif
                @endauth
                <ul class="nav nav-tabs" id="mapshowtabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active map-show-tab" id="size-1-tab" data-toggle="tab" href="#size-1" role="tab" aria-controls="size-1" aria-selected="true">{{ '1000x1000' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link map-show-tab" id="size-2-tab" data-toggle="tab" role="tab" href="#size-2" aria-controls="size-2" aria-selected="false">{{ '700x700' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link map-show-tab" id="size-3-tab" data-toggle="tab" role="tab" href="#size-3" aria-controls="size-3" aria-selected="false">{{ '500x500' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link map-show-tab" id="size-4-tab" data-toggle="tab" role="tab" href="#size-4" aria-controls="size-4" aria-selected="false">{{ '200x200' }}</a>
                    </li>
                </ul>
                <div class="card-body tab-content">
                    <div class="tab-pane fade show active map-show-content text-center" id="size-1" role="tabpanel" aria-labelledby="size-1-tab"></div>
                    <div class="tab-pane fade map-show-content text-center" id="size-2" role="tabpanel" aria-labelledby="size-2-tab"></div>
                    <div class="tab-pane fade map-show-content text-center" id="size-3" role="tabpanel" aria-labelledby="size-3-tab"></div>
                    <div class="tab-pane fade map-show-content text-center" id="size-4" role="tabpanel" aria-labelledby="size-4-tab"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@if($mode == 'edit')
<script src="{{ asset('plugin/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('plugin/select2/select2.full.min.js') }}"></script>
<script>
    function titleEdit() {
        var input = $('#title-input');
        var title = $('#title-show');
        var edit = $('#title-edit');
        var save = $('#title-save');
        var t = (title.html() === '{{ __('ui.noTitle') }}')? '': title.html();
        title.hide();
        edit.hide();
        input.val(t).show().focus();
        save.show();
    }

    function titleSave() {
        var input = $('#title-input');
        var title = $('#title-show');
        var edit = $('#title-edit');
        var save = $('#title-save');
        var t = (input.val() === '')? '{{ __('ui.noTitle') }}': input.val();
        axios.post('{{ route('index') }}/tools/map/{{ $wantedMap->id }}/title/{{ $wantedMap->edit_key }}/' + t, {
        })
            .then((response) => {
                input.hide();
                save.hide();
                title.html(t).show();
                edit.show();
            })
            .catch((error) => {
                console.log(error);
            });
    }

    function copy(type) {
        /* Get the text field */
        var copyText = $("#link-" + type);
        /* Select the text field */
        copyText.select();
        /* Copy the text inside the text field */
        document.execCommand("copy");
    }

    var maxIndex = {
        ally:{{ (count($defaults['ally']) - 1) }},
        player:{{ (count($defaults['player']) - 1) }},
        village:{{ (count($defaults['village']) - 1) }}
    };

    $(function () {
        $('.data-input-map').each(function() {
            if(this.value != null && this.value != "") {
                addNewParts(this, null);
            }
        });

        $('.checked-data-input-map').each(function() {
            if(this.value != null && this.value != "" && this.id.split('-')[3] != 'x') {
                checkPart(this, null);
            }
        });
        
        $('.change-all').change(function(e) {
            $('.'+this.attributes['aria-for'].nodeValue).prop('checked', this.checked);
        });
        addCustomLibs(null);

        $('#title-input').on("keypress keyup blur",function (event) {
            if (event.keyCode == 13) {
                titleSave();
            }
        });
    });

    /**
     * Function to dynamically generate new Input fields as the user fills them
     * @param Event e
     */
    function addNewParts(that, e) {
        var parts = that.id.split("-");

        if(parts[2] == maxIndex[parts[0]]) {
            maxIndex[parts[0]]++;
            var newElm = $('#'+parts[0]+'-mark-model-area')[0].value;
            $('#main-'+parts[0]).append(newElm.replace(/model/gi, maxIndex[parts[0]]));
            var par = $('#'+parts[0]+'-mark-'+maxIndex[parts[0]]+'-div');
            addCustomLibs(par);
        }
    }

    /**
     * Function to check if the input in a given field is valid.
     * If so it adds the id to the hidden input field
     * @param Event e
     */
    function checkPart(that, e) {
        var parts = that.id.split("-");

        switch(parts[0]) {
            case 'village':
                checkVillage(that, e);
                break;
        }     
    }

    function checkVillage(that, e) {
        var parts = that.id.split("-");
        var x = $('#village-mark-'+parts[2]+'-x').val();
        var y = $('#village-mark-'+parts[2]+'-y').val();
        axios.get('{{ route('index') }}/api/{{ $worldData->server->code }}/{{ $worldData->name }}/villageCoords/'+ x + '/' + y, {
        })
            .then((response) =>{
                const data = response.data.data;
                $('#village-mark-'+parts[2]+'-x').removeClass('is-invalid').addClass('is-valid');
                $('#village-mark-'+parts[2]+'-y').removeClass('is-invalid').addClass('is-valid');
                $('#'+parts[0]+'-'+parts[1]+'-'+parts[2]+'-id').val(data['villageID']);
            })
            .catch((error) =>{
                $('#village-mark-'+parts[2]+'-x').removeClass('is-invalid').addClass('is-invalid');
                $('#village-mark-'+parts[2]+'-y').removeClass('is-invalid').addClass('is-invalid');
                $('#'+parts[0]+'-'+parts[1]+'-'+parts[2]+'-id').val('');
            });
    }

    function addCustomLibs(context) {
        context = (context)?($(context)):($(document));

        $('.select2-player', context).select2({
            ajax: {
                url: '{{ route("api.searchPlayerByName", [$worldData->server->code, $worldData->name]) }}',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                },
                delay: 250
            },
            allowClear: true,
            placeholder: '{{ ucfirst(__('tool.map.playerSelectPlaceholder')) }}',
            theme: "bootstrap4"
        });
        $('.select2-ally', context).select2({
            ajax: {
                url: '{{ route("api.searchAllyByName", [$worldData->server->code, $worldData->name]) }}',
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                },
                delay: 250
            },
            allowClear: true,
            placeholder: '{{ ucfirst(__('tool.map.allySelectPlaceholder')) }}',
            theme: "bootstrap4"
        });

        $('.colour-picker-map', context).colorpicker({
            useHashPrefix: false,
            extensions: [{
                name: 'swatches',
                options: {
                    colors: {
                        'c11': '#ffffff', 'c12': '#eeece1', 'c13': '#d99694', 'c14': '#c0504d', 'c15': '#f79646', 'c16': '#ffff00', 'c17': '#9bbb59',
                        'c21': '#4bacc6', 'c22': '#548dd4', 'c23': '#1f497d', 'c24': '#8064a2', 'c25': '#f926e5', 'c26': '#7f6000', 'c27': '#000000',
                    },
                    namesAsValues: false
                }
            }]
        });

        $('.data-input-map').change(function() {
            if(this.value != null && this.value != "") {
                addNewParts(this, null);
            }
        });

        $('.checked-data-input-map').change(function() {
            if(this.value != null && this.value != "") {
                checkPart(this, null);
            }
        });

        $('.data-input-map').change(store);
        $('.colour-picker-map').on('colorpickerHide', store);
        $('#checkbox-show-player').change(store);
        $('#checkbox-show-barbarian').change(store);
        $('#checkbox-continent-numbers').change(store);
        $('#map-zoom-value').change(store);
        $('#center-pos-x').change(store);
        $('#center-pos-y').change(store);
        $('.showTextBox').change(store);
        $('.highlightBox').change(store);
        $('#markerFactor').change(store);
        $('#markerFactor').on("input", function(slideEvt) {
            $("#markerFactorText").text(parseInt(slideEvt.target.value*100) + "%");
        });
    }

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
        axios.post('{{ route('tools.mapToolMode', [$wantedMap->id, 'save', $wantedMap->edit_key]) }}', $('#mapEditForm').serialize())
            .then((response) => {
                mapDimensions = [
                    response.data.xs,
                    response.data.ys,
                    response.data.w,
                    response.data.h,
                ];
                
                setTimeout(function() {
                    if(storeNeeded) {
                        storeNeeded = false
                        store();
                    }
                }, 400);
                storing = false;
                reloadMap();
                reloadDrawerBackground();
            })
            .catch((error) => {

            });
    }
    
    var reloading = false;
    var reloadNeeded = false;
    function reloadMap() {
        if(reloading) {
            reloadNeeded = true;
            return;
        }
        reloading = true;
        var elm = $('.active.map-show-content')[0];
        elm.style.widht = elm.clientWidth + "px";
        elm.style.height = elm.clientHeight + "px";
        $('.map-show-content').empty();
        $('.active.map-show-tab').trigger('click');
    }
</script>
<script src="{{ asset('plugin/drawerJS/drawerJs.standalone.min.js') }}"></script>
<script>
    var drawerPlugins = [
        'Pencil',
        'Eraser',
        'Text',
        'Line',
        'ArrowOneSide',
        'ArrowTwoSide',
        'Triangle',
        'Rectangle',
        'Circle',
        'Polygon',

        'Color',
        'ShapeBorder',
        'BrushSize',
        'OpacityOption',

        'LineWidth',
        'StrokeWidth',

        'ShapeContextMenu',

        'TextLineHeight',

        'TextFontFamily',
        'TextFontSize',
        'TextFontWeight',
        'TextFontStyle',
        'TextDecoration',
        'TextColor',
        'TextBackgroundColor',
    ];
    var drawer_local = {
      'Add Drawer': '{{ __('tool.map.drawer.general.addDrawer') }}',
      'Insert Drawer': '{{ __('tool.map.drawer.general.insertDrawer') }}',
      'Insert': '{{ __('tool.map.drawer.general.insert') }}',
      'Free drawing mode': '{{ __('tool.map.drawer.general.freeDrawing') }}',
      'SimpleWhiteEraser': '{{ __('tool.map.drawer.general.simpleEraser') }}',
      'Eraser': '{{ __('tool.map.drawer.general.eraser') }}',
      'Delete this canvas': '{{ __('tool.map.drawer.general.deleteCanvas') }}',
      'Are you sure want to delete this canvas?': '{{ __('tool.map.drawer.general.deleteCanvasConfirm') }}',

      // canvas properties popup
      'Size (px)': '{{ __('tool.map.drawer.canvas.size') }}',
      'Position': '{{ __('tool.map.drawer.canvas.position') }}',
      'Inline': '{{ __('tool.map.drawer.canvas.inline') }}',
      'Left': '{{ __('tool.map.drawer.canvas.left') }}',
      'Center': '{{ __('tool.map.drawer.canvas.center') }}',
      'Right': '{{ __('tool.map.drawer.canvas.right') }}',
      'Floating': '{{ __('tool.map.drawer.canvas.floating') }}',
      'Canvas properties': '{{ __('tool.map.drawer.canvas.canvasProp') }}',
      'Background': '{{ __('tool.map.drawer.canvas.background') }}',
      'transparent': '{{ __('tool.map.drawer.canvas.transparent') }}',
      'Cancel': '{{ __('tool.map.drawer.canvas.cancel') }}',
      'Save': '{{ __('tool.map.drawer.canvas.save') }}',

      // Fullscreen plugin
      'Enter fullscreen mode': '{{ __('tool.map.drawer.fullscreen.enter') }}',
      'Exit fullscreen mode': '{{ __('tool.map.drawer.fullscreen.exit') }}',

      // shape context menu plugin
      'Bring forward': '{{ __('tool.map.drawer.shape.bringForward') }}',
      'Send backwards': '{{ __('tool.map.drawer.shape.bringBackwards') }}',
      'Bring to front': '{{ __('tool.map.drawer.shape.bringFront') }}',
      'Send to back': '{{ __('tool.map.drawer.shape.bringBack') }}',
      'Duplicate': '{{ __('tool.map.drawer.shape.duplicate') }}',
      'Remove': '{{ __('tool.map.drawer.shape.remove') }}',

      // brush size plugin
      'Size:': '{{ __('tool.map.drawer.brush.size') }}',

      // colorpicker plugin
      'Fill:': '{{ __('tool.map.drawer.color.fill') }}',
      'Transparent': '{{ __('tool.map.drawer.color.transparent') }}',

      // shape border plugin
      'Border:': '{{ __('tool.map.drawer.border.border') }}',
      'None': '{{ __('tool.map.drawer.border.none') }}',

      // arrow plugin
      'Draw an arrow': '{{ __('tool.map.drawer.arrow.drawSingle') }}',
      'Draw a two-sided arrow': '{{ __('tool.map.drawer.arrow.drawTwo') }}',
      'Lines and arrows': '{{ __('tool.map.drawer.arrow.tooltip') }}',

      // circle plugin
      'Draw a circle': '{{ __('tool.map.drawer.circle.tooltip') }}',

      // line plugin
      'Draw a line': '{{ __('tool.map.drawer.line.tooltip') }}',

      // rectangle plugin
      'Draw a rectangle': '{{ __('tool.map.drawer.rect.tooltip') }}',

      // triangle plugin
      'Draw a triangle': '{{ __('tool.map.drawer.triangle.tooltip') }}',

      // polygon plugin
      'Draw a Polygon': '{{ __('tool.map.drawer.polygon.tooltip') }}',
      'Stop drawing a polygon': '{{ __('tool.map.drawer.polygon.stop') }}',
      'Click to start a new line': '{{ __('tool.map.drawer.polygon.newLine') }}',

      // text plugin
      'Draw a text': '{{ __('tool.map.drawer.text.tooltip') }}',
      'Click to place a text': '{{ __('tool.map.drawer.text.newText') }}',
      'Font:': '{{ __('tool.map.drawer.text.font') }}',

      // movable floating mode plugin
      'Move canvas': '{{ __('tool.map.drawer.moveable.moveCanvas') }}',

      // base shape
      'Click to start drawing a ': '{{ __('tool.map.drawer.base.tooltip') }}'
    };


    var drawer;
    $(function () {
        drawer = new DrawerJs.Drawer(null, {
            texts: drawer_local,
            plugins: drawerPlugins,
            corePlugins: null,
            basePath: '/plugin/drawerJS/',
            transparentBackground: true,
            defaultActivePlugin : { name : 'Pencil', mode : 'lastUsed'},
            contentConfig: {
                saveAfterInactiveSec: 10,
                saveInHtml: false,
                saveCanvasData: function(canvasId, canvasData) {
                    saveCanvas("object", canvasData);
                    saveCanvas("image", drawer.api.getCanvasAsImage());
                },
                loadCanvasData: function(canvasId) {
                    return canvasDataObject;
;
                },
                saveImageData: function(canvasId, imageData) {
                },
            },
            borderCss: 'none',
            borderCssEditMode: 'none',
            defaultImageUrl: '{{ route('api.map.options.sized', [$wantedMap->id, $wantedMap->show_key, 'pureDrawing','1000', '1000', 'png']) }}',
            toolbars: {
                // drawing tools toolbar config
                drawingTools : {
                    // one of [left, right, top, bottom, custom]
                    position : 'left',
                    // one of [scrollable, multiline]
                    compactType : 'multiline',
                },

                // active tool options toolbar config
                toolOptions : {
                    position : 'top',
                    compactType : 'multiline',
                },

                // drawer settings toolbar config
                settings : {
                    hidden : true,
                    position : 'top',
                    compactType : 'multiline',
                },
            },
        }, 1000, 1000);
        $('#canvas-editor').append(drawer.getHtml());
        drawer.onInsert();
        
        axios.get('{{ route('tools.mapToolMode', [$wantedMap->id, 'getCanvas', $wantedMap->edit_key]) }}')
            .then((response) => {
                canvasDataObject = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        
    });
    var canvasDataObject = "";
    
    function saveCanvas(type, data) {
        var convertedData = "type="+type;
        convertedData += "&data="+encodeURIComponent(data);
        axios.post('{{ route('tools.mapToolMode', [$wantedMap->id, 'saveCanvas', $wantedMap->edit_key]) }}', convertedData)
            .then((response) => {
                if(type == 'image') {
                    reloadMap();
                }
            })
            .catch((error) => {
                console.log(error);
                alert("Could not save Drawings");
            });
    }
    
    
    $('#drawing-tab').click(function (e) {
        if($('#canvas-bg-img')[0].currentSrc != "") return;
        
        var imgSrc = "{{ route('api.map.options.sized', [$wantedMap->id, $wantedMap->show_key, 'noDrawing','1000', '1000', 'png']) }}";
        imgSrc += "?" + Math.floor(Math.random() * 9000000 + 1000000);
        $('#canvas-bg-img')[0].src = imgSrc;
    });
    
    function deleteDrawing(e) {
        saveCanvas("image", "");
        saveCanvas("object", "");
        reloadMap();
        drawer.api.startEditing();
        drawer.api.loadCanvasFromData('{"objects":[],"background":""}');
        drawer.api.stopEditing();
    }
    
    function reloadDrawerBackground() {
        $('#canvas-bg-img')[0].src = "";
    }
</script>
@endif
<script>
    var sizeRoutes = {
        "size-1": [
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '1000', '1000', 'base64']) }}",
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '1000', '1000', 'png']) }}",
            1000, 1000
        ],
        "size-2": [
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '700', '700', 'base64']) }}",
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '700', '700', 'png']) }}",
            700, 700
        ],
        "size-3": [
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '500', '500', 'base64']) }}",
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '500', '500', 'png']) }}",
            500, 500
        ],
        "size-4": [
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '200', '200', 'base64']) }}",
            "{{ route('api.map.show.sized', [$wantedMap->id, $wantedMap->show_key, '200', '200', 'png']) }}",
            200, 200
        ],
    };
    $('.map-show-tab').click(function (e) {
        var targetID = this.attributes['aria-controls'].nodeValue;
        if($('#'+targetID)[0].innerHTML.length > 0) return;
        
        $.ajax({
            type: "GET",
            url: sizeRoutes[targetID][0] + "?" + Math.floor(Math.random() * 9000000 + 1000000),
            success: function(data){
                $('#'+targetID).html(
                    '<div class="form-group row">' +
                        '<label class="control-label col-md-2">{{ ucfirst(__('tool.map.forumLink')) }}</label>' +
                        '<div class="col-1">' +
                            '<a class="btn btn-primary btn-sm" onclick="copy(\''+targetID+'\')">{{ ucfirst(__('tool.map.copy')) }}</a>' +
                        '</div>' +
                        '<div class="col-9">' +
                            '<input id="link-'+targetID+'" type="text" class="border form-control-plaintext form-control-sm disabled" value="[url={{ route('tools.mapToolMode', [$wantedMap->id, 'show', $wantedMap->show_key]) }}][img]'+sizeRoutes[targetID][1]+'[/img][/url]" />' +
                            '<small class="form-control-feedback">{{ ucfirst(__('tool.map.forumLinkDesc')) }}</small>' +
                        '</div>' +
                    '</div>' +
                    '<img id="'+targetID+'-img" class="p-0" src="' + data + '" />'
                );

                $('#'+targetID+'-img').click(function(e) {
                    mapClicked(e, this, targetID, sizeRoutes[targetID][2], sizeRoutes[targetID][3]);
                });
                
                setTimeout(function() {
                    var elm = $('.active.map-show-content')[0];
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
    });

    var mapDimensions = [
        {{$mapDimensions['xs']}},
        {{$mapDimensions['ys']}},
        {{$mapDimensions['w']}},
        {{$mapDimensions['h']}},
    ];

    function mapClicked(e, that, targetID, xSize, ySize) {
        var xPerc = (e.pageX - $(that).offset().left) / xSize;
        var yPerc = (e.pageY - $(that).offset().top) / ySize;

        var mapX = Math.floor( mapDimensions[0] + mapDimensions[2]*xPerc );
        var mapY = Math.floor( mapDimensions[1] + mapDimensions[3]*yPerc );


        if($('#map-popup')[0]) {
            $('#map-popup').remove();
        }

        axios.get('{{ route('index') }}/api/{{ $worldData->server->code }}/{{ $worldData->name }}/villageCoords/'+ mapX + '/' + mapY, {
        })
            .then((response) => {
                const data = response.data.data;
                var xRel = e.pageX - $($('#size-1')[0].parentElement.parentElement).offset().left;
                var yRel = e.pageY - $($('#size-1')[0].parentElement.parentElement).offset().top;

                var popupHTML = '<div id="map-popup">'+
                    '{{ ucfirst(__('ui.table.name')) }}: <a href="'+data.selfLink+'" target="_blank">'+data.name+'</a><br>'+
                    '{{ ucfirst(__('ui.table.points')) }}: '+data.points+'<br>'+
                    '{{ ucfirst(__('ui.table.coordinates')) }}: '+data.coordinates+'<br>';
                
                if(data.owner != 0) {
                    popupHTML += '{{ ucfirst(__('ui.table.owner')) }}: <a href="'+data.ownerLink+'" target="_blank">'+data.ownerName+'</a><br>';
                } else {
                    popupHTML += '{{ ucfirst(__('ui.table.owner')) }}: '+data.ownerName+'<br>';
                }
                if(data.ownerAlly != 0) {
                    popupHTML += '{{ ucfirst(__('ui.table.ally')) }}: <a href="'+data.ownerAllyLink+'" target="_blank">'+data.ownerAllyName+
                        '['+data.ownerAllyTag+']</a><br>';
                } else {
                    popupHTML += '{{ ucfirst(__('ui.table.ally')) }}: '+data.ownerAllyName+'<br>';
                }
                popupHTML += "{{ ucfirst(__('ui.table.conquer')) }}: "+data.conquer+"<br></div>";
                $('#'+targetID).append(popupHTML);

                $('#map-popup')[0].style.left = xRel+"px";
                $('#map-popup')[0].style.top = yRel+"px";
            })
            .catch((error) => {
            });
    }

    $(function () {
        $('.active.map-show-tab').trigger('click');
    });
    
    @auth
        @if($wantedMap->user_id != Auth::user()->id)
            function changeFollow() {
                var icon = $('#follow-icon');
                axios.post('{{ route('tools.follow') }}',{
                    model: 'Map_Map',
                    id: '{{ $wantedMap->id }}'
                })
                    .then((response) => {
                        if(icon.hasClass('far')){
                            icon.removeClass('far text-muted').addClass('fas text-warning').attr('style','cursor:pointer; text-shadow: 0 0 15px #000;');
                        }else {
                            icon.removeClass('fas text-warning').addClass('far text-muted').attr('style', 'cursor:pointer;');
                        }
                    })
                    .catch((error) => {

                    });
            }
        @endif
    @endauth
</script>
@endsection
