<?php
    require("./conn.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MeNotes--Take A Note</title>
    
    <!-- Boostrap css cdn link  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">

    <!-- Our favicon  -->
    <link rel="shortcut icon" href="./logo.png" type="image/x-icon">

    <!-- jquery cdn link for working its other links  -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- jquery datatables css cdn link  -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>

    <!-- Modal for edit the titles and descriptions-->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Your Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="post" action="./index.php">
                        <input type="hidden" id="sno" name="sno">

                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control" id="titleedit" placeholder="Enter your note title"
                                name="titleedit">
                            <label for="titleedit">Title</label>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Write your note description"
                                name="descriptionedit" id="descriptionedit" rows="5" style="height: 100px"></textarea>
                            <label for="descriptionedit">Description</label>
                        </div>


                        <button type="submit" class="btn btn-primary my-2" name="snoedit">Save changes</button>
                    </form>
                </div>



            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="width:3.5%;"><img src="./logo.png" alt="logo" width="100%"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Last Top 5 Notes Titles
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                $sql = "select * from crud";
                $result = mysqli_query($conn,$sql);
                $numRows = mysqli_num_rows($result);
                $sno = 1;
                if ($result) {
                    if ($numRows>0) {
                        while ($row=mysqli_fetch_assoc($result)) {
                            echo '<li><a class="dropdown-item" href="#">'.$row['title'].'</a></li>';
                        }
                    }
                    else{
                        echo "NO result found";
                    }
                }
                else{
                    echo "NO result found";
                }
            ?>
                        </ul>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <!-- Our Notes taking container  -->
    <div class="container my-3 ">
        <h1 class="text-center">Take Your Notes! And Finish Your Podium <span><img src="./logo.png" alt="logo"
                    width="6.7%"></span></h1>
        <p class="h6 text-center">Here every can write our notes and use any time also can find to share with friends.
        </p>

        <!-- Form to fill out the notes  -->
        <form method="post" action="<?php $_SERVER['REQUEST_URI']?>">
            <!-- Adding notes on databases  -->
            <?php
            $update = false;
            $delete = false;
            if (isset($_GET['delete'])) {
                $sno = $_GET['delete'];
                // echo $sno;
                $sql = "delete from crud where id = '$sno'";
                $result = mysqli_query($conn,$sql);
            }

            if (isset($_POST['snoedit'])) {
                $title = $_POST['titleedit'];
                $description = $_POST['descriptionedit'];
                $sno = $_POST['sno'];

                $sql = "update crud set title='$title',description='$description' where id = '$sno'";
                $result = mysqli_query($conn,$sql);

            }
            else{
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $title = $_POST['title'];
                    $description = $_POST['description'];

                    $sql = "insert into crud(title,description) values('$title','$description')";
                    $result = mysqli_query($conn,$sql);

                    if ($result) {
                        echo'<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success</strong> Your note has been added successfully
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    }
                    else{
                        echo'<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>OOPs</strong> Your note has been not added.Plz try again!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    }
                }
            }
            ?>
            <div class="mb-3 form-floating">
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter your note title">
                <label for="title">Title</label>
            </div>

            <div class="form-floating">
                <textarea class="form-control" placeholder="Write your note description" name="description"
                    id="description" rows="5" style="height: 100px"></textarea>
                <label for="description">Description</label>
            </div>

            <div class="form-text">We'll never share your information with anyone else.</div>
            <button type="submit" class="btn btn-primary my-3 rounded-pill" name="addNote">Add Note</button>

        </form>
    </div>

    <!-- Tables which save the notes  -->
    <div class="container my-3">
        <h2>Your Notes</h2>

        <table id="myTable" class="table table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">SNo.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <!-- Displaying the notes from the database  -->
                <?php
                $sql = "select * from crud";
                $result = mysqli_query($conn,$sql);
                $numRows = mysqli_num_rows($result);
                $sno = 1;
                if ($result) {
                    if ($numRows>0) {
                        while ($row=mysqli_fetch_assoc($result)) {
                            echo'<tr>
                                    <th scope="row">'.$sno.'</th>
                                    <td>'.$row['title'].'</td>
                                    <td>'.$row['description'].'</td>
                                    <td> 
                                      <!---<button type="button" class="edit btn btn-success mx-2 " name="editNote"  data-bs-toggle="modal" data-bs-target="#editModal">Edit</button> --->

                                        <button class="edit btn btn-success" id="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>

                                        <a type="button" class="delete btn btn-danger mx-2" name="deleteNote" id="'.$row['id'].'">Deletet</a>
                                    </td>
                                </tr>';
                            $sno++;
                        }
                    }
                    else{
                        echo "NO result found";
                    }
                }
                else{
                    echo "NO result found";
                }
            ?>
            </tbody>
        </table>
    </div>




    <!-- Scripts to get the data of the documents  -->
    <script>
    let edit = document.getElementsByClassName('edit');
    Array.from(edit).forEach((element) => {
        element.addEventListener('click', (e) => {
            // console.log(e.target.parentNode.parentNode);
            let tr = e.target.parentNode.parentNode;
            let title = tr.getElementsByTagName('td')[0].innerText;
            let description = tr.getElementsByTagName('td')[1].innerText;
            // console.log(title,description);

            let snoedit = document.getElementById('sno');
            let titleedit = document.getElementById('titleedit');
            let descriptionedit = document.getElementById('descriptionedit');

            snoedit.value = e.target.id;
            titleedit.value = title;
            descriptionedit.value = description
        })
    });
    let deletenote = document.getElementsByClassName('delete');
    Array.from(deletenote).forEach((element) => {
        element.addEventListener('click', (e) => {
            // console.log(e.target.parentNode.parentNode);
            let tr = e.target.parentNode.parentNode;
            let title = tr.getElementsByTagName('td')[0].innerText;
            let description = tr.getElementsByTagName('td')[1].innerText;
            // console.log(title,description);

            let sno = e.target.id.substr(0, 1);
            console.log(sno);
            if (confirm('Are your want to delete this note')) {
                // console.log('yes');
                window.location = `./index.php?delete=${sno}`;
            } else {
                console.log('no');
            }
        })
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jquery datatables script cdn  -->
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <!-- To populate the function of the datatables insert the script  -->
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    </script>
</body>

</html>