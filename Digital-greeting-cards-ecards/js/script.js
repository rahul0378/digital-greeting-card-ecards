function setedit(id,title,slug,parent,filtered){
    jQuery("#tag-name").val(title);
    jQuery("#tag-slug").val(slug);
    jQuery("#parent").val(parent);
    jQuery("#edit").val(id);
    jQuery("input[name=filtered][value="+filtered+"]").attr("checked","true")
    filtered
    jQuery("#submit").val("Update")
}
function delele_card(ID){
    if(confirm("Please confirm Category Delete?"))
    {
        jQuery("#del_id").val(ID);
        jQuery("#deletecard").submit();
    }
}
var loadFile = function(event) {
    var output = document.getElementById('priview-img');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };