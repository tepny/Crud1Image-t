<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="bg-success.bg-gradient">
    <div class="container mt-4 p-4 shadow rounded-3">
        <!-- Button trigger modal -->
        <button id="add" type="button" class="btn btn-outline-dark float-end mb-2" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            +Add Student
        </button>
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Profile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    require 'connection.php';
                    $select="SELECT * FROM tbl_student";
                    $ex=mysqli_query($conn,$select);
                    while($row=mysqli_fetch_assoc($ex)){
                        echo '
                            <tr>
                                <td>'.$row['id'].'</td>
                                <td>'.$row['name'].'</td>
                                <td>'.$row['gender'].'</td>
                                <td>
                                    <img src="'.$row['profile'].'" width="40px"
                                        height="40px" class="rounded-circle" alt="">
                                </td>
                                <td>
                                    <button class="btn btn-outline-danger" id="delete" >Delete</button>
                                    <button id="edit" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>
                                </td>
                            </tr>
                        ';
                    }

                 ?>
            </tbody>


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- FORM START -->
                        <form action="" id="form" method="POST" enctype="multipart/form-data">

                            <div class="modal-header">
                                <h5 class="modal-title">Form Student</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <!-- Name -->
                                 <input type="text" name="id" id="id">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter name"
                                        required>
                                </div>
                                <!-- Gender -->
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="" disabled selected>-- Select Gender --</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Profile -->
                                <div class="mb-3">
                                    <label class="form-label">Profile</label>
                                    <input type="file" id="profile" name="profile" class="form-control" accept="image/*" required>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" id="save" data-bs-dismiss="modal" class="btn btn-primary">
                                    Save
                                </button>
                                <button type="button" id="update" data-bs-dismiss="modal" class="btn btn-success">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </table>
    </div>
</body>
</html>
<script>
    $(document).ready(function(){
        $('#add').click(function(){
            $('#save').show()
            $('#update').hide()
            $('.modal-title').text('Add Student')
            $('#form')[0].reset()
        })
        $('#save').click(function(){
            const name=$('#name').val()
            const gender=$('#gender').val()
            const profile=$('#profile')[0].files[0]
            const imgurl=URL.createObjectURL(profile)
            const formdata=new FormData()
            formdata.append('name',name)
            formdata.append('gender',gender)
            formdata.append('profile',profile)
            $.ajax({
                url:'insert.php',
                method:'POST',
                data:formdata,
                contentType:false,
                processData:false,
                success:function(response){
                    $('tbody').append(`
                        <tr>
                            <td>${response}</td>
                            <td>${name}</td>
                            <td>${gender}</td>
                            <td>
                                <img src="${imgurl}" width="40px"
                                    height="40px" class="rounded-circle" alt="">
                            </td>
                            <td>
                                <button class="btn btn-outline-danger">Delete</button>
                                <button class="btn btn-outline-warning">Edit</button>
                            </td>
                        </tr>
                    `)
                    $('#form').trigger('reset')
                }
            })
            
        })
        $(document).on('click','#edit',function(){
            $('#save').hide()
            $('#update').show()
            $('.modal-title').text('Update Student')
            const row=$(this).closest('tr')
            const id=row.find('td:eq(0)').text().trim()
            const name=row.find('td:eq(1)').text().trim()
            const gender=row.find('td:eq(2)').text().trim()
            
            $('#id').val(id)
            $('#name').val(name)
            $('#gender').val(gender)
            
            $('#update').click(function(){
                const id=$('#id').val()
                const name=$('#name').val()
                const gender=$('#gender').val()
                const profile=$('#profile')[0].files[0]
                const imgurl=URL.createObjectURL(profile)
                const formdata=new FormData()
                formdata.append('id',id)
                formdata.append('name',name)
                formdata.append('gender',gender)
                formdata.append('profile',profile)
                $.ajax({
                    url:'update.php',
                    method:'POST',
                    data:formdata,
                    contentType:false,
                    processData:false,
                    success:function(res){
                        if(row.find('td:eq(0)').text().trim()==id){
                            row.find('td:eq(1)').text(name)
                            row.find('td:eq(2)').text(gender)
                            row.find('td:eq(3) img').attr('src',imgurl)
                        }
                    }
                })
            })
            
            
        })
        $(document).on('click','#delete',function(){
            
            if(!confirm("Are you Sure for delete?")) return
            const row=$(this).closest('tr');
            const id=row.find('td:first').text().trim()
            let formdata = new FormData()
            formdata.append('id',id)
            $.ajax({
                url:'delete.php',
                method:'POST',
                data:{
                    id
                },
                success:function(){
                    row.remove()
                }
            })
        })
    })
</script>