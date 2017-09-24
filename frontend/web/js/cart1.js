/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/


$(function(){
	
	//减少
	$(".reduce_num").click(function(){
		var amount = $(this).parent().find(".amount");
		if (parseInt($(amount).val()) <= 1){
			alert("商品数量最少为1");
		} else{
			$(amount).val(parseInt($(amount).val()) - 1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        changeCart($(this).closest('tr').attr('data-id'),amount.val());
	});

	$('.aclo5').text($('.amount').val()*$('.acol3').text());

	var goods_id=$('.amount').attr('id');

	$('.delete').click(
		function () {
			$.post("/index/ajax.html",{'goods1_id':goods_id},function (data) {

				if(data=='success'){
                    var tr=$(this).closest('tr');
                    tr.hide('slow');
                    location.reload();
				}
            })
        }
	)


	//增加
	$(".add_num").click(function(){
		var amount = $(this).parent().find(".amount");
		$(amount).val(parseInt($(amount).val()) + 1);
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        changeCart($(this).closest('tr').attr('data-id'),amount.val());
	});




	//直接输入
	$(".amount").blur(function(){
		if (parseInt($(this).val()) < 1){
			alert("商品数量最少为1");
			$(this).val(1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        var amount = $(this).parent().find(".amount");
        changeCart($(this).closest('tr').attr('data-id'),amount.val());
	});
});
var changeCart = function(goods_id,amount){
    $.post("/index/ajax.html",{goods_id:goods_id,amount:amount},function(){});
}

