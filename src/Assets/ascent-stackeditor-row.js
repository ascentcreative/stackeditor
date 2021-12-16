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

            $(this.element).find('.block').each(function() {
                self.initBlock(this);
                var block = this;
                self.setBlockBackground(block);
            });


            // Handler for when blocks are re-ordered / dragged between rows etc
           
            $(this.element).on('blocks-updated', function() {
                console.log('update event caught', this);
                self.manageGaps();
                self.updateIndexes();
            });


            // When a placeholder is clicked, we should load a new block editor into it
            // need to go via some form of list to select the type - I'm thinking a modal...
            $(this.element).on('click', '.placeholder', function() {


                var ph = this;
                //var self = this;

                console.log("PH CLICKED!");

                stackname = $(self.element).parents('.stackeditor').attr('id');
    
                rowid = $(self.element).parent().children().index(self.element);
    
                blockid = $(self.element).find('.block').length;
    
                blockname = stackname + "[rows][" + rowid + "][blocks][" + blockid + "]";
    
                // console.log(blockname);

                empty = 12;
                type = 'text'; // hardcoded for testing
    
                $('#block-picker').modal();


                $('#block-picker').one('click', 'a', function(e) {

                    e.preventDefault();

                    var type = $(this).data('block-type');

                    $('#block-picker').modal('hide');

                    // return;

                    console.log(ph);

                    // fire off a request to load the edit blade for the requested block type:
                    $.get('/admin/stack/make-block/' + type + "/" + blockname + "/" + empty, function(data) {
                        // $(self.element).find('.stack-output').before(data);
                        
                        var block = $(data);
                        //$(self.element).find('.blocks').append(block);
                        console.log(block);
    
                        var elm = block.get(0);
                        
                        $(elm).find('.block-col-start').val($(ph).data('start'));
                        $(elm).find('.block-col-count').val($(ph).data('cols'));
                        $(elm).css('width', $(ph).css('width'));
    
    
    
                        $(ph).replaceWith(elm);
    
                        console.log('** ELM **', elm);
                        for(var i = 1; i < block.length; i++) {
                            console.log('** appending **', block.get(i));
        
                            $('body').append(block.get(i));
                        }
    
    
                        self.initBlock(elm);
        
                        self.updateIndexes();
                     
                     });


                });

                // if the user clicks outside the modal, ensure the click handler is removed
                $('#block-picker').on('hidden.bs.modal', function() {
                    $('#block-picker').off('click', 'button');
                });

                return;

                



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

            // console.log(blockname);

            $.get('/admin/stack/make-block/' + type + "/" + blockname + "/" + empty)
                .done(function(data) {
                // $(self.element).find('.stack-output').before(data);
                
                var block = $(data);
                $(self.element).find('.blocks').append(block);
                // console.log(block);

                self.initBlock(block[0]);

                self.updateIndexes();
             
             }).fail(function(data) {
                 console.log('fail', data);
             });

        },


        updateIndexes: function() {

            var fldname = $(this.element).parents('.stackeditor').attr('name') + '[rows]';

            // // console.log($(this.element).parents('.row-edit'));

            // // console.log($(this.element).parents('.stackeditor').find('.row-edit'));

            var rowidx = $(this.element).parents('.stackeditor').find('.row-edit').index($(this.element));
            
            // reapply field indexes to represent reordering
            $(this.element).find('.block').each(function(idx) {

                // console.log("NEW BLOCK");

                var prefix = fldname + '[' + rowidx + '][blocks][' + idx + ']';
                // console.log(prefix);

                $(this).find('INPUT:not([type=file]), SELECT, TEXTAREA').each(function(fldidx) {

                        esc = fldname.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

                        // // console.log(esc);
                        re = new RegExp(esc + "\\[\\d+\\]\\[blocks\\]\\[\\d+\\]" );
                        // // console.log(re);

                        // // console.log('pre: ' + this.name);
                       this.name = this.name.replace(re, prefix); 
                    //    // console.log('post: ' + this.name);  
                      
                });

            });

            this.manageGaps();

            $(this.element).trigger('change');            

        },


        initBlock: function(item) {

            // // console.log('initBlock');

            // console.log(item);

            createInbetweener = function() {
                $ib = $('<div class="inbetweener"></div>').droppable({
                    tolerance: 'pointer',
                    greedy: true,
                    accept: function(d) {
                        if(d.hasClass('block')) {
                            //console.log(d[0], $(this).next()[0]);
                            if(d[0] == $(this).next()[0] || d[0] == $(this).prev()[0]) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return false;
                        }
                    },
                    drop: function(event, ui) {
                        // ok... we've got a block dropped on this inbetweener.

                        // for all elements to the left of this item, decrease the 'start' by the width of this element.
                        // is this right? no...

                        // need to detect where moving block left or right
                        // need to exclude elements past the original starting position.

                        // i.e. need to find all blocks between IB and block

                        console.log('was Before?', $(this).prevAll('.block').index(ui.draggable));
                        console.log('was After?', $(this).nextAll('.block').index(ui.draggable));

                        

                        var wasBefore = $(this).prevAll('.block').index(ui.draggable) != -1;
                        var wasAfter = $(this).nextAll('.block').index(ui.draggable) != -1;

                        if(wasBefore) {
                            var between = $(this).prevUntil(ui.draggable, '.block, .placeholder');
                        } else {
                            var between = $(this).nextUntil(ui.draggable, '.block, .placeholder');
                        }

                        between.each(function() {

                            if($(this).hasClass('placeholder')) {

                                var start = $(this).data('start')
                                var cols = parseInt(ui.draggable.find('.block-col-count').val())
                                start = start + (cols * (wasBefore ? -1 : 1));

                                $(this).data('start', start);

                            }

                            if($(this).hasClass('block')) {
                                console.log($(this).find('.block-col-start'));
                                
                                var start = parseInt($(this).find('.block-col-start').val());
                                var cols = parseInt(ui.draggable.find('.block-col-count').val())
                                start = start + (cols * (wasBefore ? -1 : 1));

                                $(this).find('.block-col-start').val(start);
                            }
                        });


                        var prev = $(this).prev();
                        var newStart = 0;
                        if(prev) {
                            console.log("**PREV**", prev);
                            if(prev.hasClass('placeholder')) {
                                var newStart = $(prev).data('start') + $(prev).data('cols');
                            }
                            if(prev.hasClass('block')) {
                                console.log('--', parseInt($(prev).find('.block-col-start').val()));
                                console.log('--', parseInt($(prev).find('.block-col-count').val()));
                                var newStart = parseInt($(prev).find('.block-col-start').val()) + parseInt($(prev).find('.block-col-count').val());
                            }
                        }


                        ui.draggable.find('.block-col-start').val(newStart);

                         // replace the elements
                         $(this).replaceWith(ui.draggable.css('position', '').css('top', '').css('z-index', '').css('left', ''));

                         ui.draggable.trigger('blocks-updated');

                    }
                });
                 return $ib;
            };

            $(item).draggable({
               // containment: '.stack-rows',
               zIndex: 1000,
               opacity: 0.8,
               handle: '.block-handle',
                revert: 'invalid',
                revertDuration: 0,
                start: function(event, ui) {
                    // create inbetweeners around each block in the row
                    $(this).parents('.blocks').find('.block, .placeholder').each(function(idx) {

                        $(this).before(createInbetweener());

                    });

                    $(this).parents('.blocks').append(createInbetweener());

                }, 
                stop: function(event, ui) {
                    $('.inbetweener').remove();
                }
            });

            // console.log('post draggable init');

            $(item).resizable({
                handles: 'e,w',
                placeholder: 'ui-state-highlight',
                create: function( event, ui ) {
                    // Prefers an another cursor with two arrows
                    console.log('RS Create');
                    $(".ui-resizable-handle").css("cursor","ew-resize");
                },
                start: function(event, ui){

                    $(ui.element).data('block-last-width', ui.size.width);

                    var colcount = 12; // change this to alter the number of cols in the row.

                    console.log('width at start:' + $(ui.element).parents('.blocks').width());

                    var colsize = $(ui.element).parents('.blocks').width() / colcount;
                    // set the grid correctly - allows for window to be resized bewteen...
                    $(ui.element).resizable('option', 'grid', [ colsize, 0 ]);

                    // min width = 3 cols
                    $(ui.element).resizable('option', 'minWidth', (colsize * 3) -1);

                    $(ui.element).data('originalStart', $(ui.element).find('.block-col-start').val());
                    $(ui.element).data('originalCols', $(ui.element).find('.block-col-count').val());
                    console.log($(ui.element).data('originalStart'));
                    
                  

                    // new code:
                    // needs to check if the current block has space to expand on the handle dragged.
                    // w = expand only if not first block, and not butted up against a block?
                    // e = expand only if not last block, and not butted up against a block? 
                    // - should this allow pushing of blocks to fill gaps? Probably! yikes...
                    var maxWidth = ui.size.width;
                    var axis = $(ui.element).data('ui-resizable').axis;

                    switch(axis) {
                        case 'e':
                            var ph = $(ui.element).next();
                        break;

                        case 'w':
                            var ph = $(ui.element).prev();
                        break;
                    }

                    console.log(ph);

                    var phwidth = 0;
                    if ($(ph).hasClass('placeholder')) {
                       phwidth = $(ph).width();
                    } 

                    // console.log('phwidth = ' + phwidth);
                    // console.log($(ui.element).width() + ' vs ' + ui.size.width);
                    
                    maxWidth = $(ui.element).width() + phwidth + 1; // -1; // + 1; // need to add a small shim to allow a block to expand past the final placeholder
                    
                    console.log('axis = ' + axis);
                    console.log('maxWidth = ' + maxWidth);

                    $(ui.element).resizable('option', 'maxWidth', maxWidth);


                },

                resize: function(event, ui) {

                    // Need to work out based on absolute values as we might lose events on a fast drag
                    
                    // get the new cols width
                    var newcols = Math.round(ui.size.width /  ($(ui.element).parents('.blocks').width() / 12), 2);
                    $(ui.element).find('.block-col-count').val(newcols);

                    var axis = $(ui.element).data('ui-resizable').axis;
                    if(axis=="w") {
                        // if dragging the left side, start will change - work out based on the change in column width
                        $(ui.element).find('.block-col-start').val(
                            parseInt($(ui.element).data('originalStart')) + (parseInt($(ui.element).data('originalCols') - newcols))
                            );
                    }

                    $(ui.element).css('left', '0px');

                    $(this).parents('.row-edit').stackeditorrow('manageGaps');
                
                    
                },

                stop: function(event, ui) {
                    $(ui.element).trigger('change');
                }

            });

           

        },



        manageGaps:function() {

            makePlaceholder = function(cols) {

                width = (100/12) * cols;

                ph = $('<div class="placeholder" style="width: ' + width + '%"></div>');

                ph.append('<div class="placeholder-inner"><div class="placeholder-icon bi-plus-circle-fill"></div><div class="placeholder-label">Click to create a new block, or drag an existing block here</div></div>');

                ph.addClass('cols-' + cols);

                return ph;
            };

            var self = this;

            // console.log('start ManageGaps');

            var iCol = 0;
            var colcount = 12; // change this to alter the number of cols in the row.
            var colsize = 100/colcount; //$(this.element).find('.blocks').width() / colcount;

            $(this.element).find('.placeholder').remove();

            $(this.element).find('.block:not(.ui-sortable-placeholder)').each(function() {
                
                var start = parseInt($(this).find('.block-col-start').val());
                var count = parseInt($(this).find('.block-col-count').val());

                // console.log('start', start, isNaN(start));

                if(isNaN(start)) {
                    console.log(this);
                }

                if (!isNaN(start) && start - iCol > 0) {
                    
                    var phsize = (start - iCol);
                    console.log('insering placeholder');

                    var ph = makePlaceholder(phsize);

                    ph.data('start', iCol);
                    ph.data('cols', start-iCol);

                    $(this).before(ph);

                    // console.log('ph', ph);
                    // console.log(ph.data());

                }

                iCol = start + count;

            });


            // add a placeholder at the end if needed:
            if (iCol < colcount) {

                var ph = makePlaceholder(colcount - iCol);

                ph.data('start', iCol);
                ph.data('cols', colcount-iCol);

                $(this.element).find('.blocks').append(ph);

            }

            
            $(this.element).find('.blocks .placeholder').droppable({
                tolerance: 'pointer',
                accept: '.block',
                over: function(event, ui) {
                    console.log('droppable over');
                    console.log(event, ui);
                },
                drop: function(event, ui) {
                    
                    // note the row the block came from.
                    var oldrow = ui.draggable.parents('.blocks');

                    // copy in the new column info from the placeholder:
                    ui.draggable.find('.block-col-start').val($(this).data('start'));
                    ui.draggable.find('.block-col-count').val($(this).data('cols'));

                    // replace the elements
                    $(this).replaceWith(ui.draggable.css('width', $(this).css('width')).css('position', '').css('top', '').css('z-index', '').css('left', ''));

                    // issue block update events:
                    if (oldrow[0] != ui.draggable.parents('.blocks')[0]) {
                        // only update old row if the row has changed
                        oldrow.trigger('blocks-updated');
                    }
                    
                    // update the (new) parent row
                    ui.draggable.parents('.blocks').trigger('blocks-updated');


                }
            });

            
           
        },

        

        oldmanageGaps: function() {
            console.log("MIND THE GAP");
            // console.log(this);

            var cols = [];
            $(this.element).find('.block').each(function(block) {
                //console.log(tthis);
                var start = parseInt($(this).find('.block-col-start').val());
                var count = parseInt($(this).find('.block-col-count').val());
                // console.log(start + " / "+ count);
                
                for(iCol = start; iCol < (start+count); iCol++) {
                    // $(this).find('.block-col-start').val()
                    cols.push(iCol);
                }
            });

            // console.log(cols);
            
            var gaps = [];

            var blocks =  $(this.element).find('.block');
            var idxBlock = 0;
            // // console.log(block);

            var gap = [];
            var lastgap = null;
            for(i = 0; i < 12; i++) {

                // console.log(cols.indexOf(i));

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

            // console.log(gaps);





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