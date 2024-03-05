<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CRUD</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet" integrity="" crossorigin="">

    <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">

    <!-- Styles -->
    <style>

    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin-top: 50px;
        }

        .listdata {
            width: 60%;
            border-radius: 5px;
            margin-top: 30px;
            padding-bottom: 10px;
        }

        .container.list-data {
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
    </style>
</head>

<body class="antialiased">
    <center>
        <div class="container list-data">
            <form>
                <div class="row">
                    <div class="col-4 col-sm-1">
                        <input type="hidden" class="form-control" id="id">
                    </div>
                    <div class="col-4 col-sm-4">
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Enter name">
                    </div>
                    <div class="col-4 col-sm-4">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>
                    <div class="col-4 col-sm-3">
                        <button type="submit" onclick="store()" class="btn btn-primary" id="submit-button">Save</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="listdata border">
            <div style="background-color: blue;">
                <h5 style="padding: 10px 30px 10px 30px; color:white">List of Data</h5>
            </div>

            <div style="padding: 10px 30px 0px 30px;">
                <table id="datatable" class="stripe border">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @foreach ($datapengguna as $data)
                    <tbody>
                        <tr>
                            <td class="border">{{ $data->nama }}</td>
                            <td class="border">{{ $data->email }}</td>
                            <td class="border">
                                <button type="button" id="delete" onclick="destroy('{{ $data->id }}')" class="btn btn-danger">Delete</button>
                                <button type="button" onclick="edit('{{ $data->id }}')" class="btn btn-success update-button">Update</button>
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </center>

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- /sweet alert -->

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        //datatable
        new DataTable('#datatable');

        function show() {
            $.get("{{ url('show')}}", {}, function(data, status) {
                $("#show").html(data);
            });
        }

        function store() {
            event.preventDefault();

            var id = $('#id').val();
            var nama = $('#nama').val();
            var email = $('#email').val();

            if (nama.trim() === '' || email.trim() === '') {
                Swal.fire({
                    title: "Warning!",
                    text: "Name and email cannot be empty",
                    icon: "warning"
                })
                return;
            }

            if (email.indexOf('@') === -1) {
                Swal.fire({
                    title: "Warning!",
                    text: "Email must contain @",
                    icon: "warning"
                })
                return;
            }

            $(function() {
                $(document).on('click', '#submit-button', function(e) {
                    e.preventDefault();
                    var link = $(this).attr("onclick");

                    if (id) {
                        update(id, nama, email);
                    } else {
                        $.ajax({
                            type: "post",
                            url: "{{url('store')}}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                nama: nama,
                                email: email
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Saved!",
                                    text: "Your data has been saved.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.responseJSON.message;
                                Swal.fire({
                                    title: "Error!",
                                    text: errorMessage,
                                    icon: "error"
                                });
                            }
                        })
                    }

                })
            });
        }

        function edit(id) {
            $.get("{{ url('edit')}}/" + id, {}, function(data, status) {
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#email').val(data.email);

                $('#submit-button').replaceWith('<button type="button" id="perbarui"  style="margin-right: 10px;margin-left: -10px;" onclick="update(' + data.id + ')" class="btn btn-success">Update</button><button type="button" id="cancel" onclick="cancel()" class="btn btn-danger">Cancel</button>');
            });
            $(document).on('click', '#cancel', function() {
                cancel();
            });
        }

        function cancel() {
            var saveButton = '<button type="submit" onclick="store()" class="btn btn-primary" id="submit-button">Save</button>';

            $('#id').val('');
            $('#nama').val('');
            $('#email').val('');

            $('#perbarui').replaceWith(saveButton);
            $('#cancel').remove();
        }

        function update(id, nama, email) {
            var id = $('#id').val();
            var nama = $('#nama').val();
            var email = $('#email').val();

            if (nama.trim() === '' || email.trim() === '') {
                Swal.fire({
                    title: "Warning!",
                    text: "Name and email cannot be empty",
                    icon: "warning"
                })
                return;
            }

            if (email.indexOf('@') === -1) {
                Swal.fire({
                    title: "Warning!",
                    text: "Email must contain @",
                    icon: "warning"
                })
                return;
            }

            $(function() {
                $(document).on('click', '#perbarui', function(e) {
                    e.preventDefault();
                    var link = $(this).attr("onclick");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This data will be updated",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, update it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "PUT",
                                url: "{{url('update')}}/" + id,
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    nama: nama,
                                    email: email
                                }
                            })
                            Swal.fire({
                                title: "Updated!",
                                text: "Your data has been updated.",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                })
            });
        }

        function destroy(id) {
            $(function() {
                $(document).on('click', '#delete', function(e) {
                    e.preventDefault();
                    var link = $(this).attr("onclick");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{url('destroy')}}/" + id,
                                data: {
                                    _token: "{{ csrf_token() }}",
                                }
                            });

                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });

                        }
                    });
                })
            });
        }
    </script>
</body>

</html>