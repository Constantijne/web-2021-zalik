<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Student</title>
</head>
<body>
<h1>students form</h1>
<form action="/students" method="post">
    @csrf
    <label for="fio-input">FIO</label>
    <input type="text" name="fio" id="fio-input">
    <label>
        Group
        <input type="text" name="group" id="group-input">
    </label>
    <label>
        Course
        <input type="text" name="course" id="course-input">
    </label>
    <button type="submit">Submit</button>
</form>
</body>
</html>
