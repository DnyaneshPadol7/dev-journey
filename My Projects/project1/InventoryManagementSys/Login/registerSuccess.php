<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Updated</title>
    <style>
        body {
            background:linear-gradient(#baa3ff, #93abff) no-repeat;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
            width: 100%;
        }
        h1 {
            color: #5c39d1;
            margin-bottom: 1rem;
        }
        p {
            color: #333;
            margin-bottom: 2rem;
        }
        a {
            background-color: #5c39d1;
            color: white;
            text-decoration: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        img {
            width: 120px;       /* desired width */
            height: auto;       /* maintain aspect ratio */
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
            border-radius: 50%; /* optional: makes it circular */
            box-shadow: 0 8px 15px #5c39d1; /* 3D shadow effect */
            transition: transform 0.3s, box-shadow 0.3s;
        }

        /* Optional: hover effect for extra 3D feel */
        img:hover {
            transform: translateY(-4px); /* image lifts slightly */
            box-shadow: 0 15px 25px rgba(0,0,0,0.4); /* stronger shadow on hover */
        }

        a:hover {
            background-color: #4a2fb3;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Registration Successful</h1>
        <img src="../Asset/success.png" alt="Success">
        <p>You have registered successfully.<br>
           You can now log in with your ID and Password.</p>
        <a href="../Login/login.html">Go to Login</a>
    </div>

</body>
</html>