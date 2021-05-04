$(document).ready(function() {
$select = $("#usertype");
$("#usertype").on("change",function(){
	
	if($(this).val() == ""){
		    $("#providerdetails").hide();
            $("#patientdetails").hide();
    }

    if($(this).val() == "patient"){
        if($("#patientdetails").is(":hidden")){
            $("#patientdetails").show();
		    $("#providerdetails").hide();
			$("#providerdetails :input").removeAttr("required");
			
        }           
    }

    if($(this).val() == "provider"){
        if($("#providerdetails").is(":hidden")){
            $("#providerdetails").show();
            $("#patientdetails").hide();
			$("#patientdetails :input").removeAttr("required");

        }           
    }


    });
});