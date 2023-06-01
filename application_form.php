<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form Demo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Application Form Demo</h1>
        <br>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="resume">Resume:</label>
                <input type="file" class="form-control-file" id="resume" name="resume" required>
            </div>
            <div class="form-group">
                <label for="resume">Cover Letter:</label>
                <input type="file" class="form-control-file" id="cover_letter" name="cover_letter" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form><br><br><br>
    </div>
</body>

</html>
