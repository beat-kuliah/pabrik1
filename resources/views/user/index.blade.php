@extends('layouts.app')

@section('content')

<div class="container">
    <h1>User</h1>
    <table id="test" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>User</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Role</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>User</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Role</th>
                <th class="text-center">Action</th>
            </tr>
        </tfoot>
    </table>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('#test').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/user/datatables',
            columns: [{
                    data: 'id'
                },
                {
                    data: 'username'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'role'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                    target: [5],
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<button type="button" onclick="editUser(' + data + ')" class="btn btn-warning">Edit</button><span>   </span><button type="button" onclick="deleteUser(' + data + ')" class="btn btn-danger">Delete</button>';
                    }
                },
                {
                    target: [4],
                    className: "text-center",
                    render: function(data, type, row) {
                        if (data == null)
                            return '-';
                        else
                            return data;
                    }
                },
                {
                    target: [2],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                },
                {
                    target: [3],
                    className: "text-center",
                    render: function(data, type, row) {
                        return fixDate(data);
                    }
                }
            ],
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        var column = this;
                        if (column[0][0] == 4) {
                            var select = $('<select><option value="">All</option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            $.ajax({
                                url: 'http://127.0.0.1:8000/role',
                            }).done(function(response) {
                                response.forEach(element => {
                                    select.append('<option value="' + element.id + '">' + element.name + '</option>');
                                });
                                // select.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }
                    });
            },
        });
    });

    function fixDate(val) {
        var fixDate = new Date(val);
        $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        var result = fixDate.getDate() + ' ' + $bulan[fixDate.getMonth()] + ' ' + fixDate.getFullYear() + ' ' + fixDate.getHours() + ':' + fixDate.getMinutes();

        return result;
    }

    function editUser(val) {
        axios.get('/user/' + val + '/update')
            .then(function(response) {
                window.location.href = '/user';
            })
            .catch(function(error) {
                console.log(error.response);
            })
    }

    function deleteUser(val) {
        axios.delete('/user/' + val)
            .then(function(response) {
                window.location.href = '/user';
            })
            .then(function(error) {
                console.log(error.response);
            })
    }
</script>

@endsection