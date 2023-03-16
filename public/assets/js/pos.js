(function(jQuery) {
    $.opt = {}; // jQuery Object

    jQuery.fn.invoice = function(options) {
        var ops = jQuery.extend({}, jQuery.fn.invoice.defaults, options);
        $.opt = ops;

        var inv = new Invoice();
        inv.init();

        jQuery('body').on('click', function(e) {
            var cur = e.target.id || e.target.className;

            if (cur == $.opt.addRow.substring(1))
                inv.newRow();

            if (cur == $.opt.addProductRow.substring(1))
                inv.newRow();

            if (cur == $.opt.delete.substring(1))
                inv.deleteRow(e.target);

            inv.init();
        });

        // jQuery('#product_id').on('click', function (e) {
        //     var cur = e.target.id || e.target.className;

        //     if (cur == $.opt.product_id.substring(1))
        //         inv.newRow();

        //     inv.init();
        // });

        // jQuery('body').on('click',function(e){
        //     var cur = e.target.id || e.target.className;
        //     if (cur == $.opt.delete.substring(1))
        //     inv.deleteRow(e.target);
        //      inv.init();
        // });

        $( "body" ).mouseover(function() {


            inv.init();
        });
        jQuery("body").on('keyup', function(e) {

            inv.init();
        });


        return this;
    };
}(jQuery));



function Invoice() {
    self = this;
}

Invoice.prototype = {
    constructor: Invoice,

    init: function() {
        this.calcTotal();
        // this.calcTotalQty();
        this.calcSubtotal();
        this.calcGrandTotal();
        this.calcPayment();
        this.datePicker();
        this.calcdistotal();
        this.calcBack();
        this.calcdisSubtotal();


        // var row_index;

        // $("#purchaseTable").on('click', "tbody tr", function(e) {
        // row_index = $(this).parent().index();
        // row_index = $(this).index();
        // let col_index = $(this).index();
        // console.log(row_index);
        // console.log(col_index);
        // let uniqueId = "hello" + row_index;
        // console.log(uniqueId);
        // $("#expiry_date").datepicker({ minDate: 0 });
        // });



        // let uniqueId = "hello" + row_index;


        // $(".invoice_datepicker").datepicker({ minDate: 0 });

        // var today = new Date();
        // $(".invoice_datepicker").on('click', function() {
        //     $(this).attr('min', today);
        // })
    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    calcTotal: function() {
        jQuery($.opt.parentClass).each(function(i) {
            var row = jQuery(this);
            var rowCount = jQuery($.opt.parentClass).length;
            var finalCount = rowCount - 1
            jQuery($.opt.item).val(finalCount);

            var discount = row.find($.opt.discountt).val();
            var total = row.find($.opt.price).val() * row.find($.opt.qty).val();

         if(discount == 0 || discount == ''){
            total = self.roundNumber(total, 2);
            discount = 0;
            row.find($.opt.totaldis).val(discount);
            row.find($.opt.total).val(total);
         }else{
            total = self.roundNumber(total, 2);
           discount = ((discount/ 100) * total);
           row.find($.opt.totaldis).val(discount);

           row.find($.opt.total).val(total);
         }
            //    total = ((discountt/ 100) * total)







            // row.find($.opt.total).html(total);



        });

        return 1;
    },


    /***
     * Calculate total quantity of an order.
     *
     * @returns {number}
     */
    // calcTotalQty: function () {
    //      var totalQty = 0;
    //      jQuery($.opt.qty).each(function (i) {
    //          var qty = jQuery(this).val();
    //          if (!isNaN(qty)) totalQty += Number(qty);
    //      });

    //      totalQty = self.roundNumber(totalQty, 2);

    //      jQuery($.opt.totalQty).html(totalQty);

    //      return 1;
    //  },

    /***
     * Calculate subtotal of an order.
     *
     * @returns {number}
     */
    calcdistotal: function() {
        var distotal = 0;
        jQuery($.opt.totaldis).each(function(i) {
            var total = jQuery(this).val();
            // var total = jQuery(this).html();
            if (!isNaN(total)) distotal += Number(total);
        });

        distotal = self.roundNumber(distotal, 2);

        //  console.log('sub total '+subtotal);

        jQuery($.opt.discount).val(distotal);
        // jQuery($.opt.subtotal).html(subtotal);

        // $('#subtotal').val(subtotal);

        return 1;
    },

    calcSubtotal: function() {
        var subtotal = 0;
        jQuery($.opt.total).each(function(i) {
            var total = jQuery(this).val();
            // var total = jQuery(this).html();
            if (!isNaN(total)) subtotal += Number(total);
        });

        subtotal = self.roundNumber(subtotal, 2);

        //  console.log('sub total '+subtotal);

        jQuery($.opt.subtotal).val(subtotal);
        // jQuery($.opt.subtotal).html(subtotal);

        // $('#subtotal').val(subtotal);

        return 1;
    },

    calcdisSubtotal: function() {
        var afterdis = 0;
        if(Number(jQuery($.opt.flatdiscount).val()) > 0 && Number(jQuery($.opt.discount).val()) > 0  && Number(jQuery($.opt.points).val()) > 0){
            afterdis = Number(jQuery($.opt.subtotal).val())  -
            Number(jQuery($.opt.discount).val()) - Number(jQuery($.opt.points).val());
        }
        else if(Number(jQuery($.opt.flatdiscount).val()) == 0 && Number(jQuery($.opt.discount).val()) == 0 && Number(jQuery($.opt.points).val()) == 0){
            afterdis = Number(jQuery($.opt.subtotal).val());

        }else{
            if(Number(jQuery($.opt.flatdiscount).val()) > 0){
                afterdis = Number(jQuery($.opt.subtotal).val())  -
                Number(jQuery($.opt.flatdiscount).val());
                afterdis = self.roundNumber(afterdis, 2);

            }
            if( Number(jQuery($.opt.discount).val()) > 0){
                afterdis = Number(jQuery($.opt.subtotal).val())  -
                Number(jQuery($.opt.discount).val());
                afterdis = self.roundNumber(afterdis, 2);

            }
            if( Number(jQuery($.opt.points).val()) > 0){
                afterdis = Number(jQuery($.opt.subtotal).val())  -
                Number(jQuery($.opt.points).val());
                afterdis = self.roundNumber(afterdis, 2);

            }
        }

        jQuery($.opt.afterdis).val(afterdis);
        return 1;
    },

    /**
     * Calculate grand total of an order.
     *
     * @returns {number}
     */
    // calcGrandTotal: function () {
    //     var grandTotal = Number(jQuery($.opt.subtotal).html())
    //                    + Number(jQuery($.opt.shipping).val())
    //                    - Number(jQuery($.opt.discount).val());
    //     grandTotal = self.roundNumber(grandTotal, 2);

    //     jQuery($.opt.grandTotal).html(grandTotal);

    //     return 1;
    // },

    calcGrandTotal: function() {


        if(Number(jQuery($.opt.afterdis).val()) == 0 || Number(jQuery($.opt.afterdis).val()) == '' ){
            if( Number(jQuery($.opt.vat).val()) != 0 || Number(jQuery($.opt.vat).val()) != ''){
                var grandTotal = Number(jQuery($.opt.subtotal).val())  +
                Number(jQuery($.opt.vat).val());
            }else{
                grandTotal = Number(jQuery($.opt.subtotal).val());
            }

        }else{

            if( Number(jQuery($.opt.vat).val()) != 0 || Number(jQuery($.opt.vat).val()) != ''){
                var grandTotal = Number(jQuery($.opt.afterdis).val())  +
                Number(jQuery($.opt.vat).val());
            }else{
                 grandTotal = Number(jQuery($.opt.afterdis).val());
            }

        }

        grandTotal = self.roundNumber(grandTotal, 2);
        jQuery($.opt.grandTotal).val(grandTotal);
        return 1;
    },


    calcPayment: function() {
          if(Number(jQuery($.opt.pay).val()) > Number(jQuery($.opt.grandTotal).val())){
            var due =  0;
          }else{
            var due =  Number(jQuery($.opt.grandTotal).val()) - Number(jQuery($.opt.pay).val());
          }




        due = self.roundNumber(due, 2);
        jQuery($.opt.due).val(due);
        return 1;
    },

    calcBack: function() {
        if(Number(jQuery($.opt.pay).val()) < Number(jQuery($.opt.grandTotal).val())){
          var back =  0;
        }else{
          var back = Number(jQuery($.opt.pay).val()) - Number(jQuery($.opt.grandTotal).val());
        }




      back = self.roundNumber(back, 2);
      jQuery($.opt.back).val(back);
      return 1;
  },

    datePicker: function() {
        // $(".invoice_datepicker").datepicker({ minDate: 0 });

        // return 0;
    },

    /**
     * Add a row.
     *
     * @returns {number}
     */

    newRow: function() {
        var i = 0;
        i++;

        jQuery(".item-row:first").after('<tr class="item-row"><td><input class="form-control pr_id" type="hidden" name="product_id[]"  readonly><input class="form-control product_name" type="text" name="product_name[]" id="product_name" readonly required></td><td><input class="form-control invoice_datepicker" type="date" name="expiry_date[]" placeholder="Expiry Date" id="expiry_date" required readonly></td><td><input class="form-control stock-qty"  type="number" name="stockquantity[]" placeholder="Quantity" required id="stock" readonly></td><td><input class="form-control qty"  type="number" name="quantity[]" value="1" required id="qty" onkeyup="prevent_stock_amount()"onchange="prevent_stock_amount()"></td><td><input class="form-control price" name="box_mrp[]" type="number" step="any" id="price" onfocus= "clearInput2(this)" required></td><td> <input class="form-control totaldis"  name="totaldis[]" type="hidden" id="totaldis"  readonly><input class="form-control discountt" name="discountt[]"  id="discountt" type="number" placeholder="%" onfocus= "clearInput3(this)"></td><td><input class="form-control total" type="number" name="total[]" placeholder="0.00 " readonly></td><td><span class="btn btn-xs btn-danger mb-1"><a class= "' + $.opt.delete.substring(1) + '" href="javascript:;" title="Remove row">Remove</a></span></td></tr>');


        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }

        return 1;
    },



    /**
     * Delete a row.
     *
     * @param elem   current element
     * @returns {number}
     */
    deleteRow: function(elem) {

        jQuery(elem).parents($.opt.parentClass).remove();

        if (jQuery($.opt.delete).length < 1) {
            jQuery($.opt.delete).hide();
        }

        return 1;
    },

    /**
     * Round a number.
     * Using: http://www.mediacollege.com/internet/javascript/number/round.html
     *
     * @param number
     * @param decimals
     * @returns {*}
     */
    roundNumber: function(number, decimals) {
        var newString; // The new rounded number
        decimals = Number(decimals);

        if (decimals < 1) {
            newString = (Math.round(number)).toString();
        } else {
            var numString = number.toString();

            if (numString.lastIndexOf(".") == -1) { // If there is no decimal point
                numString += "."; // give it one at the end
            }

            var cutoff = numString.lastIndexOf(".") + decimals; // The point at which to truncate the number
            var d1 = Number(numString.substring(cutoff, cutoff + 1)); // The value of the last decimal place that we'll end up with
            var d2 = Number(numString.substring(cutoff + 1, cutoff + 2)); // The next decimal, after the last one we want

            if (d2 >= 5) { // Do we need to round up at all? If not, the string will just be truncated
                if (d1 == 9 && cutoff > 0) { // If the last digit is 9, find a new cutoff point
                    while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                        if (d1 != ".") {
                            cutoff -= 1;
                            d1 = Number(numString.substring(cutoff, cutoff + 1));
                        } else {
                            cutoff -= 1;
                        }
                    }
                }

                d1 += 1;
            }

            if (d1 == 10) {
                numString = numString.substring(0, numString.lastIndexOf("."));
                var roundedNum = Number(numString) + 1;
                newString = roundedNum.toString() + '.';
            } else {
                newString = numString.substring(0, cutoff) + d1.toString();
            }
        }

        if (newString.lastIndexOf(".") == -1) { // Do this again, to the new string
            newString += ".";
        }

        var decs = (newString.substring(newString.lastIndexOf(".") + 1)).length;

        for (var i = 0; i < decimals - decs; i++)
            newString += "0";
        //var newNumber = Number(newString);// make it a number if you like

        return newString; // Output the result to the form field (change for your purposes)
    }
};

/**
 *  Publicly accessible defaults.
 */
jQuery.fn.invoice.defaults = {
    addRow: "#addRow",
    addProductRow: "#addProductRow",
    delete: ".delete",
    parentClass: ".item-row",


    price: ".price",
    qty: ".qty",
    Quantity: "#Quantity",
    total: ".total",
    totaldis: ".totaldis",
    // totalQty: "#totalQty",

    subtotal: "#subtotal",
    discount: "#discount",
    discountt: "#discountt",
    flatdiscount: "#flatdiscount",
    // shipping: "#shipping",
    vat: "#vat",
    grandTotal: "#grandTotal",
    afterdis:  "#afterdis",
    pay: "#pay",
    back: ".back",
    item: "#item",
    points: "#redeem_points",
    due: "#due"
};
