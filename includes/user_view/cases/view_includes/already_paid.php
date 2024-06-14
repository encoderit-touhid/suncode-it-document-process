
<div class="row_d">
            <div class="titel_col">
                <label for="">Payment Method:</label>
            </div>
            <div class="right_col right_total_price">
                <div class="payment_method_container">
                    <div class="item d-flex-center">
                        <input type="radio" checked disabled/>
                        <span><?=$result->payment_method?></span>
                    </div>
                </div>
               
                <div class="total__price">
                    <span>Total Price</span><span id="price">$ <?=$result->total_price?></span>
                </div>
                
            </div>
        </div>
