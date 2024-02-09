<?php
if(!isset($_SERVER['HTTP_REFERER'])){
	header('location:login.php');
}
session_start();
if (session_status() == PHP_SESSION_NONE || !isset($_SESSION['username'])) {
   header("location:login.php");
   exit;
}
include 'assets/include/db.php';
$username=$_SESSION['username'];
$get=mysqli_query($conn,"SELECT * FROM login WHERE username='$username'");
$fetch=mysqli_fetch_array($get);
$unit=$fetch['unit'];
$role=$fetch['access_level'];
if($role==1){
include 'assets/include/header_admin.php';}elseif($role==2){
	include 'assets/include/header.php';
}elseif($role==3){
	include 'assets/include/header_result.php';
}
?>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Validation wizard -->
                <div class="row" id="validation">
                    <div class="col-12">
                    <div class="form-group">
    <input id="myInput" class="form-control" oninput="w3.filterHTML('#id01', '.item', this.value)" placeholder="Search.."  type="text" >
                </div>
                <form action="<?php if ($role==1){ echo 'excel_director.php';}else{ echo 'create_excel.php?node='.$unit;}?>" method="POST">
<div style="padding-bottom:3px" class="col-12">
                <button  class="btn btn-info" type="submit" name="export" id="export" value="submit">Export Excel</button>
                </form></div>

</div>
    <?php
    require_once("assets/include/dbcontroller.php");
    require_once("assets/include/core.php");
	$db_handle = new DBController();
if($role==1){	$sql = "SELECT *  from application_info
        INNER JOIN personal_data ON application_info.applicant_id=personal_data.applicant_id INNER JOIN payment ON personal_data.email=payment.email WHERE admission_status='recommended' AND status='success'";}elseif($role==2){$sql = "SELECT *  from application_info
        INNER JOIN personal_data ON application_info.applicant_id=personal_data.applicant_id INNER JOIN payment ON personal_data.email=payment.email WHERE admission_status='pending' AND status='success' AND program_applied='$unit'";}
    $resultset = $db_handle->runSelectQuery($sql);
    // var_dump($resultset);
    ?>
 <table class="table">
                            <thead>
                            <th>S/N</th>
                            <th>Application Number</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Programme</th>
                            <th>Center</th>
                            <th>Study Mode</th>
                            <th>Action</th>
                            </thead>
                            <tbody id="id01">


                    <?php
    $sn = 0;
    if(!empty($resultset)) {
    foreach($resultset as $key){
        $sn +=1;
        $applicant_id  = $key['applicant_id'];

     echo '
     <tr class="item">
     <td>'.$sn.'</td>
     <td>'.$applicant_id.'</td>
     <td>'.$key['fname'] .' '.$key['sname'].' '.$key['oname'].'
     </td>
     <td>'.$key['phone'].'</td>

     <td>'.$key['program_applied'].'</td>
          <td>'.$key['study_center'].'</td>
          <td>'.$key['study_mode'].'</td>
     <td><a  class="btn btn-info" data-toggle="modal" href="#modal'.$sn.'" >View</a></td>
';

if($role==2){echo'<form action ="#" method="POST">
     <td> <button id="recommend" class="btn btn-success" type="submit" name="recommend" value="recommended">Recommend</button></td>
     <input type="hidden" value="'.$applicant_id.'" name="applicant_id" />
</form>';}elseif($role==1){echo'<form action ="#" method="POST">
     <td> <button id="approve" class="btn btn-success" type="submit" name="approve" value="approved">Approve</button></td>
     <input type="hidden" value="'.$applicant_id.'" name="applicant_id" />
</form>';}
echo'<form action ="#" method="POST">
     <td> <button id="deny" class="btn btn-danger" type="submit" name="deny" value="denied" >Deny</button></td>
        <input type="hidden" value="'.$applicant_id.'" name"applicant_id" />
      </form>
 </tr>


 <div class="modal fade" id="modal'.$sn.'" role="dialog">
 <div class="modal-dialog modal-lg">

   <!-- Modal content-->
   <div class="modal-content">
     <div class="modal-header ">
     <div class="row text-center">
     <button type="button" id="'.$applicant_id.'" class="btn btn-info" onclick="printDoc(this.id)">Print PDF</button>
     </div>

       <button type="button" class="close" data-dismiss="modal">&times;</button>

     </div>
     <div class="modal-body">

     <div class="container-fluid">

                <script>
                    function printDoc(id) {
                        window.open("print.php?applicant_id="+applicant_id,"Print","toolbar=yes,scrollbars=yes, resizable=yes, top=500, left=500, width=800, height=700");
                    }
</script>

                <div class="row text-center" id="validation">

                    <div class="col-12">
                        <div class="card wizard-content">
                            <div class="card-body">

                            <br>
                                <h4 class="card-title"> e- application </h4>
                                <div class="col-md-12">
                                <div class="profile-img"> <img style="width:75px; height:75px" src="'.$key['pic_url'].'" alt="user" /> </div>
                                    </div>

                                <p>Application no: '.$applicant_id.'</p>
                                <!-- <form action="#" class="validation-wizard wizard-circle"> -->
                                <form action="#" class="">
                                    <!-- Step 1 -->
                                    <h6>Personal Data</h6>
                                    <section>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> First Name
                                                    </label>
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$key['fname'].'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wlastName2"> Surname Name
                                                    </label>
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$key['sname'].'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="wlastName2"> Other Name
                                                </label>
                                                <input type="text" class="form-control " id="wlastName2"
                                                    placeholder=" '.$key['oname'].'" readonly> </div>
                                        </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email"> Email Address :  </label>
                                                    <input type="email" class="form-control "
                                                        id="email" name="email" placeholder=" '.$key['email'].'" readonly> </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number
													</label>
                                                    <input type="tel" class="form-control " id="phone" placeholder=" '.$key['phone'].'" readonly> </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gender"> Gender
                                                    </label>
                                                    <input class=" form-control required" id="gender"
                                                        name="gender" placeholder=" '.$key['gender'].'" readonly>

                                                        </input>
                                                </div>
                                            </div>
											
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state"> State of Origin :
                                                    </label>
                                                    <input type="text" class="form-control " id="state"
                                                        name="state" placeholder=" '.$key['state'].'" readonly> 
                                                </div>
                                            </div>
                                        </div>

										<div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lga"> Local Government :
                                                    </label>
                                                    <input type="text" class="form-control " id="lga"
                                                        name="lga" placeholder=" '.$key['lga'].'" readonly> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wdate2">Date of Birth </label>
                                                    <input type="text" class="form-control"  placeholder=" '.$key['dob'].'" readonly> 
                                                </div>
                                            </div>
										</div>

                                        <div class="row">	 

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="program">Programme :
                                                    </label>
                                                    <input class=" form-control required" id="program"
                                                        name="program"  placeholder=" '.$key['program_applied'].'" readonly>

                                                        </input >
                                                </div>
                                            </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="group">Group :
                                                    </label>
                                                    <input class=" form-control required" id="discipline"
                                                        name="group"  placeholder=" '.$key['group'].'" readonly>

                                                        </input >
                                                </div>
                                            </div>

											<div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address">Contact Address :
													</label>
                                                    <input name="shortDescription" id="address" placeholder=" '.$key['address'].'" class="form-control " readonly></input>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Step 2 -->
<script>document.getElementById("academic").style.pageBreakAfter = "always";</script>
                                    <hr>
';
$sql = "SELECT *  from application_info
INNER JOIN a_level ON application_info.applicant_id=a_level.applicant_id
WHERE application_info.applicant_id='$applicant_id' ";
$resultset = $db_handle->runSelectQuery($sql);
foreach($resultset as $key){

echo '
                                    <h6 id="academic">Academic Record</h6>
                                    <section>
																				<h4 class="card-title">A level</h4>

									 <div class="row">
                                    <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                        <label>Insitution</label>
                                            <input type="text" class="form-control"  placeholder=" '.$key['institution'].'" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                        <label>Certificate Obtained</label>

                                            <input type="text" class="form-control" placeholder=" '.$key['cert_obtained'].'" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                        <label>Date of Award</label>
                                            <input type="text" class="form-control" placeholder=" '.$key['date_of_award'].'" readonly>
                                        </div>
                                    </div>

                                    </div>
                                      <hr>
                                <div class="row">

                                ';
}

$result1=mysqli_query($conn,"SELECT exam_type,year,center_no,center_name,exam_no FROM o_level WHERE applicant_id='$applicant_id' AND sitting='First' LIMIT 1");
$row1=mysqli_fetch_array($result1);
if($row1>0){
    $db_exam_type1=$row1['exam_type'];
    $db_year1=$row1['year'];
    $db_center_no1=$row1['center_no'];
    $db_center_name1=$row1['center_name'];
    $db_exam_no1=$row1['exam_no'];
}else{
    $db_exam_type1='';
    $db_year1='';
    $db_center_no1='';
    $db_center_name1='';
    $db_exam_no1='';
}

$result2=mysqli_query($conn,"SELECT exam_type,year,center_no,center_name,exam_no FROM o_level WHERE applicant_id='$applicant_id' AND sitting='Second' LIMIT 1");
$row2=mysqli_fetch_array($result2);
if($row2>0){
    $db_exam_type2=$row2['exam_type'];
    $db_year2=$row2['year'];
    $db_center_no2=$row2['center_no'];
    $db_center_name2=$row2['center_name'];
    $db_exam_no2=$row2['exam_no'];
}else{
    $db_exam_type2='';
    $db_year2='';
    $db_center_no2='';
    $db_center_name2='';
    $db_exam_no2='';
}



echo '
<h6 id="academic"></h6>
                                    <section>
                                                                                <h4 class="card-title">O level</h4>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <!-- First column content goes here -->
      <p> First Sitting </p>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> EXAM:
                                                    </label>
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$db_exam_type1.'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wlastName2"> YEAR:
                                                    </label>
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$db_year1.'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="wlastName2"> CENTER:
                                                </label>
                                                <input type="text" class="form-control " id="wlastName2"
                                                    placeholder=" '.$db_center_name1.'" readonly> </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> CENTER NO:
                                                    </label>
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$db_center_no1.'" readonly> </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlastName2"> EXAM NO:
                                                    </label>
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$db_exam_no1.'" readonly> </div>
                                            </div>

                                        </div>';

                                $sql = "SELECT subject, grade FROM o_level WHERE applicant_id = '$applicant_id' AND sitting = 'First'";
                                $result = mysqli_query($conn, $sql);

                                    $i=1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $subject = $row['subject'];
                                        $grade = $row['grade'];
                                        echo'
                                        <div class="row">
                                            ' . $i . '. ' . '
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$subject.'" readonly> </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$grade.'" readonly> </div>
                                            </div>

                                        </div>';
                                    $i++;}

echo '
    </div>

    <div class="col-md-6">
      <!-- Second column content goes here -->
      <p> Second Sitting </p>
      <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> EXAM:
                                                    </label>
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$db_exam_type2.'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wlastName2"> YEAR:
                                                    </label>
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$db_year2.'" readonly> </div>
                                            </div>

                                            <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="wlastName2"> CENTER:
                                                </label>
                                                <input type="text" class="form-control " id="wlastName2"
                                                    placeholder=" '.$db_center_name2.'" readonly> </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> CENTER NO:
                                                    </label>
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$db_center_no2.'" readonly> </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlastName2"> EXAM NO:
                                                    </label>
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$db_exam_no2.'" readonly> </div>
                                            </div>

                                        </div>';
                                        $sql = "SELECT subject, grade FROM o_level WHERE applicant_id = '$applicant_id' AND sitting = 'Second'";
                                        $result = mysqli_query($conn, $sql);

                                    $i=1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $subject = $row['subject'];
                                        $grade = $row['grade'];
                                        echo'
                                        <div class="row">
                                            ' . $i . '. ' . '
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control " id="wfirstName2"
                                                      placeholder="'.$subject.'" readonly> </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control " id="wlastName2"
                                                        placeholder="'.$grade.'" readonly> </div>
                                            </div>

                                        </div>';
                                    $i++;}

    echo '
    </div>
  </div>
</div>
</div>

                          ';

/*
	$sql = "SELECT *  from application_info
    INNER JOIN o_level ON application_info.applicant_id=o_level.applicant_id
    WHERE application_info.applicant_id='$applicant_id' ";
$resultset = $db_handle->runSelectQuery($sql);

foreach($resultset as $key2){

    echo '
    <div class="row">
    
                                        <div class="col-sm-7 nopadding">
                                        <label>Subject</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="Degree" name="Degree[]" placeholder="'.$key2['subject'].'" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 nopadding">
                                        <label>Grade</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="Degree" name="Degree[]" placeholder="'.$key2['grade'].'" readonly>
                                            </div>
                                        </div>
                                        </div>

    ';

}*/

$sql = "SELECT *  from application_info
INNER JOIN employement_record ON application_info.applicant_id=employement_record.applicant_id
WHERE application_info.applicant_id='$applicant_id' ";
$resultset = $db_handle->runSelectQuery($sql);
if(!empty($resultset)) {
foreach($resultset as $key){


                                echo '
                                    </section>
                                    <!-- Step 3 -->
                                    <hr>
                                    <h4 class="card-title">Employement Record</h4>
                                                                       <section>

                                    <div class="row">
                                        <div class="col-sm-4 nopadding">
                                        <label>Name of Employer</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="Schoolname" placeholder="'.$key['name_of_employer'].'" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 nopadding">
                                    <label>Position</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="Major" name="Major[]" placeholder="'.$key['position'].'" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 nopadding">
                                    <label>Date</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="Degree" name="Degree[] "placeholder="'.$key['date'].'" readonly>
                                        </div>
                                    </div>  </div>
									</section>
                                    <!-- Step 4 -->
                                    <hr>
                                    <h4 class="card-title">Refrees</h4>
                                    <section>

                        ';
                    }
                }

                        $sql = "SELECT *  from application_info
                        INNER JOIN referee ON application_info.applicant_id=referee.applicant_id
                        WHERE application_info.applicant_id='$applicant_id' ";
                    $resultset = $db_handle->runSelectQuery($sql);
                    if(!empty($resultset)) {
                    foreach($resultset as $key3){
                            echo '
                            <div class="row">
                            <div class="row">
<div class="col-sm-4 nopadding">
  <div class="form-group">
  <label>Name</label>
      <input type="text" class="form-control" placeholder="'.$key3['name'].'" readonly>
  </div>
</div>

<div class="col-sm-4 nopadding">
  <div class="form-group">
  <label>Email</label>
      <input type="text" class="form-control" placeholder="'.$key3['email'].'" readonly>
  </div>
</div>
<div class="col-sm-3 nopadding">
<label>Phone</label>
  <div class="form-group">
      <input type="text" class="form-control" placeholder="'.$key3['phone'].'" readonly>
  </div>
</div>
<div class="col-sm-6 nopadding">
<label>Address</label>
  <div class="form-group">
      <input type="text" class="form-control" placeholder="'.$key3['address'].'" readonly>
  </div>
</div>
<div class="col-sm-6 nopadding">
<label>Status</label>
  <div class="form-group">
      <input type="text" class="form-control" placeholder="'.$key3['status'].'" readonly>
  </div>
</div>
</div>
                            ';
                    }
                }
                              echo '
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                </div>


     </div>
     <div class="modal-footer">
     <button type="button" id="printBtn" class="btn btn-info" onclick="printDoc()">Print Page</button>
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
   </div>

 </div>
</div>

     ';

        }
    }
                    ?>


                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
<?php include 'assets/include/footer.php'; ?>
							<!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="js/jquery-3.2.1.min.js" ></script>
	<script src="js/jquery.min.js"></script>
    <script src="w3.js"></script>
<style>
    #myInput {

}



</style>

<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
    <script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.html"></script><script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="assets/plugins/moment/moment.js"></script>
    <script src="assets/plugins/wizard/jquery.steps.min.js"></script>
    <script src="assets/plugins/wizard/jquery.validate.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="assets/plugins/sweetalert/sweetalert.min.html"></script>
    <script src="assets/plugins/wizard/steps.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>


<!-- Mirrored from demo.galaxycom.org/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 09 Sep 2019 12:14:37 GMT -->
</html>

<?php
if(isset($_POST['recommend'])){
        //session_start();
        //$user=$_SESSION['username'];
        $applicant_id=$_POST['applicant_id'];
        //$status=$_POST['status'];
        $getemail=mysqli_query($conn,"SELECT * FROM personal_data WHERE applicant_id='$applicant_id'");
        $fetch=mysqli_fetch_array($getemail);
        $email=$fetch['email'];
        $fname=$fetch['fname'];
        $session=DATE("Y");
        $sql=mysqli_query($conn,"UPDATE application_info SET admission_status='recommended' WHERE applicant_id='$applicant_id'");
        echo "<script>alert('Recommended to the director successfully'); location.href='index.php';</script>";
}
elseif(isset($_POST['approve'])){
    $date_approved=(date("d-m-Y"));
        //session_start();
        //$user=$_SESSION['username'];
        $applicant_id=$_POST['applicant_id'];
        //$status=$_POST['status'];
        $getemail=mysqli_query($conn,"SELECT * FROM personal_data WHERE applicant_id='$applicant_id'");
        $fetch=mysqli_fetch_array($getemail);
        $email=$fetch['email'];
        $fname=$fetch['fname'];
        $session=DATE("Y");
        $sql=mysqli_query($conn,"UPDATE application_info SET admission_status='Approved', date_approved='$date_approved' WHERE applicant_id='$applicant_id'");
                //send email
require_once 'assets/include/phpmailer/src/Exception.php';
require_once 'assets/include/phpmailer/src/PHPMailer.php';
require_once 'assets/include/phpmailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer\PHPMailer\PHPMailer();
$program=$fetch['program_applied'];
//$discipline=$fetch['discipline'];
$center=$fetch['study_center'];
$amount= "&#8358;65,000";//////////////Registration fees to be agreed upon
// Set the email message
$message = "Dear " . $fname . ",<br><br>";
$message .= "Following a thorough review of your application, we are pleased to inform you that you have been admitted to ".$program." PROGRAMME. Please note the following:<br><br>";
  if($center=='DUTSE'){$message .= "<ol><li>Proceed to the Consultancy Services Directorate of Federal University Dutse with your original academic credentials for screening and collection of admission letter.</li>
    <li>Make a payment of registration fees at Federal University Dutse Microfinance Bank through the following account details:
<ul><li><b>Account Name: FUD (Remedial Programme)</b></li>
    <li><b>Account Number: 1400002880</b></li>
    <li><b>Bank Name: Federal University Dutse Microfinance Bank</b></li>";
    $message.="<li><b>Amount: ".$amount."<b></li>
</ul>
    </li>";
    $message.="<li>Present an evidence of bank payment at the Consultancy Services Directorate of Federal University Dutse for collection of recipet.</li>
  <br>";
  }elseif($center=='KANO'){
    $message.="<ol><li>Proceed to Kano Remedial Study Center, No.268, Daurawa,Maiduguri road,Kano with your original academic credentials for screening and collection of admission letter.</li>
    <li>Make a payment of registration fees at any branch of Fidelity Bank Nationwide through the following account details:
<ul><li><b>Account Name: Federal University Dutse Consultancy Services Limited</b></li>
    <li><b>Account Number: 4011303759</b></li>
    <li><b>Bank Name: Fidelity Bank</b></li>";
    $message.="<li><b>Amount: ".$amount."<b></li>
</ul>
    </li>";
    $message.="<li>Present an evidence of bank payment at Kano Remedial Study Center,No.268, Daurawa,Maiduguri road,Kano for collection of recipet.</li>
  <br>";
}
$message .= "<br><i>Congratulations!</i><br>";
$message .= "Admission Committee.";
// Set the email parameters
$mail->setFrom('no-reply@fudcons.com', 'FUDCONS');
$mail->addAddress($email, $fname);
$mail->Subject = 'NOTIFICATION OF PROVISIONAL OFFER OF ADMISSION';
$mail->msgHTML($message);

// Send the email
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo "<script>alert('Notification successfully sent to $email.'); location.href='index.php';</script>";
}


    }elseif(isset($_POST['deny'])){
        //session_start();
        //$user=$_SESSION['username'];
        $applicant_id=$_POST['applicant_id'];
        //$status=$_POST['status'];
        $getemail=mysqli_query($conn,"SELECT * FROM personal_data WHERE applicant_id='$applicant_id'");
        $fetch=mysqli_fetch_array($getemail);
        $email=$fetch['email'];
        $fname=$fetch['fname'];
        $session=DATE("Y");
        //if($status=='approved'){
        $sql=mysqli_query($conn,"UPDATE application_info SET admission_status='Denied' WHERE applicant_id='$applicant_id'");
                 //send email
require_once 'assets/include/phpmailer/src/Exception.php';
require_once 'assets/include/phpmailer/src/PHPMailer.php';
require_once 'assets/include/phpmailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer\PHPMailer\PHPMailer();
// Set the email message
$message = "Dear ".$fname;
$message .= '<p align="justify">Thank you for your interest in our '.$program.' program.Following a thorough review of your application, we regret to inform you that your application was not succesful. We wish you the very best in your future endevours.</p>';
$message .='<p align="left"><i> Thank you,<br>Admission Team.</i></p>';
// Set the email parameters
$mail->setFrom('no-reply@fudcons.com', 'FUDCONS');
$mail->addAddress($email, $fname);
$mail->Subject = 'Admission Status';
$mail->msgHTML($message);

// Send the email
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo "<script>alert('Notification successfully sent to $email.'); location.href='index.php';</script>";
}

    }


?>