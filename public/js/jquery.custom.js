(function($)
{

	$.fn.numeric = function(min, max)
	{
		$(this).on("keypress keyup blur",function (event) {

        	$(this).val($(this).val().replace(/[^\d].+/, ""));

	        if ((event.which < 48 || event.which > 57)) {
	            event.preventDefault();
	        }

    	});



		$(this).focusout(function()
		{
			if(min != null)
	    	{
	    		if($(this).val() < min)
	    		{
	    			$(this).val(min);
	    		}		
	    	}
		});

		if(min != null)
	    {
	    	if($(this).val() < min)
	    	{
	    		$(this).val(min);
	    	}		
	    }
	};

})(jQuery);