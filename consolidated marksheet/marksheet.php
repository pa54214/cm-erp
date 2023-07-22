<div class="content-wrapper">

    <section class="content-header">

        <h1>

            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>  </h1>

    </section>

    <!-- Main content -->

    <section class="content">

        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-header with-border">

                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>

                    </div>

                    <div class="box-body">

                        <form role="form" action="<?php echo site_url('admin/examresult/marksheet') ?>" method="post" class="row" id="myForm">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group">

                                    <label><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>

                                    <select autofocus="" id="exam_group_id" name="exam_group_id" class="form-control select2" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        <?php

                                        foreach ($examgrouplist as $ex_group_key => $ex_group_value) {

                                            ?>

                                            <option value="<?php echo $ex_group_value->id ?>" <?php

                                            if (set_value('exam_group_id') == $ex_group_value->id) {

                                                echo "selected=selected";

                                            }

                                            ?>><?php echo $ex_group_value->name; ?></option>

                                                    <?php

                                                }

                                                ?>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>

                                </div>  

                            </div><!--./col-md-3-->

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group">   

                                    <label><?php echo $this->lang->line('exam'); ?></label><small class="req"> *</small>

                                    <select  id="exam_id" name="exam_id" class="form-control select2" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('exam_id'); ?></span>

                                </div>  

                            </div><!--./col-md-3-->

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group">  

                                    <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>

                                    <select  id="session_id" name="session_id" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        <?php

                                        foreach ($sessionlist as $session) {

                                            ?>

                                            <option value="<?php echo $session['id'] ?>" <?php

                                            if (set_value('session_id') == $session['id']) {

                                                echo "selected=selected";

                                            }

                                            ?>><?php echo $session['session'] ?></option>

                                                    <?php

                                                }

                                                ?>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>

                                </div>  

                            </div>

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group">   

                                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>

                                    <select id="class_id" name="class_id" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        <?php

                                        foreach ($classlist as $class) {

                                            ?>

                                            <option value="<?php echo $class['id'] ?>" <?php

                                            if (set_value('class_id') == $class['id']) {

                                                echo "selected=selected";

                                            }

                                            ?>><?php echo $class['class'] ?></option>

                                                    <?php

                                                }

                                                ?>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>

                                </div>  

                            </div>

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group"> 

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>

                                    <select  id="section_id" name="section_id" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>

                                </div>

                            </div>

                            <div class="col-sm-6 col-lg-4 col-md-4">

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('marksheet_template'); ?></label><small class="req"> *</small>

                                    <select  id="marksheet" name="marksheet" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        <?php

                                        foreach ($marksheetlist as $marksheet) {

                                            ?>

                                            <option value="<?php echo $marksheet->id ?>" <?php

                                            if (set_value('marksheet') == $marksheet->id) {

                                                echo "selected=selected";

                                            }

                                            ?>><?php echo $marksheet->template; ?></option>

                                                    <?php

                                                }

                                                ?>

                                    </select>

                                    <span class="text-danger"><?php echo form_error('marksheet'); ?></span>

                                </div>

                            </div>

                            <div class="col-sm-12">

                                <div class="form-group">

                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                                </div>

                            </div>
                            <input type="hidden" name="option_position" id="option_position">
                        </form>

                    </div>

                    <div class="aa"></div>

                    <?php

                    if (isset($studentList)) {

                        ?>

                        <form method="post" action="<?php echo base_url('admin/examresult/pdftestmarksheets') ?>" id="printMarksheet" >

                            <input type="hidden" name="marksheet_template" value="<?php echo $marksheet_template; ?>">

                            <div class="box-header ptbnull"></div>  

                            <div class="box-header ptbnull">

                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>

                                <button  class="btn btn-info btn-sm printSelected pull-right" type="submit" name="generate" title="generate multiple certificate"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait') ; ?>" > <?php echo $this->lang->line('bulk_download'); ?></button>

                            </div>

                            <div class="box-body">

                                

                                <div class="tab-pane active table-responsive no-padding" id="tab_1">

                                <?php if (!empty($studentList)) { ?>

                                    <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>

                                                <th><input type="checkbox" id="select_all" /></th>

                                                <th><?php echo $this->lang->line('admission_no'); ?></th>

                                                <th><?php echo $this->lang->line('student_name'); ?></th>

                                                <th><?php echo $this->lang->line('father_name'); ?></th>

                                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>

                                                <th><?php echo $this->lang->line('gender'); ?></th>

                                                <th><?php echo $this->lang->line('category'); ?></th>

                                                <th class=""><?php echo $this->lang->line('mobile_number'); ?></th>

                                                <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                                $count = 1;

                                                foreach ($studentList as $student_key => $student_value) {

                                                $student_name = $this->customlib->getFullName($student_value->firstname,$student_value->middlename,$student_value->lastname,$sch_setting->middlename,$sch_setting->lastname); 

                                                    ?>



                                                    <tr>

                                                        <td class="text-center"><input type="checkbox" class="checkbox center-block"  name="student_id[]" data-student_id="<?php echo $student_value->student_id; ?>" value="<?php echo $student_value->student_id; ?>">

                                                        </td>

                                                        <td><?php echo $student_value->admission_no; ?></td>        <td>

            <a href="<?php echo base_url(); ?>student/view/<?php echo $student_value->student_id; ?>">            

            <?php echo $student_name; ?>            

            

         </a>

        </td> 

                                                        <td><?php echo $student_value->father_name;?></td>

                                                        <td><?php 

															if (!empty($student_value->dob) && $student_value->dob != '0000-00-00') {

															echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student_value->dob)); }?></td>

                                                        <td><?php echo $this->lang->line(strtolower($student_value->gender)); ?></td>

                                                        <td><?php echo $student_value->category; ?></td>

                                                        <td><?php echo $student_value->mobileno; ?></td>

                                                        <td class="text-right white-space-nowrap">

                        
                        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $session_id = $_POST['session_id'];
                            $examoptionPosition = $_POST['option_position'];
                            $exam_group_id = $_POST['exam_group_id'];
                            $exam_id = $_POST['exam_id'];
                            $class_id = $_POST['class_id'];
                            $section_id = $_POST['section_id'];
                        }
                        ?>
<!--<a id="myLink" href="<?php echo base_url('admin/examresult/pdftestmarksheet/' . $student_value->student_id . '/' . $examoptionPosition . '/' . $exam_group_id . '/' . $session_id . '/' . $marksheet->id) ?>">ExamResult</a>
<a id="myLink1" href="<?php echo base_url('admin/examresult/ranksystem/' . $exam_group_id . '/' . $exam_id . '/' . $class_id . '/' . $section_id . '/' . $session_id . '/' . $examoptionPosition) ?>">StudentExam</a>-->

                        <button  type="button" class="btn btn-default btn-xs download_pdf" data-action="download" data-toggle="tooltip"  data-original-title="<?php echo $this->lang->line('download'); ?>"  data-student_id="<?php echo $student_value->student_id; ?>" data-admission_no="<?php echo $student_value->admission_no;?>" data-student_name="<?php echo $student_name; ?>" data-student_id="<?php echo $student_value->student_id?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="fa fa-download"></i></button>
                       
                       <button  type="button"  class="btn btn-default btn-xs email_pdf" data-action="email" data-exam_group_class_batch_exam_student_id="<?php echo $student_value->exam_group_class_batch_exam_student_id; ?>" id="load1" data-toggle="tooltip"  data-original-title="<?php echo $this->lang->line('sent_to_email'); ?>" data-student_id="<?php echo $student_value->student_id?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="fa fa-envelope"></i></button>

                                                        </td>

                                                    </tr>

                                                    <?php

                                                    $count++;

                                                }

                                            ?>

                                        </tbody>

                                    <?php }else{ ?>

                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>

                                    <?php } ?>

                                    </table>

                                </div>                                                            

                            </div> 
                            <input type="hidden" name="post_exam_id" id="post_exam_id" value="<?php echo $exam_id; ?>">

                            <input type="hidden" name="post_exam_group_id" id="post_exam_group_id" value="<?php echo $exam_group_id; ?>">

                            <input type="hidden" name="option_position" id="post_option_position" value="<?php echo $examoptionPosition; ?>">

                        </form>

                    </div>

                    <?php
                }

                ?>

            </div>

        </div>

    </section>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
  // jQuery code to capture the form submission event and set the selected option index
  $(document).ready(function() {
    $('#myForm').submit(function(event) {
      event.preventDefault(); // Prevent the form from submitting normally
      var selectedIndex = $('#exam_id').prop('selectedIndex');
      $('#option_position').val(selectedIndex);
      //alert(selectedIndex);
      // Submit the form
      $(this).unbind('submit').submit();
    });
  });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
   $(document).on('click', '.download_pdf', function() {
    let $button_ = $(this);
    var exam_group_id = $('#post_exam_group_id').val();
    var examoptionposition = $('#post_option_position').val();
    var session_id = $('#session_id').val();
    var marksheet_template = $('input[name=marksheet_template]').val();
    var class_name = document.getElementById("class_id").options[document.getElementById("class_id").selectedIndex].text;
    var section_name = document.getElementById("section_id").options[document.getElementById("section_id").selectedIndex].text;
    var student_id = [$button_.data('student_id')];
    var class_id = '<?php echo set_value('class_id') ?>';
    //var section_id = '<?php echo set_value('section_id') ?>';
    var base_url = '<?php echo base_url() ?>';
    var action=($button_.data('action'));

    $.ajax({
        type: 'POST',
        url: base_url + 'admin/examresult/pdftestmarksheets',
        data: {
            'exam_group_id': exam_group_id,
            'examoptionposition': examoptionposition,
            'session_id': session_id,
            'marksheet_template': marksheet_template,
            'class_name': class_name,
            'section_name': section_name,
            'studentIds': student_id,
            'class_id':class_id,
            'type':action
        },
        beforeSend: function() {
            $button_.button('loading');
        },
        xhr: function() {
            var xhr = new XMLHttpRequest();
            xhr.responseType = 'blob';
            return xhr;
        },
        success: function(data, jqXHR, response) {
            var date_time = new Date().getTime();
            var blob = new Blob([data], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = session_id + '_' + ".pdf";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            $button_.button('reset');
        },
        error: function(xhr) {
            // Handle error
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $button_.button('reset');
        },
        complete: function() {
            $button_.button('reset');
        }
    });
});

 $(document).on('click','.email_pdf',function(){

      let $button_ = $(this);

      let post_exam_id=$('#post_exam_id').val();

      let post_exam_group_id=$('#post_exam_group_id').val();

      var student_id=($button_.data('student_id'));

      var action=($button_.data('action'));

      var exam_group_class_batch_exam_student_id=($button_.data('exam_group_class_batch_exam_student_id'));

      let marksheet_template=$("input[name=marksheet_template]").val();

    $.ajax({

    type: 'POST',

    url: baseurl+'admin/examresult/pdftmarksheet',

    data: {

          'marksheet_template':marksheet_template,

        'type':action,

           'student_id':student_id,

           'exam_group_class_batch_exam_student_id':exam_group_class_batch_exam_student_id,

           'post_exam_id':post_exam_id,

           "post_exam_group_id":post_exam_group_id

          },

    dataType: 'JSON',

    beforeSend: function() {       

       $button_.button('loading');      

    },

   success: function (data, jqXHR, response) {

             if (data.status == 1) {

                  successMsg(data.message);

                } else {

                  errorMsg(data.message);

                }

            },

    error: function(xhr) { // if error occured      

        $button_.button('reset');

    },

    complete: function() {     

            $button_.button('reset');      

    }

});

    });



 $(document).ready(function () {

        $('.select2').select2();

    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

    var class_id = '<?php echo set_value('class_id') ?>';

    var section_id = '<?php echo set_value('section_id') ?>';

    var session_id = '<?php echo set_value('session_id') ?>';

    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';

    var exam_id = '<?php echo set_value('exam_id') ?>';

    getSectionByClass(class_id, section_id);

    getExamByExamgroup(exam_group_id, exam_id);

    $(document).on('change', '#exam_group_id', function (e) {

        $('#exam_id').html("");

        var exam_group_id = $(this).val();

        getExamByExamgroup(exam_group_id, 0);

    });



    $(document).on('change', '#class_id', function (e) {

        $('#section_id').html("");

        var class_id = $(this).val();

        getSectionByClass(class_id, 0);

    });



    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {

            $('#section_id').html("");

            var base_url = '<?php echo base_url() ?>';

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';



            $.ajax({

                type: "GET",

                url: base_url + "sections/getByClass",

                data: {'class_id': class_id},

                dataType: "json",

                beforeSend: function () {

                    $('#section_id').addClass('dropdownloading');

                },

                success: function (data) {

                    $.each(data, function (i, obj)

                    {

                        var sel = "";

                        if (section_id == obj.section_id) {

                            sel = "selected";

                        }

                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";

                    });

                    $('#section_id').append(div_data);

                },

                complete: function () {

                    $('#section_id').removeClass('dropdownloading');

                }

            });

        }

    }



    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {

            $('#exam_id').html("");

            var base_url = '<?php echo base_url() ?>';

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';



            $.ajax({

                type: "POST",

                url: base_url + "admin/examgroup/getExamByExamgroup",

                data: {'exam_group_id': exam_group_id},

                dataType: "json",

                beforeSend: function () {

                    $('#exam_id').addClass('dropdownloading');

                },

                success: function (data) {

                    $.each(data, function (i, obj)

                    {

                        var sel = "";

                        if (exam_id === obj.id) {

                            sel = "selected";

                        }

                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";

                    });



                    $('#exam_id').append(div_data);

                    $('#exam_id').trigger('change');

                },

                complete: function () {

                    $('#exam_id').removeClass('dropdownloading');

                }

            });

        }

    }

    $(document).on('submit', 'form#printMarksheet', function(e) {
        e.preventDefault();

        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var studentIds = $('form#printMarksheet input[name="student_id[]"]:checked').map(function() {
            return this.value;
        }).get();
        //alert(studentIds.join(', '));
        var exam_group_id = $('#post_exam_group_id').val();
        var examoptionposition = $('#post_option_position').val();
        var session_id = $('#session_id').val();
        var list_selected = $('form#printMarksheet input[name="student_id[]"]:checked').length;
        var marksheet_template = $('input[name=marksheet_template]').val();
        var class_name = document.getElementById("class_id").options[document.getElementById("class_id").selectedIndex].text;
        var section_name = document.getElementById("section_id").options[document.getElementById("section_id").selectedIndex].text;
        var class_id = '<?php echo set_value('class_id') ?>';
        //var section_id = '<?php echo set_value('section_id') ?>';
        var base_url = '<?php echo base_url() ?>';

        if (list_selected > 0) {
            $.ajax({
                type: "POST",
                url: base_url + 'admin/examresult/pdftestmarksheets',
                //dataType: 'json', // Specify the expected data type as JSON
                data: {
                    'exam_group_id': exam_group_id,
                    'examoptionposition': examoptionposition,
                    'session_id': session_id,
                    'studentIds': studentIds,
                    'marksheet_template': marksheet_template,
                    'class_name' : class_name,
                    'section_name' : section_name,
                    'class_id' : class_id
                },
                beforeSend: function() {
                    subsubmit_button.button('loading');
                },
                xhr: function () {// Seems like the only way to get access to the xhr object
                    var xhr = new XMLHttpRequest();
                    xhr.responseType = 'blob'
                    return xhr;
                },
                success: function (data, jqXHR, response) {
                   var date_time = new Date().getTime();
                   var blob = new Blob([data], {type: 'application/pdf'});
                   var link = document.createElement('a');
                   link.href = window.URL.createObjectURL(blob);
                   link.download = session_id +'_' + ".pdf";
                   document.body.appendChild(link);
                   link.click();
                   document.body.removeChild(link);
                   subsubmit_button.button('reset');
                },
                error: function(xhr) {
                    // Handle error
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    subsubmit_button.button('reset');
                },
                complete: function() {
                    subsubmit_button.button('reset');
                }
            });
        } else {
            confirm("Please select a student.");
        }
    });

    $(document).on('click', '#select_all', function () {

        $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            
    });

</script>


<script type="text/javascript">

    var base_url = '<?php echo base_url() ?>';

    function Popup(data)

    {

        var frame1 = $('<iframe />');

        frame1[0].name = "frame1";

        $("body").append(frame1);

        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;

        frameDoc.document.open();

//Create a new HTML document.

        frameDoc.document.write('<html>');

        frameDoc.document.write('<head>');

        frameDoc.document.write('<title></title>');

        frameDoc.document.write('</head>');

        frameDoc.document.write('<body>');

        frameDoc.document.write(data);

        frameDoc.document.write('</body>');

        frameDoc.document.write('</html>');

        frameDoc.document.close();

        setTimeout(function () {

            window.frames["frame1"].focus();

            window.frames["frame1"].print();

            frame1.remove();

        }, 500);

        return true;

    }

</script>