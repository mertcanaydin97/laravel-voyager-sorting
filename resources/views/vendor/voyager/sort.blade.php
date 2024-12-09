@extends('voyager::master')
@section('content')

    @if ($type == 'dbselect')
        <h1 class="page-title">
            <i class="voyager-list"></i> {{ $module_name }}
        </h1>
    @else
        <h1 class="page-title">
            <i class="voyager-list"></i> {{ $module_name }} Sıralanıyor
        </h1>
        <a class="btn btn-danger" href="{{ route('voyager.sort') }}"><i class="voyager-trash"></i> <span>Geri Dön</span></a>
    @endif
    <div id="voyager-notifications"></div>
    <div class="page-content browse container-fluid">
        <div class="alerts">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">



                        @if ($type == 'dbselect')
                        @else
                            <div id="nestedDemo" class="list-group col nested-sortable">
                                <div class="list-group-item nested-1 filtered">
                                    <div class="list-group nested-sortable">
                        @endif
                        @foreach ($sortables as $key => $value)
                            @if ($type == 'dbselect')
                                @if (in_array('sort', $value))
                                    @php
                                        $detail = DB::table('data_types')->where('name', $key)->first();
                                    @endphp
                                    <p><a href="{{ route('voyager.sort', [$key, 'sort']) }}">
                                            Sırala {{ $detail->display_name_plural }}</a> </p>
                                @endif
                            @else
                                <div class="list-group-item inner nested-2" data-pid="{{ $value->id }}">
                                    <p><span>{{ $value->title ?? $value->name }}</span>
                                        <a href="{{ route('voyager.' . $table_name . '.edit', $value->id) }}"
                                            target="_blank" class="btn btn-sm btn-primary pull-right edit"><i
                                                class="voyager-edit"></i>
                                            Düzenle</a>
                                        <a href="{{ route('voyager.' . $table_name . '.edit', $value->id) }}"
                                            target="_blank" class="btn btn-sm btn-warning pull-right view"><i
                                                class="voyager-eye"></i>
                                            Görüntüle</a>
                                    </p>
                                </div>
                            @endif
                        @endforeach

                        @if ($type == 'dbselect')
                        @else
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <style>
        .list-group-item p {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: center;
            justify-content: flex-start;
            align-items: center;
            margin: 0;
            gap: 12px;
        }

        .list-group-item p span {
            margin-right: auto;
        }

        .list-group-item.inner {
            padding: 0 10px;
        }
    </style>
@stop

@section('javascript')

    @if ($type == 'dbselect')
    @else
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

        <script>
            (function(window, document, undefined) {

                // code that should be taken care of right away

                window.onload = init;

                function init() {

                    var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

                    for (var i = 0; i < nestedSortables.length; i++) {
                        new Sortable(nestedSortables[i], {
                            group: 'nested',
                            animation: 150,
                            filter: '.filtered',
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onUpdate: function( /**Event*/ evt) {
                                var order = {};
                                $('.nested-2').each(function() {
                                    order[$(this).data('pid')] = $(this).index();
                                });
                                console.log(order)
                                $.ajax({
                                    url: '{{ route('voyager.sort', [$table_name, $column]) }}',
                                    type: 'POST',
                                    data: {
                                        order: order
                                    },
                                    success: function(data) {

                                        toastr.success(data);
                                    },
                                    error: function(data) {

                                        toastr.error("Error sorting.");

                                    }
                                })
                            }
                        });

                    }
                }

            })(window, document, undefined);
        </script>
    @endif
