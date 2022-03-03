<?php
require_once 'header.php';
$date = date("Y-m-d H:i:s");
$_SESSION['end_time'] = date("Y-m-d H:i:s", strtotime($date . "+$_SESSION[exam_time] minutes"));

$host = "localhost";
$username = "root";
$password = "";
$dbname = "e-learning";
$connect;

try {
    $connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
} catch (PDOException $ex) {
    echo $ex->getMessage();
};


$correct = 0;
$wrong = 0;

if (isset($_SESSION['answer'])) {
    for ($i = 1; $i <= sizeof($_SESSION['answer']); $i++) { 
        
        $answer = '';
        $query = "SELECT * FROM question WHERE examid = $_SESSION[pageid] && questionno = $i";
        $smt = $connection->prepare($query);
        $smt->execute(array());
        while ($result = $smt->fetch(PDO::FETCH_OBJ)) {
            $answer = $result->correct;
        }
       
        if (isset($_SESSION['answer'][$i])) {
            if ($answer == $_SESSION['answer'][$i]) {
                $correct = $correct + 1;
            }
            else{
                $wrong = $wrong + 1;
            }
        }
        else {
            $wrong = $wrong + 1;
        }
    }
}
$count = 0;
$query = "SELECT * FROM question WHERE examid = $_SESSION[pageid]";
$smt = $connection->prepare($query);
$smt->execute(array());
$count = $smt->rowCount();

$wrong = $count - $correct;

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center">Result</h1>
    <div style="margin: 0 auto;">
    
        <?php
        echo ("
            Total Question = $count
            <br>
            Correct Answer = $correct
            <br>
            Wrong Answer = $wrong
        "); ?>
    </div>
</div>
<?php 
    if (isset($_SESSION['exam_start'])) {
        $date = date("Y-m-d");
       $insert = "INSERT INTO exam_result (id, email, exam_id,total_questions, correct_answer, wrong_answer, exam_time) VALUES (NULL, :email, :exam_id, :total_questions, :correct_answer, :wrong_answer, :exam_time)";
        $query = $connection->prepare($insert);
        $query->execute(array(':email' => $_SESSION['email'],'exam_id' => $_SESSION['pageid'], ':total_questions' => $count, ':correct_answer' => $correct, ':wrong_answer' => $wrong, ':exam_time' => $date));
    }

    if (isset($_SESSION['exam_start'])) {
        unset($_SESSION['exam_start']);
        ?>
        <script>
            window.location.href = window.location.href;
        </script>
        <?php 
    }
?>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
require_once 'footer.php';
?>