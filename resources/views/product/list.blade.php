@extends('layout')

@section('content')
@if(Session::has('success'))
<p class="alert alert-info">{{ Session::get('success') }}</p>
@endif
<div class="container mt-4">
    <a href="{{route('product.create')}}" class="btn btn-primary float-right">Add</a>
    <button class="multiple_delete btn btn-danger float-right mr-3">Multiple Delete</button>
    <table id="product_datatable" class="table table-striped table-bordered text-center" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" class="multiple_checkbox"></th>
                <th>name</th>
                <th>price</th>
                <th>UPC</th>
                <th>status</th>
                <th>image</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        if ($('#product_datatable').length > 0) {
            let table = $('#product_datatable').DataTable({
                "processing": true,
                "serverSide": true,
                // paging: false,
                //'pageLength' : 10,
                "ajax": {
                    "url": "{{route('product')}}",
                    "type": "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                },
                columns: [{
                        "data": "id",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'upc'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'image'
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "searchable": false
                    },

                ]
            });

            $(document).on('click', '.product-delete', function() {
                var product_id = $(this).attr('product_id');
                if (confirm('Are you sure to remove this record?')) {
                    $.ajax({
                        url: "{{route('product.delete')}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": product_id,
                        },
                        success: function(data) {
                            var response = $.parseJSON(data);
                            if (response.status == true) {
                                alert(response.message);
                                table.ajax.reload();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.multiple_delete', function() {
                var v = [];
                $.each($(".single_checkbox"), function() {
                    if ($(this).prop('checked')) {
                        v.push($(this).val());
                    }
                });

                if (v.length == 0) {
                    alert('Please select checkbox');
                    return false;
                }

                if (confirm('Are you sure to remove multiple record?')) {
                    $.ajax({
                        url: "{{route('product.delete')}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "ids": v,
                        },
                        success: function(data) {
                            var response = $.parseJSON(data);
                            if (response.status == true) {
                                alert(response.message);
                                table.ajax.reload();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.multiple_checkbox', function() {
                if ($(this).prop('checked')) {
                    $.each($(".single_checkbox"), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($(".single_checkbox"), function() {
                        $(this).prop('checked', false);
                    });
                }
            });

        }

    });
</script>
@endsection