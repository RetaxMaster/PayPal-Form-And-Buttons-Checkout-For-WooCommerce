$(document).ready(function(){

    var FUNDING_SOURCES = [
        paypal.FUNDING.PAYPAL,
        paypal.FUNDING.VENMO,
        paypal.FUNDING.CREDIT,
        paypal.FUNDING.CARD
    ];
    
    // Loop over each funding source / payment method
    FUNDING_SOURCES.forEach(function(fundingSource) {
    
        // Initialize the buttons
        var button = paypal.Buttons({
            fundingSource: fundingSource
        });
    
        // Check if the button is eligible
        if (button.isEligible()) {
            // Render the standalone button for that funding source
            button.render('#rm_paypal_buttons');
        }

    });

});