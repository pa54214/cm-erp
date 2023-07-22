<?php
foreach ($storedArray as $studentId => $studentData) {
    // Get the exam subjects and subject results from the array
    $rank = $studentData['rank'];
    $examSubjects = $studentData['exam_subjects'];
    $subjectResults = $studentData['subject_results'];
    $studentDetails = $studentData['studentdetails'];
    $session = $studentData['session'];
    $template = $studentData['template'];
    $exams = $studentData['exams'];
    $info = $studentData['info'];
    $ttmarks = 0;
    $tsubtmax = 0;
    $tgrade = 0;
    //$trank = "Default";
?>
<div class="pagebreak">
    <div class="container" style="border: 2px solid black;">
        <div class="container">
            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->header_image); ?>" width="100%" height="300px;">
        </div>
        <div class="container text-center">
            <h3>Achievement Profile</h3>
            <h4>Academic Session : <?php echo $info['session_name']; ?></h4>
        </div>
        <div class="container">
            <table class="table" id="details">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center" style="padding-left: 180px;">Student Details</th>
                        <td rowspan="4"  class="text-right">
                            <img src="<?php echo $this->media_storage->getImageURL($studentDetails['image']); ?>" width="120" height="150">
                        </td>
                    </tr>
                    <tr>
                        <td>Student's Name : <?php echo $studentDetails['firstname']; ?></td>
                        <td>Adm. No. / Roll No. : <?php echo $studentDetails['admission_no']; ?> / <?php echo $studentDetails['roll_no']; ?></td>
                    </tr>
                    <tr>
                        <td>Father's Name : <?php echo $studentDetails['father_name']; ?></td>
                        <td>Class & Section : <?php echo $info['class_name'] . " - " . $info['section_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Mother's Name : <?php echo $studentDetails['mother_name']; ?></td>
                        <td>Attendance : <?php echo "N/A"; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container" id="marks">
            <table class="table">
                <thead>
                    <?php
                        // Get the unique exam IDs
                        $examIds = array_keys($examSubjects);
                        $totalExam = count($examIds);
                        ?>
                    <tr>
                        <th colspan="<?php echo $totalExam + 3; ?>" class="text-center">Scholastic Area</th>
                    </tr>
                    <tr class="text-center">
                        <th class="text-center">SUBJECT NAME</th>
                        <?php
                        $AllSubjectTotalMaxMarks = 0;
                        $subjectTotalMaxMarks = 0;
                        // Print the exam IDs as table headers
                        foreach ($exams as $exam) {
                            if(in_array($exam->id, $examIds)){
                                echo '<th class="text-center">';
                                foreach ($examSubjects[$exam->id] as $examSubject){
                                    $subjectMaxMarks = $examSubject->max_marks;
                                    $AllSubjectTotalMaxMarks += $subjectMaxMarks;
                                }
                                echo $exam->exam;
                                echo "<br>";
                                echo "(".$subjectMaxMarks.")";
                                $subjectTotalMaxMarks += $subjectMaxMarks;
                            }
                        }
                        ?>
                        <th class="text-center">Total<?php echo "<br>"; echo "(".$subjectTotalMaxMarks.")"; ?></th>
                        <th class="text-center">GRADE</th>
                        <!--<th class="text-center">RANK</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Create an array to store the subject names already printed
                    $printedSubjects = array();
                    // Iterate over the exam subjects to print the subject names and marks obtained
                    foreach ($examSubjects as $examSubjectGroup) {
                        foreach ($examSubjectGroup as $examSubject) {
                            $subjectId = $examSubject->subject_id;
                            $subjectName = $examSubject->subject_name;                        
                            // Check if the subject name has already been printed
                            if (!in_array($subjectName, $printedSubjects)) {
                                echo '<tr class="text-center">';
                                echo '<td class="text-left">' . $subjectName . '</td>';
                                // Iterate over the exam IDs to find marks obtained for the subject in each exam
                                foreach ($examIds as $examId) {
                                    $marksObtained = 'N/A'; // Default value if marks not found
                                    // Check if the subject results exist for the current exam ID
                                    if (isset($subjectResults[$examId])) {
                                        foreach ($subjectResults[$examId] as $subjectResult) {
                                            if ($subjectResult->subject_id === $subjectId) {
                                                if($subjectResult->attendence === "present"){
                                                    $marksObtained = $subjectResult->get_marks;
                                                    if($subjectResult->get_marks<$subjectResult->min_marks){
                                                        $status = true; 
                                                    }
                                                }else{
                                                    $marksObtained = "Absent";
                                                }
                                                break;
                                            }
                                        }
                                    }
                                    echo '<td class="text-center">'; echo $marksObtained; if($status){echo "F";$status = false;} echo'</td>';
                                    if($marksObtained!="Absent"){
                                        $tmarks += $marksObtained;
                                    }
                                }
                                // Add the subject name to the printed subjects array
                                $printedSubjects[] = $subjectName;
                                echo '<td class="text-center">' . $tmarks . '</td>';
                                $ttmarks += $tmarks;
                                echo '<td class="text-center">';
                                $grade = round(number_format((($tmarks/$subjectTotalMaxMarks)*100),2));
                                if ($grade >= 91 && $grade <= 100) {
                                    echo "A1";
                                } elseif ($grade >= 81 && $grade <= 90) {
                                    echo "A2";
                                } elseif ($grade >= 71 && $grade <= 80) {
                                    echo "B1";
                                } elseif ($grade >= 61 && $grade <= 70) {
                                    echo "B2";
                                } elseif ($grade >= 51 && $grade <= 60) {
                                    echo "C1";
                                } elseif ($grade >= 41 && $grade <= 50) {
                                    echo "C2";
                                } else {
                                    echo "Fail";
                                    //$rank = 'N/A';
                                }
                                echo'</td>';
                                /*echo '<td class="text-center">' . $rank . '</td>';*/
                            echo '</tr>';
                            $tmarks = 0;
                            }  
                        }    
                    }
                    ?>
                    <tr>
                        <td colspan="<?php echo $totalExam + 3; ?>" style="padding: 0px 5px;">8 Point Grading Scale: A1 (91%, 100%), A2 (81%, 90%), B1 (71%, 80%), B2 (61%, 70%), C1 (51%, 60%), C2 (41%, 50%)</td>
                    </tr>
                    <tr>
                        <td colspan="<?php echo $totalExam + 3; ?>" style="padding: 0px 5px;" class="text-right">
                            <strong style="font-weight: bold; font-size: 1em; color: #000;">Grand Total: <?php echo $ttmarks; ?> / <?php echo $AllSubjectTotalMaxMarks ?>&emsp;&emsp;Percentage: <?php
                              $tgrade = round(number_format((($ttmarks/$AllSubjectTotalMaxMarks)*100),2));
                              echo number_format((($ttmarks/$AllSubjectTotalMaxMarks)*100),2); ?> %&emsp;&emsp;Grade:
                              <?php
                              if ($tgrade >= 91 && $tgrade <= 100) {
                                  echo "A1";
                              } elseif ($tgrade >= 81 && $tgrade <= 90) {
                                  echo "A2";
                              } elseif ($tgrade >= 71 && $tgrade <= 80) {
                                  echo "B1";
                              } elseif ($tgrade >= 61 && $tgrade <= 70) {
                                  echo "B2";
                              } elseif ($tgrade >= 51 && $tgrade <= 60) {
                                  echo "C1";
                              } elseif ($tgrade >= 41 && $tgrade <= 50) {
                                  echo "C2";
                              } else {
                                  echo "Fail";
                                  //$trank = "N/A";
                              }
                              ?>
                              &emsp;&emsp;Rank : 
                              <?php 
                                if(isset($rank)){
                                    echo $rank;
                                }else{
                                    echo "N/A";
                                } 
                              ?>
                              &emsp;&emsp;
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container" id="other">
            <div class="row">
                <div class="col-xs-3" style="width: 48%; margin-right: 0;">CO-Scholastic Area (3 Point grading Scale A, B, C)
                    <table class="table">
                        <tbody>
                            <tr class="text-center">
                                <th class="text-center">Activity</th>
                                <th class="text-center">T1</th>
                            </tr>
                            <tr>
                                <td>Work Education</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Art Education</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Health Physical Education</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Scientific Skills</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Thinking Skills</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Social Skills</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Yoga/ NCC</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sports</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-3" style="width: 49%; margin-right: 0;">Discipline (3 Point grading Scale A, B, C)
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="text-center">Elements</th>
                                <th class="text-center">T1</th>
                            </tr>
                            <tr>
                                <td>Regularity & Punctuality</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sincerity</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Behavior & Value</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Respectfulness for Rules & Regulations</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attitude towards Teachers</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attitude towards School-mates</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attitude Towards Society</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attitude Towards Nation</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container">
            <b>Remarks :</b>
            <hr class="hr dark" />
        </div>
        <div class="container text-center">
            <div class="row">
                <div class="col-xs-3">
                    <div class="container text-center">
                        <?php
                        $date = $template->created_at;
                        //echo date_format($date,"d/m/Y H:i:s");
                        echo date('d-m-Y', strtotime($date));
                        ?>
                    </div>
                    <b>Date</b>
                </div>
                <div class="col-xs-5">
                    <div class="container text-center">
                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->left_sign); ?>" width="100px" height="50px">   
                    </div>
                    <b>Class Teacher Signature</b>
                </div>
                <div class="col-xs-3">
                    <div class="container text-center">
                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->right_sign); ?>" width="100px" height="50px">  
                    </div>
                    <b>Principal Signature</b>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
$this->session->unset_userdata('studentRanks');
?>
