<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .btn-dashboard {
            width: 100%;
            padding: 20px;
            font-size: 20px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <div class="container text-center mt-5">
        <h3>Admin Dashboard</h3>

        <div class="row">
            <div class="col-md-6">
                <a href="{{route('roles')}}" class="btn btn-outline-primary btn-dashboard">Manage Roles</a>
            </div>
            <div class="col-md-6">
                <a href="{{route('users')}}" class="btn btn-outline-success btn-dashboard">Manage Users</a>
            </div>
        </div>

    </div>

</body>
</html>
