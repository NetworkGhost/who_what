//Ready functions that allow persistent experience with ajax calls
$(document).ready(function() {
//Toggles show tag form for user search results. By default this is hidden
$('body').on('click', 'div.showTagForm', function() {
	var userInfo = this.id;
	$("#"+userInfo+"_tag_container").toggle();
});
//Handles tag updates from tag form
$('body').on('click', 'button.submitTag', function() {
    var userInfo = this.id;
    var tagValue = $("#"+userInfo+"_tag_Field").val();
    var tagType = $("#"+userInfo+"_tag_type").val();
    updateTags(userInfo,tagValue,tagType,updateTagCallbackfunction);
});
//Executes tag search when a tag is clicked 
$('body').on('click', 'a.button_tag', function() {
	var searchString = $(this).text();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'tags');
	}
});
//Allows user to click enter for search without having to move mouse to search button
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
//Allows user to click enter for search without having to move mouse to search button
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
//Search function for button tags
$('button[id^="button_tag"]').click(function(event) {
	var searchString = $(this).text();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'tags');
	}
});
//Button search function for name
$("#btn_search_name").click(function() {
	var searchString = $("#searchField_name").val();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'search');
	}
});
//Tag search for search form
$("#search_form_tags").submit(function() {
	var searchString = $("#searchField_tags").val();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'tags');
	}
});
//Search function for tags based search
$("#btn_search_tags").click(function() {
	var searchString = $("#searchField_tags").val();
	if (searchValidation(searchString)) // Calling validation function.
	{
        	search(searchString,searchCallbackfunction,'tags');
	}
});
//Validate search string length before executing
function searchValidation(searchString) {
	var n = searchString.length;
	if (n >= 3) {
		return true;
	}	
	return false;
}
//Call back function for updating search results
function searchCallbackfunction(results){
         $("#searchResults").html(results);
}
//Call back function for tag updates
function updateTagCallbackfunction(userID,results) {
	$("#"+userID+"_tags").html(results);
}
//Update tags for a user
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
//Search for users 
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

}); //End of functions for persistence experience
