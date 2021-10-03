// ******

// ******
// Code (c) Kieran Metcalfe / Ascent Creative 2021


$.ascent = $.ascent?$.ascent:{};

var StackEditor = {
        
		rowCount: 0,

		_init: function () {

            
			var self = this;
			this.widget = this;
			
			idAry = (this.element)[0].id.split('-');
			
			var thisID = (this.element)[0].id;
			
			var fldName = idAry[1];
			
            var obj = this.element;
            
            // make the stack sortable (drag & drop)
            $(this.element).find('.stack-rows').sortable({
                axis: 'y',
                handle: '.row-handle',
                start: function(event, ui) {
                    $(ui.placeholder).css('height', $(ui.item).height() + 'px');
                },
                update: function(event, ui) {
                    
                    self.updateIndexes();

                }
            });

            //capture the submit event of the form to serialise the stack data
            $(this.element).parents('form').on('submit', function() {

                self.serialise();

            });


            $(this.element).on('change', function() {

                self.serialise();

            });



            $(this.element).on('click', '.row-delete', function() {

                if (confirm("Delete this row?")) {
                    $(this).parents('.row-edit').remove();
                    self.updateIndexes();
                }

                return false;
            }); 
            

            // capture the click event of the add block button
            // (test for now - adds a new row block. Will need to be coded to ask user what block to add)
            $(this.element).on('click', '.stack-add-row', function() {

                //var type = 'row';\
            
                var type = $(this).attr('data-block-type');
                var field = $(this).attr('data-block-field'); //'content';
                var idx = $(self.element).find('.row-edit').length;

            //    alert(idx);

                $.get('/admin/stack/make-row/' + type + '/' + field + '/' + idx, function(data) {
                   // $(self.element).find('.stack-output').before(data);
                   $(self.element).find('.stack-rows').append(data);
                   self.updateIndexes();                
                });

             //   alert('hide...');
                $('.btn.dropdown-toggle').dropdown('hide');

                return false;

            });


            this.serialise();


		},

        serialise: function() {

            var data = $(this.element).find('INPUT, SELECT, TEXTAREA').not('.stack-output').serializeJSON();

           // console.log(data);
         //  return false;
         
            // remove the top level wrapper (which is just the field name):
            for(fld in data) {
          
                $(this.element).find('.stack-output').val(
                    JSON.stringify(data[fld])
                );

            }

        },

        updateIndexes: function() {

            // console.log('UBI - Stack');

            // console.log($(this.element).find('.block-edit'));

            var fldname = $(this.element).attr('name') + '[rows]';

            // reapply field indexes to represent reordering
            $(this.element).find('.row-edit').each(function(idx) {

                var prefix = fldname + "[" + idx + "]";

                $(this).find('INPUT:not([type=file]), SELECT, TEXTAREA').each(function(fldidx) {

                        esc = fldname.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

                        re = new RegExp(esc + "\\[\\d+\\]");
                       
                        this.name = this.name.replace(re, prefix);   
                       
                   // $('#frm_edit').addClass('dirty'); //trigger('checkform.areYouSure');
                    
                });

            });

            $(this.element).trigger('change');

        }   

}

$.widget('ascent.stackeditor', StackEditor);
$.extend($.ascent.StackEditor, {
		 
		
}); 