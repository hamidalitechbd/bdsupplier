<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/buttons.flash.min.js"></script>
<script type="text/javascript" src="js/pdfmake.min.js"></script>
<script type="text/javascript" src="js/vfs_fonts.js"></script>
<script type="text/javascript" src="js/buttons.html5.min.js"></script>
<script type="text/javascript" src="js/buttons.print.min.js "></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts 
<script src="../bower_components/raphael/raphael.min.js"></script>-->
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- ChartJS -->
<script src="bower_components/chart.js/Chart.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap 
<script src="../bower_components/moment/min/moment.min.js"></script>-->
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>-->
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/bootstrapvalidator.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes 
<script src="../dist/js/demo.js"></script>-->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


<script src="includes/js/manageProfileUser.js"></script> 
<script>
    function readNotification(){
        $.ajax({
    		url:"phpScripts/readNotification.php",
    		method:"POST",
    		contentType: false,
    		processData: false,
    		dataType: 'json',
    		success:function(data)
    		{
                if(parseFloat($("#UnReadNotify").html()) < parseFloat(data.pending)){
                    var x = document.getElementById("myAudio"); 
                    x.play(); 
                }
                $("#UnReadNotify").html(data.pending);
                $("#notification").html(data.notification);	
    		},
    		error: function (xhr) {
    			//alert("Souen Error: "+xhr.responseText);
    			console.log("Souen Error: "+xhr.responseText);
    		}
    	});
    }
    readNotification();
	setInterval(readNotification, 30000);
    $(document).ready(function () {
        $('#exampleas').DataTable({
            //responsive: true
            dom: 'Bfrtip',
            buttons: [
                'pageLength', 'copy', 'csv', 'pdf', 'print'
            ]
        })
        $('#example2').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    })
</script>
<script>

    $(document).ready(function () {
        $('#example111').DataTable({
            //responsive: true
            dom: 'Bfrtip',
            buttons: [
                'pageLength', 'copy', 'csv', 'pdf', 'print'
            ]
        })
    })
</script>
<script>
    $(function () {
        $('#example311').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'pageLength', 'copy', 'csv', 'pdf', 'print'
            ]
        })
        $('#example4').DataTable({
            'paging': true,
            'lengthChange': false,
            'searching': false,
            'ordering': true,
            'info': true,
            'autoWidth': false
        })
    })
</script>
<script>
    $(function () {
        /** add active class and stay opened when selected */
        var url = window.location;

        // for sidebar menu entirely but not cover treeview
        $('ul.sidebar-menu a').filter(function () {
            return this.href == url;
        }).parent().addClass('active');

        // for treeview
        $('ul.treeview-menu a').filter(function () {
            return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

    });
</script>
<script>
    $(function () {
        //Date picker
        $('#datepicker_add').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        })
        $('#datepicker_edit').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        })

        //Timepicker
        $('.timepicker').timepicker({
            showInputs: false
        })

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'})
        //Date range as a button
        $('#daterange-btn').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
        )

    });
    function salesReport(salesId, saleType){
        var action = "findSalesReportPrint";
        var countAction = "countPrint";
        if(saleType=='TS'){
            action = "findTSReportPrint";
            countAction= "countTSPrint";
        }else if(saleType == 'partySaleReturn' || saleType == 'walkinSaleReturn' || saleType == 'FSReturn'){
            action = "findSaleReturnReportPrint";
            countAction= "countReturnPrint";
        }
       
        $.ajax({
			type: 'POST',
			url: 'phpScripts/activityAction.php',
			data: "action="+action+"&id="+salesId,
			dataType: 'json',
			success: function(response){
			    var origin = window.location.origin;
				if(response.print_count > 0){
				    var conMsg = confirm(response.print_count+" Times Print already taken. Last Printed By "+response.fname +" at "+response.printed_date);
				    if(conMsg){
				        if(saleType=='PartySale'){
				            window.open(origin+"/wholesalesViewDetails.php?id="+salesId);
				        }else if(saleType=='WalkinSale'){
				            window.open(origin+"/salesViewDetails.php?id="+salesId);
				        }else if(saleType=='TS'){
				            window.open(origin+"/tsSalesViewDetails.php?id="+salesId);
				        }else if(saleType=='FS'){
    			            window.open(origin+"/fsSalesViewDetails.php?id="+salesId);
    			        }else if(saleType=='partySaleReturn'){
    			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=PartySale");
    			        }else if(saleType=='walkinSaleReturn'){
    			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=WalkinSale");
    			        }else if(saleType=='FSReturn'){
    			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=FS");
    			        }else{
				            alert("Invalid Report");
				        }
				        $.ajax({
                			type: 'POST',
                			url: 'phpScripts/activityAction.php',
                			data: "action="+countAction+"&id="+salesId,
                			dataType: 'json',
                			success: function(response){
                			}
				        });
				    }
				}else{
				    if(saleType=='PartySale'){
			            window.open(origin+"/wholesalesViewDetails.php?id="+salesId);
			        }else if(saleType=='WalkinSale'){
			            window.open(origin+"/salesViewDetails.php?id="+salesId);
			        }else if(saleType=='TS'){
			            window.open(origin+"/tsSalesViewDetails.php?id="+salesId);
			        }else if(saleType=='FS'){
			            window.open(origin+"/fsSalesViewDetails.php?id="+salesId);
			        }else if(saleType=='partySaleReturn'){
			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=PartySale");
			        }else if(saleType=='walkinSaleReturn'){
			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=WalkinSale");
			        }else if(saleType=='FSReturn'){
			            window.open(origin+"/salesReturnViewDetails.php?id="+salesId+"&rType=FS");
			        }else{
			            alert("Invalid Report");
			        }
			        
			        $.ajax({
            			type: 'POST',
            			url: 'phpScripts/activityAction.php',
            			data: "action="+countAction+"&id="+salesId,
            			dataType: 'json',
            			success: function(response){
            			}
			        });
				}
			}, 
			beforeSend: function(){
                // Show image container
                $("#loading").show();
            },
            complete:function(data){
                // Hide image container
                $("#loading").hide();
            }, error: function (xhr) {
				alert(JSON.stringify(xhr));
			}
	    });
    }
    //--Initialize CKEditor
    CKEDITOR.replace('addPageFooter', {

        width: "100%",
        height: "200px"

    });
    CKEDITOR.replace('editPageFooter', {

        width: "100%",
        height: "200px"

    });
    CKEDITOR.replace('editApplicationSpecification', {

        width: "100%",
        height: "200px"

    });
</script>
