function process(type, data)
{
    switch (type)
    {
        case "home":
            $("#content").html($("#homeData").html());
            register("link");
            return;
        case "about":
            $("#content").html($("#aboutData").html());
            return;
        case "newDeviceControl":
            $("#content").html($("#newDeviceData").html());
            register("form", "newDeviceForm");
            register("link");
            return;
        case "ipMacChanging":
            $("#content").html($("#ipMacChanging").html());
            register("form", "ipMacChanging");
            register("link");
            ajaxProcess("type=ipMacChanging&data=ip",1);
            return;
        case "vlanData":
            $("#content").html($("#vlanData").html());
            register("form", "vlanData");
            register("link");
            return;
        case "osData":
            $("#content").html($("#osData").html());
            register("form", "osData");
            register("form", "osData2");
            register("link");
            return;
    }
};

function register(type, id)
{
    switch (type)
    {
        case "form":
            $("input[type=button]","#"+id).click(function(){
                ajaxProcess($("#"+id).serialize()+"");
            });
            $("input[type=text]","#"+id).keyup(function(event){
                if     (event.keyCode==37 || event.keyCode==38)
                {
                    var a = $(this).attr("sira");
                    a-=1;
                    if(a<1)
                        a=8;
                    $("input[name=ip"+a+"]",$(this).parent()).focus().select();                }
                else if(event.keyCode==39 || event.keyCode==40)
                {
                    var a = $(this).attr("sira");
                    a+=1;
                    if(a>8)
                        a=1;
                    $("input[name=ip"+a+"]",$(this).parent()).focus().select();
                }
                return false;
            });
            $("input[type=checkbox]","#"+id).change(function(){
                if  ($(this).attr("checked"))
                {
                    if($(this).val()=="ip")
                         $("input[name=mac]",$(this).parent()).removeAttr("checked");
                    else
                         $("input[name=ip]",$(this).parent()).removeAttr("checked");
                }
                else
                {
                    if($(this).val()=="ip")
                    {
                         $("input[name=mac]",$(this).parent()).attr({"checked":"checked"});
                    }
                    else
                    {
                         $("input[name=ip]",$(this).parent()).attr({"checked":"checked"});
                    }
                }
                ajaxProcess("type=ipMacChanging&data="+$("input:checked",$(this).parent()).val(),1);
            });
            break;
        case "link":
            $("#content a[alt]").live("click",function(event){
                event.preventDefault();

                if ($(this).html()=="::")
                    return;

                if ($(this).attr('title')=="Delete")

                    if(!window.confirm('Do you want to delete?'))
                        return;
                ajaxProcess($(this).attr('alt'));
            });
    }
}

function ajaxProcess(formData,listType)
{
    $.ajax({
        url: "getData.php",
        type: "GET",
        cache: false,
        data : formData,
        beforeSend: function(){
            $("#wait").stop(true,true).fadeIn();
        },
        success: function(receivedData){
            if(listType==1)
                $("#ipMacChanging select[name=list]").html(receivedData);
            else
                $("#content").html(receivedData);
            $("#wait").stop(true,true).fadeOut(700);
        }
    });
}

$().ready(function(){
    $(".home").click(function(){
        process("home");
    });
    $(".about").click(function(){
        process("about");
    });

    $(".home").trigger('click');
});