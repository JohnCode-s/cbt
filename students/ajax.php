<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "e-learning";
$connect;

try {
    $connect =
        new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
} catch (PDOException $ex) {
    echo $ex->getMessage();
};

$ans = '';

if (isset($_POST['question'])) {
    $questions = $_POST['question'];
    $_SESSION['page'] = $questions;
    $id = $_SESSION['pageid'];
    $questions = $questions - 1;
    $query = ("SELECT * FROM question WHERE examid = :examid LIMIT $questions, 1 ");
    $smt = $connect->prepare($query);
    $smt->execute(array(':examid' => $id));
    $question = $smt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['questionno'] = $question['id'];
    
    if (isset($_SESSION['answer'][$question['questionno']])) {
        $ans = $_SESSION['answer'][$question['questionno']];
    }

    echo ('<div class="mb-3 px-3 py-2 Regular shadow rounded border border-dark" style="background: #fff;">
                    <p style="font-weight: bolder;font-size:larger;">
                        '.($questions + 1).' &nbsp;&nbsp;'.$question['questiontitle'].'
                    </p>
                    <div class="form-check px-1">
                        <div class="bg-light rounded py-2 my-2 pl-2">
                            <label class="form-check-label normal-label">
                                <input type="radio" class="mr-3" id="id_form-0-user_answer input" value="' . $question['option1'] .'" name="radAnswer" onclick="(saveAnswer(this.value,' . $question['questionno'] .'))" ' . ($ans == $question['option1'] ? 'checked' : '' ). '>'.$question['option1'].'
                            </label>
                        </div>
                        <div class="bg-light rounded py-2 my-2 pl-2">
                            <label class="form-check-label normal-label">
                                <input type="radio" class="mr-3" id="id_form-0-user_answer input" value="' . $question['option2'] .'" name="radAnswer" onclick="(saveAnswer(this.value,' . $question['questionno'] .'))" ' .( $ans == $question['option2'] ? "checked" : '') . '>'.$question['option2'].'
                            </label>
                        </div>
                        <div class="bg-light rounded py-2 my-2 pl-2">
                            <label class="form-check-label normal-label">
                                <input type="radio" class="mr-3" id="id_form-0-user_answer input" value="' . $question['option3'] .'" name="radAnswer" onclick="(saveAnswer(this.value,' . $question['questionno'] .'))" ' .( $ans == $question['option3'] ? "checked" : '') . '>'.$question['option3'].'
                            </label>
                        </div>
                        <div class="bg-light rounded py-2 my-2 pl-2">
                            <label class="form-check-label normal-label">
                                <input type="radio" class="mr-3" id="id_form-0-user_answer input" value="' . $question['option4'] .'" name="radAnswer" onclick="(saveAnswer(this.value,' . $question['questionno'] .'))" ' . ($ans == $question['option4'] ? "checked" : '') . '>'.$question['option4']. '
                            </label>
                        </div>
                    </div>
                </div>'
              );
               
    exit();
}
