var RentalsImporter = {
    init: function(rentals) {
        this.categories = this.getCategories(rentals);
        this.rentals = rentals;
    },

    categories: [],
    rentals: [],

    getCategories: function(rentals){
        return _.chain(rentals)
            .map(function(rental){
                return rental.categoryName;
            })
            .uniq()
            .value();
    },
    startImporter: function(){
        jQuery.ajax({
            type: "POST",
            url: pluginUrl + 'importer/importer.php',
            data: {
                categories: this.categories,
                rentals: this.rentals
            },
            success: function(){
                alert("Import successful");
            }
        });
    }
};

jQuery(document).ready(function(){
    RentalsImporter.init(rentals);
});

