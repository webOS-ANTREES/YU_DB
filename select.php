<?php
$con = mysqli_connect("127.0.0.1", "hgkim", "hg011204", "database_project") or die("MySQL 접속 실패 !!");

$sql = "SELECT * FROM user";

$ret = mysqli_query($con, $sql);
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "userTBL 데이터 조회 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit();
}

echo "<h1>회원 조회 결과</h1>";
echo "<TABLE border=1>";
echo "<TR>";
echo "<TH>회원번호</TH><TH>이름</TH><TH>아이디</TH><TH>비밀번호</TH><TH>이메일</TH><TH>역활</TH></TH><TH>수정</TH><TH>삭제</TH>";
echo "</TR>";

while ($row = mysqli_fetch_array($ret)) {
    echo "<TR>";
    echo "<TD>", $row['Email'], "</TD>";
    echo "<TD>", $row['User_ID'], "</TD>";
    echo "<TD>", $row['ID'], "</TD>";
    echo "<TD>", $row['Password'], "</TD>";
    echo "<TD>", $row['Name'], "</TD>";
    echo "<TD>", $row['Role'], "</TD>";
    echo "<TD><a href='update.php?userID=", $row['userID'], "'>수정</a></TD>";
    echo "<TD><a href='delete.php?userID=", $row['userID'], "'>삭제</a></TD>";
    echo "</TR>";
}

mysqli_close($con);
echo "</TABLE>";
echo "<br> <a href='main.html'>←초기 화면</a>";
?>

