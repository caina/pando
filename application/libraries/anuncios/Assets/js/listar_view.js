var ajax_url;
$(document).ready(function(){

  ajax_url = $("#api_url").val();
  display_list(0);
  $(".pager").click(function(e){
    e.preventDefault();

    $('.pager').parent().removeClass('active');
    $('.pager[data-page="'+$(this).attr("data-page")+'"]').parent().addClass("active");

    display_list($(this).attr("data-page"));
  });

});

function display_list(page){
  $("#ad_ajax_view").empty().html("Carregando");
  $.post(ajax_url+"/manage_list_ajax", {page:page}, function(data, textStatus, xhr) {
    $("#ad_ajax_view").empty().html(data);
  });

}
