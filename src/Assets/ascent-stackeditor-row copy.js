// ******

// ******
// Code (c) Kieran Metcalfe / Ascent Creative 2021


$.ascent = $.ascent?$.ascent:{};

// fix for sometimes null sender... set in 'over', unset in 'stop'
var sender = null;

var StackEditorRow = {
        
		rowCount: 0,

		_init: function () {

            // alert('row init');
            
            var self = this;
            this.widget = this;
            idAry = (this.element)[0].id.split('-');
            var thisID = (this.element)[0].id;
            var fldName = idAry[1];
            var obj = this.element;

            /* handle delete */
            $(this.element).on('click', 'A.block-delete', function() {
                
                if (confirm("Delete this item?")) {
                  
                    $(this).parents('.block').remove();

                    $(self).trigger('change');
                    
                    self.updateIndexes();
                }

                return false; 
            });

            // background handlers:
            $(this.element).on('change', '.row-bgcolor', function() {
                self.setRowBackground();
            });
            self.setRowBackground();

            $(this.element).on('change', '.block .block-bgcolor', function() {
                self.setBlockBackground($(this).parents('.block'));
            });

            // var start = 0;
            $(this.element).find('.block').each(function() {
                self.initBlock(this);
                var block = this;
                self.setBlockBackground(block);

                // also, just to check old data, we'll init the Start Column values for each block
                // should be the same as what's already stored for correctly updated pages
                // $(this).find('.block-col-start').val(start);
                // start += parseInt($(this).find('.block-col-count').val());

            });

           

              // ITEM ADD buttons:
              $(this.element).on('click', 'A.row-add-block', function() {
                self.loadBlockTemplate($(this).attr('data-block-type'));
                 $(this).closest('.show').removeClass('show');
               return false;
             });



             $(this.element).find('.blocks').sortable({
                connectWith: '.blocks',
                //containment: '.stack-edit',
                handle: '.block-handle',
                // axis: 'x',
                forcePlaceholderSize: true,
                xevert: 100,
                beforeStop: function(event, ui) {
                    console.log('BEFOE STOP');
                },
                change: function(event, ui) {
                    console.log("CCHANGE");
                },
                sort: function( event, ui ) {
                    console.log("SORT");
                },
                start: function(event, ui) {
                    $(ui.placeholder).css('height', $(ui.item).height() + 'px');
                    sender = this;
                },
                over: function(event, ui) {
                    console.log('over');
                    console.log(ui.position);
                
                     // will it fit? 
                     empty = self.getEmptyCount();
                   
                     if(sender == this) {
                        empty += parseInt($(ui.item).find('.block-col-count').val());
                     }
                  
                     if(parseInt($(ui.item).find('.block-col-count').val()) <= empty) {
                         // ok
                         //self.updateBlockIndexes();
                         $(ui.placeholder).show();
                     } else {
                        // alert('Too Big - No Space');
                         $(this).addClass('drop-not-allowed');
                         $(ui.placeholder).hide();

                     }
                },
                out: function(event, ui) {
                    $(this).removeClass('drop-not-allowed');
                    
                },
                receive: function(event, ui) {
                    console.log('receive');

                    // receiving an item from another list:

                    // will it fit? 
                    empty = self.getEmptyCount();
                    // at this point, the size of the dropped element is included...
                    empty += parseInt($(ui.item).find('.block-col-count').val());
              
                    if(parseInt($(ui.item).find('.block-col-count').val()) <= empty) {
                        // ok
                        self.updateIndexes();
                    } else {
                       // alert('Too Big - No Space');
                        $(ui.sender).sortable('cancel');
                    }

                   
                   
                },
                remove: function(event, ui) {
                    console.log('remove');
                    self.updateIndexes();
                },
                update: function(event, ui) {
                    console.log('update');
                    self.updateIndexes();
                },
                stop: function(event, ui) {
                    console.log('stop');
                   // if ($(ui.item).hasClass('number') && $(ui.placeholder).parent()[0] != this) {
                  //  $(this).sortable('cancel');
                    //}
                    sender = null;
                  
                }

            });

            this.manageGaps();


        },

        setRowBackground: function() {
            
            var bgcolor = $(this.element).find('.row-bgcolor').val();
            
            if(bgcolor == 'transparent' || bgcolor == '') {
                $(this.element).css("background-color", '');
                $(this.element).removeClass('row-has-bgcolor');
            } else {
                $(this.element).css("background-color", bgcolor);
                $(this.element).addClass('row-has-bgcolor');
            }

        },

        setBlockBackground: function(block) {

            bgcolor = $(block).find('.block-bgcolor').val();
       
            if(bgcolor == 'transparent' || bgcolor == '') {
                $(block).find('.block-content').css("background-color", '');
                $(block).find('.block-content').removeClass('block-has-bgcolor');
            } else {
                $(block).find('.block-content').css("background-color", bgcolor);
                $(block).find('.block-content').addClass('block-has-bgcolor');
            }            

        },

        getEmptyCount: function() {
            var empty = 12;
            
            $(this.element).find('.block-col-count').each(function() {
                empty -= parseInt($(this).val());
            });

            return empty;
        },


        loadBlockTemplate: function(type) {

            // check there's room
            // var empty = 12;
            // $(this.element).find(".block-col-count").each(function() {
            //     empty -= parseInt($(this).val());
            // });

            var empty = this.getEmptyCount();

            if (empty < 1) {
                alert('This row is full - resize the other elements to make room, or add a new row');
                return false;
            }


            var self = this;

            stackname = $(this.element).parents('.stackeditor').attr('id');

            rowid = $(this.element).parent().children().index(this.element);

            blockid = $(this.element).find('.block').length;

            blockname = stackname + "[rows][" + rowid + "][blocks][" + blockid + "]";

            console.log(blockname);

            $.get('/admin/stack/make-block/' + type + "/" + blockname + "/" + empty, function(data) {
                // $(self.element).find('.stack-output').before(data);
                
                var block = $(data);
                $(self.element).find('.blocks').append(block);
                console.log(block);

                self.initBlock(block);

                self.updateIndexes();
             
             });

        },


        updateIndexes: function() {

            var fldname = $(this.element).parents('.stackeditor').attr('name') + '[rows]';

            // console.log($(this.element).parents('.row-edit'));

            // console.log($(this.element).parents('.stackeditor').find('.row-edit'));

            var rowidx = $(this.element).parents('.stackeditor').find('.row-edit').index($(this.element));
            
            // reapply field indexes to represent reordering
            $(this.element).find('.block').each(function(idx) {

                console.log("NEW BLOCK");

                var prefix = fldname + '[' + rowidx + '][blocks][' + idx + ']';
                console.log(prefix);

                $(this).find('INPUT:not([type=file]), SELECT, TEXTAREA').each(function(fldidx) {

                        esc = fldname.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

                        // console.log(esc);
                        re = new RegExp(esc + "\\[\\d+\\]\\[blocks\\]\\[\\d+\\]" );
                        // console.log(re);

                        // console.log('pre: ' + this.name);
                       this.name = this.name.replace(re, prefix); 
                    //    console.log('post: ' + this.name);  
                      
                });

            });

            this.manageGaps();

            $(this.element).trigger('change');            

        },


        initBlock: function(item) {

            $(item).resizable({
                handles: 'e,w',
                placeholder: 'ui-state-highlight',
                create: function( event, ui ) {
                    // Prefers an another cursor with two arrows
                    $(".ui-resizable-handle").css("cursor","ew-resize");
                },
                start: function(event, ui){
                    // sibTotalWidth = ui.originalSize.width + ui.originalElement.next().outerWidth();
                    console.log(ui.size);

                    console.log($(ui.element).parents('.blocks').width());

                    var colcount = 12; // change this to alter the number of cols in the row.

                    var colsize = $(ui.element).parents('.blocks').width() / colcount;
                    // set the grid correctly - allows for window to be resized bewteen...
                    $(ui.element).resizable('option', 'grid', [ colsize, 0 ]);

                    // min width = 3 cols
                    $(ui.element).resizable('option', 'minWidth', (colsize * 3) -1);
                    
                    // old code: assumed blocks would be contigous and only adjusted from the right.

                    /*
                    
                    // calc the max possible width for this item (to prevent dragging larger than the row)
                    // get the col counts of items in the row
                    var filled = 0;
                    $(ui.element).parents('.blocks').find('.block').each(function() {
                        filled += parseInt($(this).find('.block-col-count').val());
                        console.log(filled);
                    });
                    // subtract the col count of this item
                    filled -= $(ui.element).find('.block-col-count').val();

                    // the difference is the max number of cols this can fill
                    empty = (colcount - filled);

                    console.log(empty);

                    // multiply to get a total max width.
                    $(ui.element).resizable('option', 'maxWidth', colsize * (colcount - filled));

                    */


                    // new code:
                    // needs to check if the current block has space to expand on the handle dragged.
                    // w = expand only if not first block, and not butted up against a block?
                    // e = expand only if not last block, and not butted up against a block? 
                    // - should this allow pushing of blocks to fill gaps? Probably! yikes...
                    var maxWidth = ui.size.width;
                    var axis = $(ui.element).data('ui-resizable').axis;


                    console.log('axis = ' + axis);
                    console.log('maxWidth = ' + maxWidth);

                    //$(ui.element).resizable('option', 'maxWidth', maxWidth);


                },

                resize: function(event, ui) {

                    console.log($(ui.element).data('ui-resizable'));

                    // console.log(ui.size.width + " :: " + $(ui.element).parents('.blocks').width());

                    // calculate the number of cols currently occupied and write to the col-count field
                    cols = (ui.size.width / $(ui.element).parents('.blocks').width()) * 12; // need to pull this from the same parameter as in 'start' - should probably widgetise this code...
                    console.log(Math.round(cols));
                    $(ui.element).find('.block-col-count').val(Math.round(cols));

                    // also set the 'start' column so we can work out gaps etc.
                    // console.log("UI = ");
                    var start = Math.round( (ui.element.position().left / $(ui.element).parents('.blocks').width()) * 12);
                    $(ui.element).find('.block-col-start').val(start);
                    //console.log('start = ' + start);


                   


                    // //console.log('left = ' + (ui.size.width / $(ui.element).parents('.blocks').position()));
                    // //start = (ui.size.width / $(ui.element).parents('.blocks').width()) * 12;



                    // // also adjust a placeholder before or after the current block.

                    $(ui.element).css('left', '0px');

                    $(this).parents('.row-edit').stackeditorrow('manageGaps');

                    

                    // console.log(event);
                    // console.log(ui);

                    // // return e or w - the handle dragged.
                    // console.log($(ui.element).data('ui-resizable').axis);
                    // //if dragged 'w', a placeholder/gap is needed before the block.

                    // // if dragged 'e', a placeholder/gap is needed after the block.

                    // // need to see if a 'gap' already exists. Create one if not.

                    // // or do we just call a "gapManagement" function on the row? 
                    // // yeah, lets do that :)

                    // console.log(widget);
                    // console.log(self);
                    // console.log(this);

                    // 
                      // if the previous block is not a placeholder, create one.
            
                    

                    // $(this).parents('.row-edit').find('.block').each(function() {
                    //     $(this).css('left', '0px');
                    // });
                    
                },

                stop: function(event, ui) {

                    //$(ui.element).css('width', $(ui.element).width() + 'px');

                    var pct = $(ui.element).width() / $(ui.element).parents('.items').width() *100;
                    $(ui.element).css('width', pct + '%');

                    $(ui.element).trigger('change');
                }

            });

        },


        manageGaps:function() {

            var iCol = 0;
            var colcount = 12; // change this to alter the number of cols in the row.
            var colsize = 100/colcount; //$(this.element).find('.blocks').width() / colcount;

            console.log('blockswith');
            console.log($(this.element).width());

            $(this.element).find('.placeholder').remove();

            $(this.element).find('.block').each(function() {
                
                var start = parseInt($(this).find('.block-col-start').val());
                var count = parseInt($(this).find('.block-col-count').val());

                if (start - iCol > 0) {

                    console.log(colsize);   
                    console.log(start);
                    console.log(iCol);
                    
                    var phsize = colsize * (start - iCol);

                    console.log('ddding: ' + phsize);
                    $(this).before('<div class="placeholder" style="width: ' + phsize + '%"></div>');

                   

                }

                iCol = start + count;

            });


            // add a placeholder at the end if needed:
            console.log('final = ' + iCol);
            if (iCol < colcount) {
                 $(this.element).find('.blocks').append('<div class="placeholder" style="width: ' + ((colcount - iCol) * colsize) + '%"></div>');
            }
           
        },

        

        oldmanageGaps: function() {
            console.log("MIND THE GAP");
            console.log(this);

            var cols = [];
            $(this.element).find('.block').each(function(block) {
                //console.log(tthis);
                var start = parseInt($(this).find('.block-col-start').val());
                var count = parseInt($(this).find('.block-col-count').val());
                console.log(start + " / "+ count);
                
                for(iCol = start; iCol < (start+count); iCol++) {
                    // $(this).find('.block-col-start').val()
                    cols.push(iCol);
                }
            });

            console.log(cols);
            
            var gaps = [];

            var blocks =  $(this.element).find('.block');
            var idxBlock = 0;
            // console.log(block);

            var gap = [];
            var lastgap = null;
            for(i = 0; i < 12; i++) {

                console.log(cols.indexOf(i));

                if (cols.indexOf(i) == -1) {
                    if (lastgap != null && lastgap != (i-1)) {
                        gaps.push(gap);
                        gap = [];
                    }
                    gap.push(i);
                    lastgap = i;
                }

            }

            gaps.push(gap);

            console.log(gaps);





            // loop blocks and find gaps.

            // 


            // var colcount = 12; // change this to alter the number of cols in the row.

            // var colsize = $(this.element).find('.blocks').width() / colcount;

           

            // var end = 0;

            
            
            // $(this.element).find('.block-col-start').each(function() {

            //     console.log('start = ' + $(this).val());
            
            // });




            // var colcount = 12; // change this to alter the number of cols in the row.

            // var colsize = $(ui.element).parents('.blocks').width() / colcount;

            // console.log("prev:");
            // console.log(ui.element.prev()[0]);
   
            // var prev = ui.element.prev()[0];
            // var placeholder = null;
            // console.log($(prev).hasClass('placeholder'));
            // if(!prev || !$(prev).hasClass('placeholder')) {
            //     $(ui.element).before('<DIV class="placeholder" style="border: 1px solid;">PH</DIV>');
            // }
            
            // placeholder = ui.element.prev()[0];
            
            // console.log(placeholder);
            // $(placeholder).css('width', colsize + 'px');


            

        }

}

$.widget('ascent.stackeditorrow', StackEditorRow);
$.extend($.ascent.StackEditorRow, {
		 
		
}); 