//Using ready function to maintain js functions after ajax calls
$(document).ready(function() {
//Tooltip for photo URL
 $('[data-toggle="tooltip"]').tooltip(); 
//Function that handles updates to site settings
//All site settings are set and updated at the same time
$("#btn_site").click(function() {
	resetFieldColor();
	var siteAdminUsername = $("#siteAdminUsername").val();
	var siteTitle = $("#siteTitle").val();
	var siteUserPhoto = $("#site_photo_url").val();
	var siteAdminPassword = $("#siteAdminPassword").val();
	var confSiteAdminPassword = $("#conf_siteAdminPassword").val();
	var siteLogoURL = $("#site_logo_url").val();
	if (validation()) // Calling validation function.
	{
		//Update settings
		updateRecords(siteTitle,siteAdminUsername,siteAdminPassword,siteUserPhoto,siteLogoURL,siteCallbackfunction);
	}
});
//Function that handles ldap settings
$("#btn_ldap").click(function() {
	var ldapDN = $("#ldapDN").val();
	var ldapServer = $("#ldapServer").val();
	var ldapFilter = $("#ldapFilter").val();
	var ldapUsername = $("#ldapUsername").val();
	var ldapPassword = $("#ldapPassword").val();
	if (ldapValidation()) // Calling validation function.
	{
        	//$("#ldap_form").submit(); // Form submission.
        	updateLDAPRecords(ldapServer,ldapDN,ldapFilter,ldapUsername,ldapPassword,ldapCallbackfunction);
	}
});
//ldap validation used to check that form fields have values
function ldapValidation() {
	
        var ldapServer = $("#ldapServer").val();
        var ldapDN = $("#ldapDN").val();
        var ldapFilter = $("#ldapFilter").val();
        var ldapUsername = $("#ldapUsername").val();
        var ldapPassword = $("#ldapPassword").val();

        if (ldapServer === '' || ldapDN === '' || ldapFilter === '' || ldapUsername === '' || ldapPassword === '') {
		$("#ldapDN").css({ "border": '#FF0000 1px solid'});
		$("#ldapServer").css({ "border": '#FF0000 1px solid'});
		$("#ldapFilter").css({ "border": '#FF0000 1px solid'});
                $("#ldapUsername").css({ "border": '#FF0000 1px solid'});
                $("#ldapPassword").css({ "border": '#FF0000 1px solid'});
                return false;
        } else {
                return true;
        }
}
//Reset field validation colors
function resetFieldColor() {
	$("#siteAdminUsername").css({ "border": 'black 1px solid'});
	$("#siteAdminPassword").css({ "border": 'black 1px solid'});
	$("#confSiteAdminPassword").css({ "border": 'black 1px solid'});
	$("#site_logo_url").css({ "border": 'black 1px solid'});
	$("#site_photo_url").css({ "border": 'black 1px solid'});
	$("#siteTitle").css({ "border": 'black 1px solid'});
}

//Validation function that checks site settings
function validation() {
	var siteTitle = $("#siteTitle").val();
	var siteAdminUsername = $("#siteAdminUsername").val();
	var siteAdminPassword = $("#siteAdminPassword").val();
	var confSiteAdminPassword = $("#conf_siteAdminPassword").val();	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var siteLogoURL = $("#site_logo_url").val(); 
	var sitePhotoURL = $("#site_photo_url").val();
	if (siteAdminUsername === '') {
		$("#siteAdminUsername").css({ "border": '#FF0000 1px solid'});
		return false;
	} else if (siteTitle === '') {
		$("#siteTitle").css({ "border": '#FF0000 1px solid'});
		return false;
	} else if (siteLogoURL === '') {
		$("#site_logo_url").css({ "border": '#FF0000 1px solid'});
		return false;
        } else if (sitePhotoURL === '') {
		$("#site_photo_url").css({ "border": '#FF0000 1px solid'});
		return false;
        }  else if (confSiteAdminPassword != siteAdminPassword) {
		$("#siteAdminPassword").css({ "border": '#FF0000 1px solid'});
		$("#confSiteAdminPassword").css({ "border": '#FF0000 1px solid'});
		return false;
	} else {
		return true;
	}
}
//Call back function used for updating return values after form submission
function siteCallbackfunction(siteTitle,siteAdminUsername,siteAdminPassword,sitePhotoURL){
        $("#siteTitle").val(siteTitle);
        $("#siteAdminUsername").val(siteAdminUsername);
	$("#siteAdminPassword").val(siteAdminPassword);	
	$("#site_photo_url").val(sitePhotoURL);
}
//Call back function used for updating ldap return values in form after submission
function ldapCallbackfunction(ldapServer,ldapDN,ldapFilter,ldapUsername,ldapPassword) {
	$("#ldapServer").val(ldapServer);
	$("#ldapDN").val(ldapDN);
        $("#ldapFilter").val(ldapFilter);
        $("#ldapUsername").val(ldapUsername);
        $("#ldapPassword").val(ldapPassword);
	
}
//Update function for site settings form submission
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
//Update ldap settings
function updateLDAPRecords(ldapServer,ldapDN,ldapFilter,ldapUsername,ldapPassword,callback)
{
      	jQuery.ajax({
       		type: "POST",
       		data: 'ldapServer='+ldapServer+'&ldapDN='+ldapDN+'&ldapFilter='+ldapFilter+'&ldapUsername='+ldapUsername+'&ldapPassword='+ldapPassword,     
       		url: "adminUpdate.php",
       		cache: false,
       		success: function(data){
         		callback(ldapServer,ldapDN,ldapFilter,ldapUsername,ldapPassword);
       		},
       		error: function() {
        		alert('Error updating ldap settings');
       		}
     	});
}

});//End on ready functions
