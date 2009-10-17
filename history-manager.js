var $j = jQuery.noConflict();
$j(document).ready(function() {

$j(".widget_history_manager ul.use_js").accordion({
header: 'a.whm-link-header',
//header: 'a:not(.hm-list a)',
collapsible: true,
active: 0,
autoHeight: false
});

});

/*
//event: 'click',
//activeClass: 'selected',
collapsible: true
//autoHeight: false,
//active: false
*/