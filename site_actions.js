$(document).ready(function() {
$('body').on('click', 'div.showTagForm', function() {
var userInfo = this.id;
$("#"+userInfo+"_tag_container").toggle();

});

$('body').on('click', 'button.submitTag', function() {
    var userInfo = this.id;
    var tagValue = $("#"+userInfo+"_tag_Field").val();
    var tagType = $("#"+userInfo+"_tag_type").val();
    updateTags(userInfo,tagValue,tagType,updateTagCallbackfunction);
    // do something
});
$('body').on('click', 'a.button_tag', function() {
var searchString = $(this).text();
if (searchValidation(searchString)) // Calling validation function.
{
        //$("#search_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        search(searchString,searchCallbackfunction,'tags');
}

});

$("#searchField_tags").keydown(function(event){
    if(event.keyCode == 13){
        var searchString = $("#searchField_tags").val();
        if (searchValidation(searchString)) // Calling validation function.
        {
                search(searchString,searchCallbackfunction,'tags');
        }
        event.preventDefault();
    }
});

$("#searchField_name").keydown(function(event){
    if(event.keyCode == 13){
	var searchString = $("#searchField_name").val();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'search');
	}
	event.preventDefault(); 
    }
});

$('button[id^="button_tag"]').click(function(event) {
var searchString = $(this).text();
if (searchValidation(searchString)) // Calling validation function.
{
        //$("#search_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        search(searchString,searchCallbackfunction,'tags');
}
});




$("#btn_search_name").click(function() {
var searchString = $("#searchField_name").val();
if (searchValidation(searchString)) // Calling validation function.
{
        //$("#search_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        search(searchString,searchCallbackfunction,'search');
}
});
$("#search_form_tags").submit(function() {
var searchString = $("#searchField_tags").val();
if (searchValidation(searchString)) // Calling validation function.
{
        //$("#search_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        search(searchString,searchCallbackfunction,'tags');
}
});

$("#btn_search_tags").click(function() {
var searchString = $("#searchField_tags").val();
if (searchValidation(searchString)) // Calling validation function.
{
        //$("#search_form").submit(); // Form submission.
        //alert(" Site Title : " + siteTitle + " \n Site Admin : " + siteAdminUsername + " \n Form id : " + $("#site_form").attr('id') + "\n\n Form Submitted Successfully......");
        search(searchString,searchCallbackfunction,'tags');
}
});
function searchValidation(searchString) {
	var n = searchString.length;
	if (n >= 3) {
		return true;
	}	
	return false;
}

function searchCallbackfunction(results){
         $("#searchResults").html(results);

}
function updateTagCallbackfunction(userID,results) {
	$("#"+userID+"_tags").html(results);
}
function updateTags(userInfo,tag,tagType,callback)
{
      jQuery.ajax({
       type: "POST",
       data: 'userID='+userInfo+'&tag='+tag+'&tagType='+tagType,     // <-- put on top
       url: "tagUpdate.php",
       cache: false,
       success: function(data){
         callback(userInfo,data);
       },
       error: function() {
        alert('ajax error');
       }
     });
}

function search(searchString,callback,type)
{
	$("#searchResults").html('');
	$("#loader").toggle();
      jQuery.ajax({
       type: "POST",
       data: 'searchString='+searchString+'&type='+type,     // <-- put on top
       url: "search.php",
       cache: false,
       success: function(data){
	 $("#loader").toggle();
         callback(data);
       },
       error: function() {
        alert('ajax error');
       }
     });
}

});
