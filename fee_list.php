<?php require_once('dbo.php');
// pagination condition  pagination btn click cheyyumbo kittuna $_get nte ullli data udel
if(isset($_GET['page'])) {
    // ivide get il nmukk kittuna page parameter value vech data edukkanam athinu thzhe get all fucntionil arguiment aay kodukkunu
    $page = $_GET['page'];
}
else {
    $page = 1;
}
//if(isset($_GET['student_id'])) {
//    $fees = getAll('fees', $page, $_GET);
//}
$fees = getAll('fees', $_GET, $page);
// No need to pass $page argument for fetching all records, It is made as null (default argument) in getAll function.
// So it is $page argument is optional
$allFees = getAll('fees', $_GET);
$totalRecords = count($allFees);
$numberOfPages = ceil($totalRecords/10);


?><!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <title>STUDENTS MANAGER</title>
    <style>
        /* Stackoverflow preview fix, please ignore */
        .navbar-nav {
            flex-direction: row;
        }

        .nav-link {
            padding-right: .5rem !important;
            padding-left: .5rem !important;
            color: #fff!important;
        }

        /* Fixes dropdown menus placed on the right side */
        .ml-auto .dropdown-menu {
            left: auto !important;
            right: 0px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark rounded" style="background: #b366ff;">
    <a class="navbar-brand" href="index.png"><img src="images/log.png" class="img-responsive" ></a>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
            <a href="student_list.php" class="nav-link">Student</a>
        </li>
        <li class="nav-item">
            <a href="fee_list.php" class="nav-link">Fees</a>
        </li>

    </ul>
</nav>
<section>
    <div class="container">
        <div class="row" style="position: relative;padding-bottom: 10%;">
            <div class="add-btn" style="position: absolute; right: 5%;">
                <a href="fee_create.php" class="blue-btn-add">ADD NEW</a>
            </div>

        </div>
        <div class="row">


            <table class="table">

                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Payment Date</th>
                    <th scope="col">Fee Amount</th>
                    <th scope="col">Fine Amount</th>
                    <th scope="col">Total</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php // $feeinte ullil ennam 0 thinekkal kooduthal means undenkil foreach print list cheyyanam?>
                <?php if(count($fees) > 0): ?>
                    <?php foreach($fees as $fee): ?>
                        <tr>
                            <?php // ivide nammude kayyil ulla student id vech student tablil ninnu data fetch cheyth athivide display cheyyuvaanu.?>
                            <?php $student = getOne('student', $fee['student_id'])?>
                            <td><?php echo $student['name'];?></td>
                            <td><?php echo $fee['semester'];?></td>
                            <td><?php echo date('d M Y',strtotime($fee['payment_date']));?></td>
                            <td><?php echo $fee['fee_amount'];?></td>
                            <td><?php echo $fee['fine_amount'];?></td>
                            <td><?php echo $fee['fee_amount']+$fee['fine_amount'];?></td>

                            <td><a href="fee_create.php?id=<?php echo $fee['id']; ?>">Edit</a> |
                                <a href="javascript:void(0);" onClick="deleteRecord(<?php echo $fee['id']; ?>)"style="cursor:pointer;">Delete</a></td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">No fees available</td></tr>
                <?php endif; ?>
                </tbody>
            </table>



        </div>
    </div>
</section>
<section>
    <?php // if we have more then 10 records then only we need show pagination?>
    <?php if($totalRecords > 10): ?>
        <div class="container">
            <div class="row d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <!--                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>-->
                        <?php for($i=1; $i<=$numberOfPages; $i++) { ?>

                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="fee_list.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>

                        <?php } ?>
                        <!--                    <li class="page-item"><a class="page-link" href="#">Next</a></li>-->
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</section>


<script type="text/javascript">
    function deleteRecord(id){
        var conf = confirm("Are you sure you want to delete ?");
        if(conf == true){
            window.location = "fee_delete.php?id="+id;
        }
        else {
            return false;
        }
    }
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
</html>