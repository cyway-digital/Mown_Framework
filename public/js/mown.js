/*
 * Mown JS common functions
 */

/** add active class and stay opened when selected */
var currentUri = window.location;

// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
    if (this.href) {
        return this.href == currentUri || currentUri.href.indexOf(this.href) == 0;
    }
}).addClass('active');

// for the treeview
$('ul.nav-treeview a').filter(function() {
    if (this.href) {
        return this.href == currentUri || currentUri.href.indexOf(this.href) == 0;
    }
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

const mownToken = $('meta[name="token"]').attr('content');

$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

    // $('body.editable-init').editable({
    //     selector: 'a',
    //     send: 'never',
    //     mode: 'inline'
    // }).on('save', function(e, params) {
    //     mownAppMount.$refs.child.editField(e,params)
    // });
});