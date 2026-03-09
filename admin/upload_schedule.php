<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    echo "Access denied";
    exit();
}

if(isset($_FILES['schedule_file'])){

    $file = $_FILES['schedule_file']['tmp_name'];

    if(($handle = fopen($file, "r")) !== FALSE){

        // Delete old schedule
        mysqli_query($conn, "DELETE FROM school_calendar");

        // Skip header row
        fgetcsv($handle);

        while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){

            $date = trim($data[0]);
            $type = trim($data[1]);

            $day_type = $type;
            $description = NULL;

            // Detect holiday with description
            if(strpos($type, "holiday(") === 0){

                $day_type = "holiday";

                preg_match('/holiday\((.*?)\)/', $type, $matches);

                if(isset($matches[1])){
                    $description = $matches[1];
                }

            }

            $stmt = $conn->prepare("INSERT INTO school_calendar (calendar_date, day_type, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $date, $day_type, $description);
            $stmt->execute();

        }

        fclose($handle);

        echo "<script>alert('Schedule uploaded successfully!'); window.location='schedule_manager.php';</script>";

    }else{
        echo "Error reading file.";
    }

}else{
    echo "No file uploaded.";
}
?>