/**
 * Bill loader for transaction bill select
 * @author techguytom
 */
jQuery(document).ready(function($) {
    $('#transaction_bill').change(function() {
        var id = this.value;
        window.location.assign("/" + id);
    });
});