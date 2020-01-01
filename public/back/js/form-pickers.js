// MAterial Date picker    
    $('.mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false, minDate: new Date() });
    // $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
    // $('#date-format').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });

    // $('#min-date').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', minDate: new Date() });
    // Clock pickers
    // $('#single-input').clockpicker({
    //     placement: 'bottom',
    //     align: 'left',
    //     autoclose: true,
    //     'default': 'now'
    // });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
    }).find('input').on('change', function() {
        console.log(this.value);
    });
//     $('#check-minutes').on('click', function(e) {
//         // Have to stop propagation here
//         e.stopPropagation();
//         input.clockpicker('show').clockpicker('toggleView', 'minutes');
//     });
//     if (/mobile/i.test(navigator.userAgent)) {
//         $('input').prop('readOnly', true);
//     }
//     // Colorpicker
//     $(".colorpicker").asColorPicker();
//     $(".complex-colorpicker").asColorPicker({
//         mode: 'complex'
//     });
//     $(".gradient-colorpicker").asColorPicker({
//         mode: 'gradient'
//     });
//     // Date Picker
//     jQuery('.mydatepicker, #datepicker').datepicker();
//     jQuery('#datepicker-autoclose').datepicker({
//         autoclose: true,
//         todayHighlight: true
//     });
//     jQuery('#date-range').datepicker({
//         toggleActive: true
//     });
//     jQuery('#datepicker-inline').datepicker({
//         todayHighlight: true
//     });
//     // Daterange picker
//     $('.input-daterange-datepicker').daterangepicker({
//         buttonClasses: ['btn', 'btn-sm'],
//         applyClass: 'btn-danger',
//         cancelClass: 'btn-inverse'
//     });
//     $('.input-daterange-timepicker').daterangepicker({
//         timePicker: true,
//         format: 'MM/DD/YYYY h:mm A',
//         timePickerIncrement: 30,
//         timePicker12Hour: true,
//         timePickerSeconds: false,
//         buttonClasses: ['btn', 'btn-sm'],
//         applyClass: 'btn-danger',
//         cancelClass: 'btn-inverse'
//     });
//     $('.input-limit-datepicker').daterangepicker({
//         format: 'MM/DD/YYYY',
//         minDate: '06/01/2015',
//         maxDate: '06/30/2015',
//         buttonClasses: ['btn', 'btn-sm'],
//         applyClass: 'btn-danger',
//         cancelClass: 'btn-inverse',
//         dateLimit: {
//             days: 6
//         }
//     });




// $(function() {
//     // Basic
//     $('.dropify').dropify();

//     // Translated
//     $('.dropify-fr').dropify({
//         messages: {
//             default: 'Glissez-déposez un fichier ici ou cliquez',
//             replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
//             remove: 'Supprimer',
//             error: 'Désolé, le fichier trop volumineux'
//         }
//     });

//     // Used events
//     var drEvent = $('#input-file-events').dropify();

//     drEvent.on('dropify.beforeClear', function(event, element) {
//         return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
//     });

//     drEvent.on('dropify.afterClear', function(event, element) {
//         alert('File deleted');
//     });

//     drEvent.on('dropify.errors', function(event, element) {
//         console.log('Has Errors');
//     });

//     var drDestroy = $('#input-file-to-destroy').dropify();
//     drDestroy = drDestroy.data('dropify')
//     $('#toggleDropify').on('click', function(e) {
//         e.preventDefault();
//         if (drDestroy.isDropified()) {
//             drDestroy.destroy();
//         } else {
//             drDestroy.init();
//         }
//     })
// });
 


// $(function() {

// $('.textarea_editor').wysihtml5();
// });

