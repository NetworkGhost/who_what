$(document).ready(function() {
$("#btn_site").click(function() {
var siteAdminUsername = $("#siteAdminUsername").val();
var siteTitle = $("#siteTitle").val();
var siteUserPhoto = $("#site_photo_url").val();
var siteAdminPassword = $("#siteAdminPassword").val();
var confSiteAdminPassword = $("#conf_siteAdminPassword").val();
var siteLogoURL = $("#site_logo_url").val();
if (validation()) // Calling validation function.
{
	//alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
	updateRecords(siteTitle,siteAdminUsername,siteAdminPassword,siteUserPhoto,siteLogoURL,siteCallbackfunction);
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
	var siteAdminPassword = $("#siteAdminPassword").val();
	var confSiteAdminPassword = $("#conf_siteAdminPassword").val();	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if (siteAdminUsername === '' || siteTitle === '') {
		alert("Please fill all fields...!!!!!!");
		return false;
	} else if (confSiteAdminPassword != siteAdminPassword) {
		alert("Admin passwords do not match." + " " + confSiteAdminPassword + ":" + siteAdminPassword);
		return false;
	} else {
		return true;
	}
}
//callbackfuncion
function siteCallbackfunction(siteTitle,siteAdminUsername,siteAdminPassword,sitePhotoURL){
        $("#siteTitle").val(siteTitle);
        $("#siteAdminUsername").val(siteAdminUsername);
	$("#siteAdminPassword").val(siteAdminPassword);	
	$("#site_photo_url").val(sitePhotoURL);
}
function updateRecords(siteTitle,siteAdminUsername,siteAdminPassword,sitePhotoURL,siteLogoURL,callback)
{
      jQuery.ajax({
       type: "POST",
       data: 'siteAdminUsername='+siteAdminUsername+'&siteTitle='+siteTitle+'&siteAdminPassword='+siteAdminPassword+'&sitePhotoURL='+sitePhotoURL+'&siteLogoURL='+siteLogoURL,     // <-- put on top
       url: "adminUpdate.php",
       cache: false,
       success: function(data){
	 callback(siteTitle,siteAdminUsername,siteAdminPassword,sitePhotoURL); 
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
