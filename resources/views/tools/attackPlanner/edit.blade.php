<div class="modal fade edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('global.edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editItemForm">
                <div class="modal-body">
                    <div class="row justify-content-md-center">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Type</span>
                                    <span class="input-group-text"><img class="type-img" src="{{ \App\Util\Icon::icons(8) }}"></span>
                                </div>
                                <select name="type" class="custom-select attack-type" data-toggle="tooltip" data-placement="top" title="{{ __('tool.attackPlanner.type_helper') }}">
                                    <option value="-1">{{ __('ui.old.nodata') }}</option>
                                    @foreach(\App\Tool\AttackPlanner\AttackListItem::attackPlannerTypeIconsGrouped() as $name => $grp)
                                    <optgroup label="{{ $name }}">
                                    @foreach($grp as $idx)
                                        <option value="{{ $idx }}">{{ \App\Tool\AttackPlanner\AttackListItem::statTypeIDToName($idx) }}</option>
                                    @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('tool.attackPlanner.startVillage') }}</span>
                                </div>
                                <input name="xStart" class="form-control mx-auto col-5 coord-input" type="text" inputmode="numeric" placeholder="500" maxlength="3" />
                                <div class="input-group-append input-group-prepend">
                                    <span class="input-group-text">|</span>
                                </div>
                                <input name="yStart" class="form-control mx-auto col-5 coord-input" type="text" inputmode="numeric" placeholder="500" maxlength="3" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('tool.attackPlanner.targetVillage') }}</span>
                                </div>
                                <input name="xTarget" class="form-control mx-auto col-5 coord-input" type="text" inputmode="numeric" placeholder="500" maxlength="3" />
                                <div class="input-group-append input-group-prepend">
                                    <span class="input-group-text">|</span>
                                </div>
                                <input name="yTarget" class="form-control mx-auto col-5 coord-input" type="text" inputmode="numeric" placeholder="500" maxlength="3" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('tool.attackPlanner.date') }}</span>
                                </div>
                                <input name="day" type="date" class="form-control form-control-sm day" value="{{ date('Y-m-d', time()) }}" data-toggle="tooltip" data-placement="top" title="{{ __('tool.attackPlanner.date_helper') }}" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn input-group-text dropdown-toggle dropdown-toggle-split time-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ __('tool.attackPlanner.arrivalTime') }} <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item time-switcher" value="0">{{ __('tool.attackPlanner.arrivalTime') }}</a>
                                        <a class="dropdown-item time-switcher" value="1">{{ __('tool.attackPlanner.sendTime') }}</a>
                                    </div>
                                    <input name="time_type" type="hidden" class="time-type" value="0">
                                </div>
                                <input name="time" type="time" step="0.001" class="form-control form-control-sm time" value="{{ date('H:i:s', time()+3600) }}" data-toggle="tooltip" data-placement="top" title="{{ __('tool.attackPlanner.date_helper') }}" />
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('global.unit') }}</span>
                                    <span class="input-group-text"><img class="unit-img" src="{{ \App\Util\Icon::icons(0) }}"></span>
                                </div>
                                <select name="slowest_unit" class="form-control form-control-sm slowest-unit" data-toggle="tooltip" data-placement="top" title="{{ __('tool.attackPlanner.unit_helper') }}">
                                    <option value="0">{{ __('ui.unit.spear') }}</option>
                                    <option value="1">{{ __('ui.unit.sword') }}</option>
                                    <option value="2">{{ __('ui.unit.axe') }}</option>
                                    @if ($config->game->archer == 1)
                                        <option value="3">{{ __('ui.unit.archer') }}</option>
                                    @endif
                                    <option value="4">{{ __('ui.unit.spy') }}</option>
                                    <option value="5">{{ __('ui.unit.light') }}</option>
                                    @if ($config->game->archer == 1)
                                        <option value="6">{{ __('ui.unit.marcher') }}</option>
                                    @endif
                                    <option value="7">{{ __('ui.unit.heavy') }}</option>
                                    <option value="8">{{ __('ui.unit.ram') }}</option>
                                    <option value="9">{{ __('ui.unit.catapult') }}</option>
                                    @if ($config->game->knight > 0)
                                        <option value="10">{{ __('ui.unit.knight') }}</option>
                                    @endif
                                    <option value="11">{{ __('ui.unit.snob') }}</option>
                                </select>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-12">
                            <div class="form-inline row">
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(0) }}"></span>
                                    </div>
                                    <input name="spear" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(1) }}"></span>
                                    </div>
                                    <input name="sword" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(2) }}"></span>
                                    </div>
                                    <input name="axe" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                @if ($config->game->archer == 1)
                                    <div class="input-group col-2 input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(3) }}"></span>
                                        </div>
                                        <input name="archer" class="form-control form-control-sm col-9" placeholder="0">
                                    </div>
                                @endif
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(4) }}"></span>
                                    </div>
                                    <input name="spy" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(5) }}"></span>
                                    </div>
                                    <input name="light" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                @if ($config->game->archer == 1)
                                    <div class="input-group col-2 input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(6) }}"></span>
                                        </div>
                                        <input name="marcher" class="form-control form-control-sm col-9" placeholder="0">
                                    </div>
                                @endif
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(7) }}"></span>
                                    </div>
                                    <input name="heavy" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(8) }}"></span>
                                    </div>
                                    <input name="ram" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(9) }}"></span>
                                    </div>
                                    <input name="catapult" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                                @if ($config->game->knight > 0)
                                    <div class="input-group col-2 input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(10) }}"></span>
                                        </div>
                                        <input name="knight" class="form-control form-control-sm col-9" placeholder="0">
                                    </div>
                                @endif
                                <div class="input-group col-2 input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inputGroup-sizing-sm"><img class="pr-2" src="{{ \App\Util\Icon::icons(11) }}"></span>
                                    </div>
                                    <input name="snob" class="form-control form-control-sm col-9" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <div class="input-group-prepend mb-2">
                            <span class="input-group-text inputGroup-sizing-sm">
                            <img src="{{asset("/images/ds_images/boost/tribe_skill.png")}}"
                                 alt="tribe_boost"></span>
                                        <select class="form-control form-control-sm" name="tribe_skill" data-toggle="tooltip" data-placement="top"
                                                title="{{ __('tool.attackPlanner.type_tribe_boost') }}">
                                            <option value="0">0%</option>
                                            <option value="0.01">1%</option>
                                            <option value="0.02">2%</option>
                                            <option value="0.03">3%</option>
                                            <option value="0.04">4%</option>
                                            <option value="0.05">5%</option>
                                            <option value="0.06">6%</option>
                                            <option value="0.07">7%</option>
                                            <option value="0.08">8%</option>
                                            <option value="0.09">9%</option>
                                            <option value="0.1">10%</option>
                                            <option value="0.11">11%</option>
                                            <option value="0.12">12%</option>
                                            <option value="0.13">13%</option>
                                            <option value="0.14">14%</option>
                                            <option value="0.15">15%</option>
                                            <option value="0.16">16%</option>
                                            <option value="0.17">17%</option>
                                            <option value="0.18">18%</option>
                                            <option value="0.19">19%</option>
                                            <option value="0.2">20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 mb-2">
                                    <div class="input-group-prepend">
                            <span class="input-group-text inputGroup-sizing-sm">
                            <img src="{{asset("/images/ds_images/boost/support_boost.png")}}"
                                 alt="support_boost" height="16px"></span>
                                        <select class="form-control form-control-sm" name="support_boost" data-toggle="tooltip" data-placement="top"
                                                title="{{ __('tool.attackPlanner.type_support_boost') }}">
                                            <option value="0">0%</option>
                                            <option value="0.1">10%</option>
                                            <option value="0.2">20%</option>
                                            <option value="0.3">30%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="control-label col-3">{{ __('tool.attackplaner.notes') }}</label>
                                <div class="col-12">
                                    <textarea name="note" class="form-control form-control-sm"  rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <input name="attack_list_item" type="hidden">
                        <input name="key" type="hidden" value="{{ $attackList->edit_key }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('global.close') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).on('submit', '#editItemForm', function (e) {
        e.preventDefault();
        if (validatePreSend(this)) {
            var id = $('input[name="attack_list_item"', this).val();
            axios.patch('{{ route("tools.attackListItem.update", ["itemId"]) }}'.replaceAll("itemId", id), $('#editItemForm').serialize())
                .then((response) => {
                    var data = response.data;
                    reloadData(true);
                    createToast(data['msg'], data['title'], '{{ __('global.now') }}', data['data'] === 'success'? 'fas fa-check-circle text-success' :'fas fa-exclamation-circle text-danger')
                })
                .catch((error) => {

                })
        }
    })
    
    var autoFilledTime = true;
    $(document).on('change', '#editItemForm input[name="day"], #editItemForm input[name="time"]', () => {
        autoFilledTime = false;
    })
    
    function editUpdateTime(day, time) {
        if(! autoFilledTime) return;
        var context = $('#editItemForm');
        $('input[name="day"]', context).val(day);
        $('input[name="time"]', context).val(time);
    }
    
    function editSetAutoTime() {
        autoFilledTime = true;
    }
</script>
@endpush
