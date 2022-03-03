<?php
require_once 'header.php';
require_once '../server/classes/fetchData.php';
$id = $_GET['id'];
$_SESSION['pageid'] = $id;

$host = "localhost";
$username = "root";
$password = "";
$dbname = "e-learning";
$connect;

try {
    $connect = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
} catch (PDOException $ex) {
    echo $ex->getMessage();
};

$sql = "SELECT * FROM quiz WHERE id = $id";
$qry = $connect->prepare($sql);
$qry->execute(array());
$exam = $qry->fetch(PDO::FETCH_OBJ);
$_SESSION['exam_time'] = $exam->examdate;

$date = date("Y-m-d H:s:i");
$_SESSION['end_time'] = date("Y-m-d H:i:s", strtotime($date . "+$_SESSION[exam_time] minutes"));

$_SESSION['exam_start'] = 'yes';
?>
<script>
    function getData(question = '') {

        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: {
                question: question,
            },
            success: function(response) {
                //console.log(response);
                document.getElementById("paginate").innerHTML = response;
            }
        });
    }

    function saveAnswer(value, questionno) {
        $.ajax({
            type: 'post',
            url: 'save_answer.php',
            data: {
                value: value,
                questionno: questionno,
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        getData(1)

    });
</script>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="text-center">
        <h3>Take Quiz</h3>
        <h4>Answer Correctly the Questions</h4>
        <p>Please ensure you choose your answers correctly as any wrong choice can not be reversed after Submission.</p>
    </div>
    <div class="col-lg-8 offset-lg-2 mt-4">
        <?php

        $query = "SELECT * FROM question WHERE examid = $id";

        $stmt = $connect->prepare($query);
        $stmt->execute(array());
        $pages = $stmt->rowCount();
        $question = $stmt->fetch(PDO::FETCH_OBJ);
        
        ?>

        <form action="score.php" method="POST">
            <div id="paginate">
                <!-- load exam questions here -->
            </div>
            <div class="row pt-3">
                <div class="col-lg-6">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination " id="total">
                            <!-- <li class="page-item">
                                <?php (int)$i = 1; ?>
                                <a role="button" onclick="getData(<?php echo $i - 1, $_SESSION['pageid']; ?>)" class="page-link">Prev</a>
                            </li> -->
                            <?php for ($i; $i <= $pages; $i++) : ?>

                                <li class="page-item <?php echo
                                    @$_SESSION['page'] == $i ? 'active' : ''; ?>">
                                    <a role="button" class="page-link" onclick="getData(<?php echo $i ?>)" data-value="<?php echo $i ?>"><?php echo $i; ?></a>
                                </li>

                            <?php endfor; ?>
                            <!-- <li class="page-item">
                                <a role="button" onclick="getData(<?php echo $i + 1 ?>)" class="page-link">Next</a>
                            </li> -->
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-6 text-right">
                    <button class="btn btn-success text-light btn-rounded waves-effect waves-light" name="btnAdd" type="submit">Submit</button>
                </div>
            </div>
        </form>
    


    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
require_once 'footer.php';
?>

<!-- https://www.daniweb.com/programming/web-development/threads/508092/pagination-with-while-loop -->