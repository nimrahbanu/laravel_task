<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-success" href="{{ url('/') }}">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <h3>Role Form</h3>
    <div id="error_alert" class="alert alert-danger" style="display: none;">
        <strong>Error:</strong>Please try again.
    </div>
    <form id="role_form" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Role Name</label>
            <input type="text" name="role_name" class="form-control" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')" required>
            <div id="role_error" class="text-danger mt-2" style="display: none;"></div>
        </div>
        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
    </form>

    <h4 class="mt-5">Roles List</h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Role Name</th>
        </tr>
        </thead>
        <tbody id="role_list">
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function Role() {
            $.ajax({
                url: '{{route('api.roles')}}',
                type: 'GET',
                success: function (response) {
                    let table_data = '';
                    let i = 1;
                    response.data.forEach(role => {
                        table_data += `<tr>
                            <td>${i}</td>
                            <td class="text-capitalize">${role.role_name}</td>
                        </tr>`;
                        i++;
                    });
                    $('#role_list').html(table_data);
                },
                error: function () {
                    $('#error_alert').text("Please try again.").show();
                }
            });
        }
        Role();

        $('#role_form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $('#role_error').hide();
            $('#error_alert').hide();

            $.ajax({
                url: '{{route('api.roles')}}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    Role();
                    $('#role_form')[0].reset();
                },
                error: function (response) {
                    if (response.status === 422) {
                        const errors = response.responseJSON.errors;
                        if (errors.role_name) {
                            $('#role_error').text(errors.role_name[0]).show();
                        }
                    } else {
                        $('#error_alert').text("Please try again.").show();
                    }
                }
            });
        });
    });
</script>
</body>
</html>
