<?php
// DB 연결
$con = mysqli_connect("127.0.0.1", "hgkim", "hg011204", "database_project") or die("MySQL 접속 실패 !!");

// 기존 데이터 삭제
mysqli_query($con, "DELETE FROM User");
mysqli_query($con, "ALTER TABLE User AUTO_INCREMENT = 1"); // AUTO_INCREMENT 초기화

mysqli_query($con, "DELETE FROM Board");
mysqli_query($con, "ALTER TABLE Board AUTO_INCREMENT = 1"); // AUTO_INCREMENT 초기화

echo "기존 데이터 삭제 완료!<br>";

// Board 테이블에 데이터 삽입
$board_sql = "
INSERT IGNORE INTO Board (Board_ID, Post_Num) VALUES
(1, NULL), 
(2, NULL), 
(3, NULL)
";
mysqli_query($con, $board_sql);

// User 테이블에 데이터 삽입 (반복문 사용)
$insert_base_sql = "INSERT INTO User (Email, User_ID, ID, Password, Name, Role, Board_Board_ID) VALUES ";

$values = [];
for ($i = 1; $i <= 1600; $i++) {
    $email = "user{$i}@example.com";
    $user_id = $i; // 고유한 User_ID
    $id = "user{$i}";
    $password = "password{$i}";
    $name = "사용자{$i}";
    $role = $i % 2 === 0 ? "user" : "system_manager"; // 짝수는 user, 홀수는 system_manager
    $board_id = ($i % 3) + 1; // Board_Board_ID는 1, 2, 3 순환

    $values[] = "('$email', '$user_id', '$id', '$password', '$name', '$role', $board_id)";
}

// 값 묶음으로 SQL 실행
$chunk_size = 100; // 한 번에 삽입할 레코드 수
for ($start = 0; $start < count($values); $start += $chunk_size) {
    $chunk = array_slice($values, $start, $chunk_size);
    $insert_sql = $insert_base_sql . implode(", ", $chunk);

    $insert_ret = mysqli_query($con, $insert_sql);
    if (!$insert_ret) {
        echo "데이터 추가 실패! 실패 원인: " . mysqli_error($con) . "<br>";
        break; // 실패 시 중단
    }
}

echo "1600개의 데이터가 성공적으로 추가되었습니다!<br>";

// 데이터 조회 쿼리
$sql = "SELECT * FROM User LIMIT 10"; // 출력 제한 (10개)
$ret = mysqli_query($con, $sql);
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "User 테이블 데이터 조회 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit();
}

// 테이블 출력
echo "<h1>회원 조회 결과 (상위 10개)</h1>";
echo "<TABLE border=1>";
echo "<TR>";
echo "<TH>회원번호</TH><TH>이름</TH><TH>아이디</TH><TH>비밀번호</TH><TH>이메일</TH><TH>역할</TH><TH>수정</TH><TH>삭제</TH>";
echo "</TR>";

while ($row = mysqli_fetch_array($ret)) {
    echo "<TR>";
    echo "<TD>", $row['User_ID'], "</TD>";
    echo "<TD>", $row['Name'], "</TD>";
    echo "<TD>", $row['ID'], "</TD>";
    echo "<TD>", $row['Password'], "</TD>";
    echo "<TD>", $row['Email'], "</TD>";
    echo "<TD>", $row['Role'], "</TD>";
    echo "<TD><a href='update.php?User_ID=", $row['User_ID'], "'>수정</a></TD>";
    echo "<TD><a href='delete.php?User_ID=", $row['User_ID'], "'>삭제</a></TD>";
    echo "</TR>";
}

mysqli_close($con);
echo "</TABLE>";
echo "<br> <a href='file:///C:/xampp/htdocs/HTML_Code/Login.html'>←초기 화면</a>";
?>
