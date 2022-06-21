<div class="content py-5 mt-3">
    <div class="container">
        <div class="card card-outline card-dark shadow rounded-0">
            <div class="card-header">
                <h4 class="card-title">Place Order</h4>
            </div>
            <div class="card-body">
                  <?php 
                   $total = 0;
                   $cart = $conn->query("SELECT c.*,p.name, p.price, p.image_path,b.name as brand, cc.category FROM `cart_list` c inner join product_list p on c.product_id = p.id inner join brand_list b on p.brand_id = b.id inner join categories cc on p.category_id = cc.id where c.client_id = '{$_settings->userdata('id')}' order by p.name asc");
                    while($row = $cart->fetch_assoc()):
                    $total += ($row['quantity'] * $row['price']);
                ?>
                 <?php endwhile; ?>
                <form action="" id="place_order">
                    <div class="form-group">
                        <label for="delivery_address" class="control-label">Delivery Address</label>
                        <textarea name="delivery_address" id="delivery_address" class="form-control form-control-sm rounded-0" rows="4"><?= $_settings->userdata('address') ?></textarea>
                    </div>
                    <div class="form-group text-right">
                        <label class="control-label">Payment Method: </label>
                            <a data-amount="1" data-fee="0" data-expiry="6" data-description="Payment for RJK Motor Shop" data-href="https://getpaid.gcash.com/paynow" data-public-key="pk_40e973b73a5ec1b02406adc2f517a16e" onclick="this.href = this.getAttribute('data-href')+'?public_key='+this.getAttribute('data-public-key')+'&amp;amount='+this.getAttribute('data-amount')+'&amp;fee='+this.getAttribute('data-fee')+'&amp;expiry='+this.getAttribute('data-expiry')+'&amp;description='+this.getAttribute('data-description');" href="https://getpaid.gcash.com/paynow?public_key=pk_40e973b73a5ec1b02406adc2f517a16e&amp;amount=100&amp;fee=0&amp;expiry=6&amp;description=Payment for RJK Motor Shop" target="_blank" class="btn btn-flat btn-primary">Pay using GCash</a>
                            <button class="btn btn-flat btn-primary">Cash on Delivery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#place_order').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=place_order",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.replace('./?p=my_orders');
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
    })
</script>