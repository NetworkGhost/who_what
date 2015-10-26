$(document).ready(function() {
$("#btn_site").click(function() {
var siteAdminUsername = $("#siteAdminUsername").val();
var siteTitle = $("#siteTitle").val();
if (validation()) // Calling validation function.
{
	//alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
	updateRecords(siteTitle,siteAdminUsername,siteCallbackfunction);
}
});
$("#btn_ldap").click(function() {
var ldapDN = $("#ldapDN").val();
var ldapServer = $("#ldapServer").val();
if (ldapValidation()) // Calling validation function.
{
        $("#ldap_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        updateLDAPRecords(ldapServer,ldapDN,ldapCallbackfunction);
}
});

function ldapValidation() {
        var ldapServer = $("#ldapServer").val();
        var ldapDN = $("#ldapDN").val();
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (ldapServer === '' || ldapDN === '') {
                alert("Please fill all fields...!!!!!!");
                return false;
        } else {
                return true;
        }
}





function validation() {
	var siteTitle = $("#siteTitle").val();
	var siteAdminUsername = $("#siteAdminUsername").val();
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if (siteAdminUsername === '' || siteTitle === '') {
		alert("Please fill all fields...!!!!!!");
		return false;
	} else {
		return true;
	}
}
//callbackfuncion
function siteCallbackfunction(siteTitle,siteAdminUsername){
         $("#siteTitle").val(siteTitle);
         $("#siteAdminUsername").val(siteAdminUsername);

}
function updateRecords(siteTitle,siteAdminUsername,callback)
{
      jQuery.ajax({
       type: "POST",
       data: 'siteAdminUsername='+siteAdminUsername+'&siteTitle='+siteTitle,     // <-- put on top
       url: "adminUpdate.php",
       cache: false,
       success: function(data){
	 callback(siteTitle,siteAdminUsername); 
       },
       error: function() {
        alert('ajax error');
       }
     });
}
function updateLDAPRecords(ldapServer,ldapDN,callback)
{
      jQuery.ajax({
       type: "POST",
       data: 'ldapServer='+ldapServer+'&ldapDN='+ldapDN,     // <-- put on top
       url: "adminUpdate.php",
       cache: false,
       success: function(data){
         callback(ldapServer,ldapDN);
       },
       error: function() {
        alert('ajax error');
       }
     });
}

});
