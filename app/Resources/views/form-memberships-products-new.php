<div id="subway-new-product-form">

	<?php wp_enqueue_script( 'subway-membership-add-js' ); ?>

    <form autocomplete="off" method="POST"
          action="http://multisite.local/nibble/wp-json/subway/v1/membership/new-product">

        <div>
            <input type="hidden" name="page" value="subway-membership">
            <input type="hidden" name="new" value="yes">
        </div>

        <div>
            <h3><label>Product Title</label></h3>
            <p>Enter the product title</p>
            <input id="input-title" name="title" type="text" class="widefat" placeholder="Add Name">
        </div>

        <div>
            <h3><label>Product Description</label></h3>
            <p>Enter the product description</p>
            <textarea id="input-description" name="description" class="widefat" rows="5"
                      placeholder="Product description"></textarea>
        </div>

        <div>
            <h3><label>Payment Type</label></h3>
            <p>Select a payment type</p>
            <select name="type" id="input-type">
                <option value="free">Free</option>
                <option value="fixed">Fixed</option>
                <option value="recurring">Recurring</option>
            </select>
        </div>

        <div>
            <h3><label>Price</label></h3>
            <p>Enter the price of this product</p>
            <input id="input-amount" name="amount" type="number" style="width: 6em;" size="3" placeholder="0.00"/>
        </div>

        <hr/>

        <div>
            <input id="publish-product" type="submit" class="button button-primary button-large"
                   value="Publish Product"/>
        </div>

    </form>
</div>
