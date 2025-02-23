$(function() {
    $(".autocomplete").each(function() {
        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "assets/fetch_autocomplete_agents.php",
                    dataType: "json",
                    data: {
                        term: request.term,
                        column: $(this.element).attr('id')
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2
        });
    });
});