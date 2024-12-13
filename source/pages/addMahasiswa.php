<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <style>
        input:focus {
            border-color: #4CAF50;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Tambah Mahasiswa</h2>
        <form action="action/addMahasiswaAction.php" method="POST">
            <label for="username">NIM:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="level">Level:</label>
            <select id="level" name="level" required>
                <option value="mahasiswa">mahasiswa</option>
                <option value="admin">admin</option>
            </select>

            <button type="submit">Tambah Mahasiswa</button>
        </form>
    </div>

</body>
</html>
