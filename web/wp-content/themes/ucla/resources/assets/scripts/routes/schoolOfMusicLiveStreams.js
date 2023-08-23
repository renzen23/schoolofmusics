export default {
    init() {
      // JavaScript to be fired on the livestream page
        if (location.hash !== '') {
            var array = new Array();
            $('.tab-content > div').each(function(){
                array.push("#"+$(this).attr('id')); 
            });

            if ($.inArray(location.hash, array)!=-1) {
                $('a[href="' + location.hash + '"]').tab('show');
            } else {
                $('a[href="#other"]').tab('show');
            }
        }
    },
  };
  