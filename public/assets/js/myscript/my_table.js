
var load_table_url;
                
function load_listevent(search,page){
    
//        $('#loadmask').addClass('active')
        //$('#load_result').mask('Loading... Please wait.');
        $('#load_result').html('<p class="loading-text"><span class="spinner"></span><br>Loading, please wait...</p>');
        var post_data={};
        post_data['limit']=$('#limit').val();
        post_data['search']=$('#search_event_list').val();
        post_data['order_by']=$('#order_by').val();
        post_data['sort_by']=$('#sort_by').val();
        post_data['record_status']=$('#record_status').val();
        post_data['page']=page;
        $.ajax({
                url: load_table_url,
                type: 'POST',
                data: post_data,
                success:function(result){
                        $("#load_result").html(result);
                        //$('#loadmask').removeClass('active')
                        //$('#load_result').unmask();
                }
        });
}

/*
$('#search_event_list').on('change',function(){
        load_listevent('','');
});
*/

$('.sort').on('click',function(){ 
        $('#order_by').val($(this).attr('id'));
        if($('#sort_by').val()=='desc'){
            $('#sort_by').val('asc')
        } else {
            $('#sort_by').val('desc')
        }
        load_listevent('','1');
});

$('#limit').on('change',function(){
//        $("#mytable_limit_loading").show();
        var search = '';
        var page = '1';
//        var page = $('#cur_page').val();
//        if(page=='') page = '';
        if($('#search_event_list').val()!='') search=$('#search_event_list').val();
        load_listevent('',page);
});

$('.page_button').on('click',function(){
//        $("#mytable_pagi_loading").show();
        var page=1;
        var search='';        
        if($(this).html()=='Last')page=$('#max_page').val();
        else if($(this).html()=='First')page=1;
        else if($(this).html()=='Next') page=parseInt($('#cur_page').val())+1;
        else if($(this).html()=='Previous')page=parseInt($('#cur_page').val())-1;
        else page=$(this).html();
        
        var max = parseInt($('#max_page').val());
        
        if(page<=max && page!=0 && page!=$('#cur_page').val()){
            if($('#search_event_list').val()!='') search=$('#search_event_list').val();
            load_listevent('',page);
        }
        
});
