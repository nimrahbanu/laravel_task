<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
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
    <h3>User Form</h3>
    <div id="error_alert" class="alert alert-danger" style="display: none;">
        <strong>Error:</strong>Please try again.
    </div>
    <form id="user_form" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" id="userName" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" id="userEmail">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" id="userPhone"  maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" id="userDescription"></textarea>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control" id="userRole">
                <option disabled selected>--Select role--</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Profile Image</label>
            <input type="file" name="profile_image" class="form-control" id="userImage">
            <img id="imagePreview" src="#" alt="Image Preview" style="display: none; margin-top: 10px; width: 100px; height: auto;">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>



    <h4 class="mt-5">Users</h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody id="user_table">

        </tbody>
    </table>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        function load_roles() {
            $.ajax({
                url: '{{route('api.roles')}}',
                type: 'GET',
                success: function (response) {
                    let role_options = '<option disabled selected>--Select role--</option>';
                    response.data.forEach(role => {
                        role_options += `<option class="text-capitalize" value="${role.id}">${role.role_name}</option>`;
                    });
                    $('select[name="role_id"]').html(role_options);
                }
            });
        }

        function load_users() {
            $.ajax({
                url: '{{route('api.users')}}',
                type: 'GET',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        let tableData = '';
                        let i=1;
                        response.data.forEach(user => {
                            const roleName = user.role_info && user.role_info.role_name ? user.role_info.role_name : 'Role Not Found';
                            const profileImage = user.profile_image ? user.profile_image : 'default-image.jpg';
                            tableData += `<tr>
                                <td>${i}</td>
                                <td><img width="100" src="${user.profile_image}"></td>
                                <td class="text-capitalize">${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td class="text-capitalize">${roleName}</td>
                                <td>${user.description}</td>
                            </tr>`;
                            i++;
                        });
                        $('#user_table').html(tableData);
                    } else {
                        $('#user_table').html('<tr><td colspan="5">No users found.</td></tr>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#error_alert').text("Please try again.").show();

                }
            });
        }
        load_roles();
        load_users();

        $('#user_form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '{{route('api.users')}}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    load_users();
                    $('#user_form')[0].reset();
                    $('#imagePreview').hide();
                },
                error: function (response) {
                    if (response.status === 422) {

                        const errors = response.responseJSON.errors;
                        console.log(errors);
                        let errorMessages = '';
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessages += `<p><strong>${field}:</strong> ${errors[field].join(', ')}</p>`;
                            }
                        }
                        $('#error_alert').html(errorMessages).show();

                    } else {
                        console.error('Error:', error);
                    }
                }
            });
        });

    });

$('#userImage').on('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            $('#imagePreview').attr('src', event.target.result).show();
        };
        reader.readAsDataURL(file);
    } else {
        $('#imagePreview').hide();
    }
});

</script>

</body>
</html>
