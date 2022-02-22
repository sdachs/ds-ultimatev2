@extends('layouts.app')

@section('titel', "Collection")

@section('content')
    <div class="row justify-content-center">
        <!-- Titel für Tablet | PC -->
        <div class="col-12 p-lg-5 mx-auto my-1 text-center d-none d-lg-block">
            <h1 class="font-weight-normal">Datensammlung: </h1>
        </div>
        <div class="card col-lg-10 mb-4">
            <form id="collect-form" class="m-3" action="" method="POST">
                <div class="form-group">
                    <textarea class="form-control" name="data" style="height: 300px"></textarea>
                </div>
                <div class="form-row">
                    <select class="select2 select2-single col-md-6 form-control" id="select-world">
                    @foreach ($worlds as $world)
                        <option value="{{ $world->server->code . $world->name }}">
                            {{ $world->display_name }} ({{ $world->server->code . $world->name }})
                        </option>
                    @endforeach
                    </select>
                    <div class="col"></div>
                    <input class="btn btn-sm btn-success" type="submit">
                </div>
                @csrf
            </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h2>Letze daten:<p class="float-right" style="font-size: 0.9rem;">
                    <a href="{{ route("tools.collectDataStats") }}">Gesamtstatistiken (nur für admins)</a>
                </p></h2>
                <table class="table" id="latest-table">
                    <colgroup>
                        <col style="width: 100px">
                        <col style="width: 120px">
                        <col style="width: 50px">
                        <col style="width: 80px">
                        <col style="width: 80px">
                        <col style="width: 60px">
                        <col style="width: 60px">
                        <col style="width: 60px">
                        <col style="width: 100px">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>Welt</th>
                        <th>Gebäude</th>
                        <th>Stufe</th>
                        <th>Bauzeit</th>
                        <th>HG Stufe</th>
                        <th>Holz</th>
                        <th>Lehm</th>
                        <th>Eisen</th>
                        <th>Bauernhof</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($latestData as $data)
                        <tr>
                            <th>{{ $data->world->display_name }} ({{ $data->world->server->code . $data->world->name }})</th>
                            <td>{{ ucfirst(__("ui.buildings." . $data->building)) }}</td>
                            <td>{{ $data->level }}</td>
                            <td>{{ $data->buildtime }}</td>
                            <td>{{ $data->mainLevel }}</td>
                            <td>{{ $data->wood }}</td>
                            <td>{{ $data->clay }}</td>
                            <td>{{ $data->iron }}</td>
                            <td>{{ $data->pop }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    var url = "{{ route('index') }}/tools/datacollectionHQ/post/";

    $(function () {
        $('#select-world').change(function() {
            $('#collect-form')[0].action = url + $('#select-world').val() + "?debug=1";
        });

        $('#select-world').trigger("change");

        $('#collect-form').submit(function(e) {
            e.preventDefault();
            axios.post($('#collect-form')[0].action, $('#collect-form').serialize())
                .then((response) => {
                    var data = response.data;
                    createToast(data, "Daten erhalten", "{{ __('global.now') }}");
                })
                .catch((error) => {

                });
        });
    });
</script>
<script src="{{ asset('plugin/select2/select2.full.min.js') }}"></script>
<script>
    function loadText(state) {
        if(!state.element) return state.text;

        if(!state.element.attributes['data-content']) return state.text;
        console.log($(state.element.attributes['data-content'].nodeValue));
        return $("<p>"+state.element.attributes['data-content'].nodeValue+"</p>");
    }

    $(function() {
        $('.select2-single').select2({
            theme: "bootstrap4",
            minimumResultsForSearch: 0,
            templateResult: loadText,
        });
        $('.select2-multi').select2({
            theme: "bootstrap4",
            minimumResultsForSearch: 0,
            closeOnSelect: false,
            templateResult: loadText,
        });
    });
</script>
@endpush

@push('style')
<style>
    #latest-table thead th { text-align: center }
    #latest-table thead th:first-child { text-align: left }
    #latest-table tbody td { text-align: center }
    #latest-table tbody td:first-child { text-align: left }

    #data-table thead th { text-align: center }
    #data-table thead th:first-child { text-align: left }
    #data-table tbody td { text-align: center }
    #data-table tbody td:first-child { text-align: left }
</style>
@endpush
