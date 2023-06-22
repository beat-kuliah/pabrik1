@extends('layouts.app')

@section('content')

<div class="container">
    <button style="float: right; margin-top: 50px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUser">
        Tambah Data
    </button>
    <h1>User</h1>
    <br><br>
    <div class="table-responsive">
        <table id="user" class="display" style="width:100%">
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
</div>

@include('user.create')
@include('user.edit')
@include('user.role')

@endsection

@section('script')

<script>
    var editId;

    $(document).ready(function() {
        $('#user').DataTable({
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
                        return '<button type="button" data-bs-toggle="modal" data-bs-target="#updateRole" onclick="updateRole(' + data + ')" class="btn btn-warning">role</button><span>   </span><button type="button" onclick="deleteUser(' + data + ')" class="btn btn-danger">Delete</button>';
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

                            axios({
                                url: '/role',
                            }).then(function(response) {
                                response.data.forEach(element => {
                                    select.append('<option value="' + element.id + '">' + element.name + '</option>');
                                });
                                // select.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }
                    });
            },
        });

        axios.get('/role')
            .then(function(response) {
                response.data.forEach(element => {
                    var role = document.getElementById("selectRole");
                    var option = document.createElement("option");
                    option.value = element.id;
                    option.text = element.name;
                    role.add(option);
                });
            })
    });

    function tambahUser() {
        var formData = new FormData(document.getElementById('formUser'));
        axios({
            url: '/user',
            method: 'post',
            data: formData
        }).then(function(response) {
            if (response.data = 200) {
                alert('Success');
                window.location.href = '/user';
            } else
                alert('Ada Username yang sama');
        }).catch(function(error) {
            console.log(error);
            alert('Gagal');
        })
    }

    function updateRole(val) {
        editId = val;
        axios.get('/user/show/' + val)
            .then(function(response) {
                $('#selectRole').val(response.data.roles[0].id);
            })
            .catch(function(error) {
                console.log(error);
            })

    }

    function updatedRole() {
        var formData = new FormData(document.getElementById('formUpdateRole'));
        axios({
            url: '/user/update-role/' + editId,
            method: 'post',
            data: formData
        }).then(function(response) {
            alert('Success');
            window.location.href = '/user';
        }).catch(function(error) {
            alert('Gagal');
        })
    }

    function deleteUser(val) {
        if (confirm("Anda yakin?") == true) {
            userDeleted(val);
        }
    }

    function userDeleted(val) {
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