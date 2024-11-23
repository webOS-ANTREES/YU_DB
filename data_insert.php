<?php
// DB 연결
$con = mysqli_connect("127.0.0.1", "hgkim", "hg011204", "database_project") or die("MySQL 접속 실패 !!");

// 참조 데이터 삭제
mysqli_query($con, "DELETE FROM Sub_Task"); // Sub_Task 삭제
mysqli_query($con, "DELETE FROM Task"); // Task 삭제
mysqli_query($con, "DELETE FROM Project"); // Project 삭제
mysqli_query($con, "DELETE FROM User"); // User 삭제
mysqli_query($con, "DELETE FROM Board"); // Board 삭제
mysqli_query($con, "ALTER TABLE User AUTO_INCREMENT = 1"); // User AUTO_INCREMENT 초기화
mysqli_query($con, "ALTER TABLE Board AUTO_INCREMENT = 1"); // Board AUTO_INCREMENT 초기화

echo "기존 데이터 삭제 완료!<br>";

// Board 테이블에 데이터 삽입
$board_sql = "
INSERT INTO Board (Board_ID, Post_Num) VALUES
(1, 10)
";
if (!mysqli_query($con, $board_sql)) {
    echo "Board 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}

// Project 테이블에 데이터 삽입
$project_sql = "
INSERT INTO Project (Project_ID, Project_Version, Name, Description, Start_Date, Total_Time, Project_Member) VALUES
(1, 1.0, 'Project A', '프로젝트 A 설명', NOW(), 100, '사용자1, 사용자2');
";
if (!mysqli_query($con, $project_sql)) {
    echo "Project 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}

// Task 테이블에 데이터 삽입
$task_values = [];
for ($i = 1; $i <= 10; $i++) {
    $task_id = $i; // Task_ID는 1~10
    $task_version = 1.0; // Task_Version은 고정
    $task_name = "Task {$i}";
    $task_values[] = "($task_id, $task_version, 1, '$task_name', '태스크 {$i} 설명', NOW(), DATE_ADD(NOW(), INTERVAL $i DAY), $i*10, '사용자1, 사용자2', NULL, 'pending')";
}
$task_sql = "INSERT INTO Task (Task_ID, Task_Version, Project_ID, Name, Description, Start_Date, End_Date, Sub_Total_Time, Task_Member, K, Task_Status) VALUES " . implode(", ", $task_values);
if (!mysqli_query($con, $task_sql)) {
    echo "Task 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}
echo "Task 데이터 삽입 완료!<br>";

// User 테이블에 데이터 삽입
$user_values = [];
for ($i = 1; $i <= 1600; $i++) {
    $email = "user{$i}@example.com";
    $user_id = $i;
    $id = "user{$i}";
    $password = "password{$i}";
    $name = "사용자{$i}";
    $role = $i % 2 === 0 ? "user" : "system_manager";
    $board_id = 1;
    $user_values[] = "('$email', $user_id, '$id', '$password', '$name', '$role', $board_id)";
}
$user_sql = "INSERT INTO User (Email, User_ID, ID, Password, Name, Role, Board_Board_ID) VALUES " . implode(", ", $user_values);
if (!mysqli_query($con, $user_sql)) {
    echo "User 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}
echo "User 데이터 삽입 완료!<br>";

// Sub_Task 초기 데이터 삽입
$sub_task_sql = "
INSERT INTO Sub_Task 
(Sub_Task_ID, Sub_Task_Version, Task_ID, Advanced_Task_ID, Time, Sub_Start_Date, Name, Member_ID, Sub_Task_member_name, Sub_Status) 
VALUES 
(1, 1.0, 1, NULL, 5, NOW(), 'Sub_Task 1', 1, 'user1', 'pending'); 
";

if (mysqli_query($con, $sub_task_sql)) {
    echo "초기 Sub_Task 데이터 삽입 완료!<br>";
} else {
    echo "Sub_Task 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}
echo "Enter 3 Sub_Task !<br>";

// 이미 1번 데이터를 삽입했으므로 4번부터 시작
for ($i = 2; $i <= 100; $i++) {
    $sub_task_id = $i; // Sub_Task_ID는 4~100 고유 값
    $task_id = ($i % 10) + 1; // Task 1~10에 분배
    $advanced_task_id = $i - 1; // 항상 이전 Sub_Task_ID를 참조
    $sub_task_name = "Sub_Task {$i}";

    // 데이터 삽입
    $sub_task_sql = "
        INSERT INTO Sub_Task 
        (Sub_Task_ID, Sub_Task_Version, Task_ID, Advanced_Task_ID, Time, Sub_Start_Date, Name, Member_ID, Sub_Task_member_name, Sub_Status) 
        VALUES 
        ($sub_task_id, 1.0, $task_id, $advanced_task_id, 5, 50, '$sub_task_name', $i, '사용자{$i}', 'pending')
    ";

    if (!mysqli_query($con, $sub_task_sql)) {
        echo "Sub_Task 데이터 삽입 실패 (ID: $sub_task_id)! 오류: " . mysqli_error($con) . "<br>";
        exit;
    }
}
echo "Sub_Task 데이터 삽입 완료!<br>";
 

// postTBL 테이블에 데이터 삽입
$post_values = [];
for ($i = 1; $i <= 10; $i++) {
    $title = "Post Title {$i}";
    $content = "Post Content {$i}";
    $post_values[] = "(NULL, 1, NULL, NULL, NULL, NULL, 1, 1.0, '$title', '$content', NOW(), NOW(), 'post', NULL, 0, 0, FALSE)";
}
$post_sql = "INSERT INTO postTBL (post_id, Board_ID, Sub_Task_ID, Sub_Task_Version, Task_ID, Task_Version, Project_ID, Project_Version, title, content, created_at, updated_at, type, file_path, views, likes, notify_on_update) VALUES " . implode(", ", $post_values);
if (!mysqli_query($con, $post_sql)) {
    echo "Post 데이터 삽입 실패! 오류: " . mysqli_error($con) . "<br>";
    exit;
}
echo "Post 데이터 삽입 완료!<br>";


// 데이터 조회
$project_ret = mysqli_query($con, "SELECT * FROM Project");
$task_ret = mysqli_query($con, "SELECT * FROM Task LIMIT 10");
$sub_task_ret = mysqli_query($con, "SELECT * FROM Sub_Task LIMIT 10");
$post_ret = mysqli_query($con, "SELECT * FROM postTBL LIMIT 10");
$user_ret = mysqli_query($con, "SELECT * FROM User LIMIT 10");

// 결과 출력
echo "<h1>Project 데이터</h1>";
while ($row = mysqli_fetch_assoc($project_ret)) {
    print_r($row);
    echo "<br>";
}

echo "<h1>Task 데이터 (상위 10개)</h1>";
while ($row = mysqli_fetch_assoc($task_ret)) {
    print_r($row);
    echo "<br>";
}

echo "<h1>Sub_Task 데이터 (상위 10개)</h1>";
while ($row = mysqli_fetch_assoc($sub_task_ret)) {
    print_r($row);
    echo "<br>";
}

echo "<h1>Post 데이터 (상위 10개)</h1>";
while ($row = mysqli_fetch_assoc($post_ret)) {
    print_r($row);
    echo "<br>";
}

echo "<h1>User 데이터 (상위 10개)</h1>";
while ($row = mysqli_fetch_assoc($user_ret)) {
    print_r($row);
    echo "<br>";
}

mysqli_close($con);
?>
