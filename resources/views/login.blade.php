<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            outline: none;
        }

        body {
            display: flex;
            background-color: #283e37;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            display: flex;
            background: #283e37;
            box-sizing: border-box;
            padding: fluid;
            justify-content: space-between;
            flex-direction: row;
            min-height: 60vh;
            border-radius: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
            /* Negative margin to counteract padding on cards */
        }

        .card {
            flex: 0 0 calc(30% - 60px);
            /* Adjust width as needed */
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 70px;
            margin: 40px;
            background: #6af1ce;
            /* Adjust margin to create space between cards */
        }

        .card1 {
            background-color: #929192;
            /* Yellow background for first card */
        }

        .card2 {
            background-color: #c2fdf5;
            /* Blue background for second card */
        }

        .header {
            padding: 30px 0 30px 0;
            text-align: center;
        }

        .header h1 {
            font-size: 21px;
        }

        .input_area {
            display: flex;
            flex-direction: column;
            justify-content: stretch;
            align-content: center;
            text-align: center;
        }

        .input_area input {
            /* width: 100%; */
            margin: 10px 0;
            border: 1px grey solid;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 12px;
            outline: none;
            color: grey;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .actions {
            text-align: center;
            padding: 30px 0 10px 0;
        }

        .actions button {
            background-color: #1aebb6;
            border: none;
            border-radius: 20px;
            padding: 5px 25px;
            cursor: pointer;
        }

        .actions p {
            font-size: 13px;
        }

        .Sign_up_link {
            font-weight: 500;
            color: #14b397;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col mb-10">
            <div class="container">

                <div class="card card1">
                    <div class="header">
                        <h1>Admin Login</h1>
                    </div>
                    <div class="actions">
                        <form action="{{ route('admin.index') }}">
                            <button type="submit" value="Login">Login</button>
                        </form>
                    </div>
                </div>

                <div class="card card2">
                    <div class="header">
                        <h1>User Login</h1>
                    </div>
                    <div class="actions">
                        <form action="{{ route('user.index') }}">
                            <button type="submit" value="Login">Login</button>
                        </form>
                        <p>
                            Don't have a account ?
                            <a href="{{route('user.register')}}" class="Sign_up_link">Sign Up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>